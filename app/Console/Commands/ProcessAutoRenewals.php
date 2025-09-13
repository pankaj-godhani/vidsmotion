<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProcessAutoRenewals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:auto-renew';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process auto-renewals for expired subscriptions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting auto-renewal process...');

        // Find subscriptions that expired today and have auto-renew enabled
        $expiredSubscriptions = Payment::where('is_active', true)
            ->where('auto_renew', true)
            ->where('status', 'completed')
            ->whereDate('subscription_end', '<=', now())
            ->with('user')
            ->get();

        $this->info("Found {$expiredSubscriptions->count()} subscriptions eligible for auto-renewal");

        $renewedCount = 0;
        $failedCount = 0;

        foreach ($expiredSubscriptions as $subscription) {
            try {
                $this->processAutoRenewal($subscription);
                $renewedCount++;
                $this->info("✅ Auto-renewed subscription for user: {$subscription->user->name}");
            } catch (\Exception $e) {
                $failedCount++;
                $this->error("❌ Failed to auto-renew subscription for user: {$subscription->user->name} - {$e->getMessage()}");
                Log::error('Auto-renewal failed', [
                    'subscription_id' => $subscription->id,
                    'user_id' => $subscription->user_id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->info("Auto-renewal process completed. Renewed: {$renewedCount}, Failed: {$failedCount}");

        return Command::SUCCESS;
    }

    /**
     * Process auto-renewal for a single subscription
     */
    private function processAutoRenewal(Payment $subscription)
    {
        $user = $subscription->user;

        // Calculate new subscription end date
        $newEndDate = $this->calculateNewEndDate($subscription);

        // Create new payment record for the renewal
        $renewalPayment = Payment::create([
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_name' => $user->name,
            'razorpay_payment_id' => 'auto_renew_' . time() . '_' . $subscription->id,
            'razorpay_order_id' => 'auto_renew_order_' . time() . '_' . $subscription->id,
            'razorpay_signature' => 'auto_renew_signature_' . time() . '_' . $subscription->id,
            'plan_name' => $subscription->plan_name,
            'plan_type' => $subscription->plan_type,
            'amount' => $subscription->amount,
            'currency' => $subscription->currency,
            'status' => 'completed',
            'payment_date' => now(),
            'receipt_id' => 'auto_renew_' . time(),
            'razorpay_response' => [
                'auto_renewal' => true,
                'original_subscription_id' => $subscription->id,
                'timestamp' => now()->toISOString()
            ],
            'ip_address' => '127.0.0.1', // Auto-renewal from server
            'user_agent' => 'Auto-Renewal System',
            'subscription_start' => $subscription->subscription_end,
            'subscription_end' => $newEndDate,
            'is_active' => true,
            'auto_renew' => true, // Keep auto-renew enabled
        ]);

        // Deactivate the old subscription
        $subscription->update([
            'is_active' => false,
            // Keep original subscription_end unchanged
        ]);

        Log::info('Auto-renewal processed successfully', [
            'original_subscription_id' => $subscription->id,
            'new_subscription_id' => $renewalPayment->id,
            'user_id' => $user->id,
            'plan' => $subscription->plan_name,
            'new_end_date' => $newEndDate
        ]);

        // TODO: Send notification to user about successful auto-renewal
        // This could be an email, in-app notification, or SMS
        // For now, we'll just log it
        Log::info('Auto-renewal notification should be sent to user', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'plan' => $subscription->plan_name,
            'new_end_date' => $newEndDate
        ]);
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
