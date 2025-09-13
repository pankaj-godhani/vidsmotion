<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use Carbon\Carbon;

class ProcessRazorpayAutoRenewals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:razorpay-auto-renew';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process auto-renewals using Razorpay subscription API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Razorpay auto-renewal processing...');

        // Initialize Razorpay API
        $api = new Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));

        // Find subscriptions that are expiring soon (within 1 day) and have auto-renew enabled
        $expiringSubscriptions = Payment::where('is_active', true)
            ->where('auto_renew', true)
            ->where('subscription_end', '<=', now()->addDay())
            ->where('subscription_end', '>', now())
            ->whereNotNull('razorpay_subscription_id')
            ->get();

        $this->info("Found {$expiringSubscriptions->count()} subscriptions expiring soon with auto-renew enabled.");

        $renewedCount = 0;
        $failedCount = 0;

        foreach ($expiringSubscriptions as $subscription) {
            try {
                $this->processRazorpayAutoRenewal($api, $subscription);
                $renewedCount++;
                $this->info("Successfully processed auto-renewal for subscription ID: {$subscription->id}");
            } catch (\Exception $e) {
                $failedCount++;
                $this->error("Failed to process auto-renewal for subscription ID: {$subscription->id}. Error: {$e->getMessage()}");

                Log::error('Razorpay auto-renewal failed', [
                    'subscription_id' => $subscription->id,
                    'user_id' => $subscription->user_id,
                    'razorpay_subscription_id' => $subscription->razorpay_subscription_id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        $this->info("Auto-renewal processing completed. Renewed: {$renewedCount}, Failed: {$failedCount}");

        Log::info('Razorpay auto-renewal command completed', [
            'total_processed' => $expiringSubscriptions->count(),
            'renewed_count' => $renewedCount,
            'failed_count' => $failedCount,
            'timestamp' => now()->toISOString()
        ]);

        return Command::SUCCESS;
    }

    /**
     * Process auto-renewal for a single subscription using Razorpay API
     */
    private function processRazorpayAutoRenewal(Api $api, Payment $subscription)
    {
        $user = $subscription->user;

        if (!$user) {
            throw new \Exception("User not found for subscription ID: {$subscription->id}");
        }

        Log::info('Processing Razorpay auto-renewal', [
            'subscription_id' => $subscription->id,
            'user_id' => $user->id,
            'user_email' => $user->email,
            'razorpay_subscription_id' => $subscription->razorpay_subscription_id,
            'plan_type' => $subscription->plan_type,
            'current_end_date' => $subscription->subscription_end
        ]);

        try {
            // Get subscription details from Razorpay
            $razorpaySubscription = $api->subscription->fetch($subscription->razorpay_subscription_id);

            Log::info('Razorpay subscription details', [
                'subscription_id' => $subscription->id,
                'razorpay_status' => $razorpaySubscription->status,
                'razorpay_current_start' => $razorpaySubscription->current_start,
                'razorpay_current_end' => $razorpaySubscription->current_end,
                'razorpay_ended_at' => $razorpaySubscription->ended_at ?? 'null'
            ]);

            // Check if subscription is active in Razorpay
            if ($razorpaySubscription->status !== 'active') {
                throw new \Exception("Razorpay subscription is not active. Status: {$razorpaySubscription->status}");
            }

            // Get the plan ID from the existing subscription
            $planId = $this->getPlanIdFromSubscription($razorpaySubscription);

            // Create a new subscription for the next billing cycle
            $newSubscriptionData = [
                'plan_id' => $planId, // Use the PLAN ID, not subscription ID
                'customer_id' => $this->getOrCreateCustomerId($api, $user),
                'total_count' => 1, // One-time renewal
                'quantity' => 1,
                'start_at' => $subscription->subscription_end->timestamp,
                'expire_by' => $subscription->subscription_end->addDays(7)->timestamp, // 7 days buffer
                'notify_info' => [
                    'notify_phone' => $user->phone ?? '',
                    'notify_email' => $user->email
                ]
            ];

            // Create new subscription in Razorpay
            $newRazorpaySubscription = $api->subscription->create($newSubscriptionData);

            Log::info('New Razorpay subscription created', [
                'subscription_id' => $subscription->id,
                'new_razorpay_subscription_id' => $newRazorpaySubscription->id,
                'new_subscription_status' => $newRazorpaySubscription->status
            ]);

            // Calculate new end date
            $newEndDate = $this->calculateNewEndDate($subscription);

            // Create new payment record for the renewal
            $renewalPayment = Payment::create([
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_name' => $user->name,
                'razorpay_payment_id' => null, // Will be updated when payment is made
                'razorpay_order_id' => null, // Will be updated when payment is made
                'razorpay_signature' => null, // Will be updated when payment is made
                'razorpay_subscription_id' => $newRazorpaySubscription->id,
                'plan_name' => $subscription->plan_name,
                'plan_type' => $subscription->plan_type,
                'amount' => $subscription->amount,
                'currency' => 'INR',
                'status' => 'pending', // Will be updated to completed when payment is made
                'payment_date' => null, // Will be updated when payment is made
                'receipt_id' => 'auto_renew_' . time() . '_' . $subscription->plan_type,
                'razorpay_response' => [
                    'subscription_id' => $newRazorpaySubscription->id,
                    'status' => $newRazorpaySubscription->status,
                    'created_at' => now()->toISOString(),
                    'auto_renewal' => true
                ],
                'ip_address' => null,
                'user_agent' => 'Auto-renewal system',
                'subscription_start' => $subscription->subscription_end,
                'subscription_end' => $newEndDate,
                'is_active' => false, // Will be activated when payment is completed
                'auto_renew' => true,
            ]);

            // Deactivate the old subscription
            $subscription->update([
                'is_active' => false,
                // Keep original subscription_end unchanged
            ]);

            Log::info('Razorpay auto-renewal processed successfully', [
                'old_subscription_id' => $subscription->id,
                'new_payment_id' => $renewalPayment->id,
                'new_razorpay_subscription_id' => $newRazorpaySubscription->id,
                'new_end_date' => $newEndDate,
                'user_id' => $user->id
            ]);

            // TODO: Send notification to user about successful auto-renewal
            $this->info("Auto-renewal setup completed for user {$user->email}. New subscription ID: {$newRazorpaySubscription->id}");

        } catch (\Exception $e) {
            Log::error('Razorpay auto-renewal processing failed', [
                'subscription_id' => $subscription->id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Get or create customer ID in Razorpay
     */
    private function getOrCreateCustomerId(Api $api, User $user)
    {
        try {
            // Try to find existing customer by email
            $customers = $api->customer->all(['email' => $user->email]);

            if ($customers->count > 0) {
                return $customers->items[0]->id;
            }
        } catch (\Exception $e) {
            Log::info('No existing customer found, creating new one', [
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);
        }

        // Create new customer
        $customerData = [
            'name' => $user->name,
            'email' => $user->email,
            'contact' => $user->phone ?? '',
        ];

        $customer = $api->customer->create($customerData);

        Log::info('New Razorpay customer created', [
            'user_id' => $user->id,
            'customer_id' => $customer->id,
            'customer_email' => $customer->email
        ]);

        return $customer->id;
    }

    /**
     * Get plan ID from Razorpay subscription
     */
    private function getPlanIdFromSubscription($razorpaySubscription): string
    {
        // Extract plan ID from the subscription
        if (isset($razorpaySubscription->plan_id)) {
            return $razorpaySubscription->plan_id;
        }

        // Fallback: get plan ID from our plan details based on amount
        $amount = $razorpaySubscription->plan->item->amount ?? 0;

        return match ($amount) {
            5000 => 'plan_RGmQmqwhgJIrHe', // Standard plan (₹50)
            10000 => 'plan_RGmR6HlQ7mW0pP', // Pro Monthly (₹100)
            110000 => 'plan_RGmRITENfJFYMj', // Premier Yearly (₹1100)
            default => 'plan_RGmQmqwhgJIrHe', // Default to Standard
        };
    }

    /**
     * Calculate new subscription end date based on plan type
     */
    private function calculateNewEndDate(Payment $subscription): Carbon
    {
        $startDate = $subscription->subscription_end; // Start from where the old subscription ended

        return match ($subscription->plan_type) {
            'Standard' => $startDate->addDays(7),
            'Pro Monthly' => $startDate->addMonth(),
            'Premier Yearly' => $startDate->addYear(),
            default => $startDate->addMonth(), // Default to monthly
        };
    }
}
