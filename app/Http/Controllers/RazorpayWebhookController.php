<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use App\Models\User;
use Razorpay\Api\Api;
use Carbon\Carbon;

class RazorpayWebhookController extends Controller
{
    /**
     * Handle Razorpay webhook events
     */
    public function handleWebhook(Request $request)
    {
        try {
            // Get the webhook signature
            $webhookSignature = $request->header('X-Razorpay-Signature');
            $webhookBody = $request->getContent();

            // Verify webhook signature
            $expectedSignature = hash_hmac('sha256', $webhookBody, env('RAZORPAY_WEBHOOK_SECRET', ''));

            if (!hash_equals($expectedSignature, $webhookSignature)) {
                Log::error('Invalid webhook signature', [
                    'received_signature' => $webhookSignature,
                    'expected_signature' => $expectedSignature
                ]);
                return response()->json(['error' => 'Invalid signature'], 400);
            }

            $webhookData = json_decode($webhookBody, true);
            $event = $webhookData['event'] ?? null;
            $payload = $webhookData['payload'] ?? [];

            Log::info('Razorpay webhook received', [
                'event' => $event,
                'payload' => $payload
            ]);

            switch ($event) {
                case 'subscription.charged':
                    $this->handleSubscriptionCharged($payload);
                    break;

                case 'subscription.completed':
                    $this->handleSubscriptionCompleted($payload);
                    break;

                case 'subscription.halted':
                    $this->handleSubscriptionHalted($payload);
                    break;

                case 'subscription.cancelled':
                    $this->handleSubscriptionCancelled($payload);
                    break;

                case 'payment.captured':
                    $this->handlePaymentCaptured($payload);
                    break;

                case 'payment.failed':
                    $this->handlePaymentFailed($payload);
                    break;

                default:
                    Log::info('Unhandled webhook event', ['event' => $event]);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Webhook processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_body' => $request->getContent()
            ]);

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Handle subscription charged event
     */
    private function handleSubscriptionCharged($payload)
    {
        $subscription = $payload['subscription'] ?? null;
        $payment = $payload['payment'] ?? null;

        if (!$subscription || !$payment) {
            Log::error('Invalid subscription charged payload', ['payload' => $payload]);
            return;
        }

        $razorpaySubscriptionId = $subscription['id'];
        $razorpayPaymentId = $payment['id'];

        Log::info('Processing subscription charged event', [
            'subscription_id' => $razorpaySubscriptionId,
            'payment_id' => $razorpayPaymentId,
            'amount' => $payment['amount'],
            'currency' => $payment['currency']
        ]);

        // Find the pending payment record
        $paymentRecord = Payment::where('razorpay_subscription_id', $razorpaySubscriptionId)
            ->where('status', 'pending')
            ->first();

        if (!$paymentRecord) {
            Log::error('Payment record not found for subscription charged', [
                'razorpay_subscription_id' => $razorpaySubscriptionId
            ]);
            return;
        }

        // Update payment record with payment details
        $paymentRecord->update([
            'razorpay_payment_id' => $razorpayPaymentId,
            'status' => 'completed',
            'payment_date' => now(),
            'is_active' => true,
            'razorpay_response' => array_merge($paymentRecord->razorpay_response ?? [], [
                'payment_id' => $razorpayPaymentId,
                'subscription_charged_at' => now()->toISOString(),
                'webhook_event' => 'subscription.charged'
            ])
        ]);

        // Assign credits to user for subscription renewal
        $user = \App\Models\User::find($paymentRecord->user_id);
        if ($user) {
            $creditsToAdd = \App\Models\User::getCreditsForPlan($paymentRecord->plan);
            if ($creditsToAdd > 0) {
                $user->addCredits($creditsToAdd, "Subscription renewal: {$paymentRecord->plan}");

                Log::info('Credits assigned for subscription renewal', [
                    'user_id' => $user->id,
                    'plan' => $paymentRecord->plan,
                    'credits_added' => $creditsToAdd,
                    'new_balance' => $user->fresh()->credits
                ]);
            }
        }

        Log::info('Payment record updated for subscription charged', [
            'payment_id' => $paymentRecord->id,
            'user_id' => $paymentRecord->user_id,
            'subscription_end' => $paymentRecord->subscription_end,
            'credits_assigned' => $user ? \App\Models\User::getCreditsForPlan($paymentRecord->plan) : 0
        ]);

        // TODO: Send success notification to user
    }

    /**
     * Handle subscription completed event
     */
    private function handleSubscriptionCompleted($payload)
    {
        $subscription = $payload['subscription'] ?? null;

        if (!$subscription) {
            Log::error('Invalid subscription completed payload', ['payload' => $payload]);
            return;
        }

        $razorpaySubscriptionId = $subscription['id'];

        Log::info('Processing subscription completed event', [
            'subscription_id' => $razorpaySubscriptionId,
            'status' => $subscription['status']
        ]);

        // Find the payment record
        $paymentRecord = Payment::where('razorpay_subscription_id', $razorpaySubscriptionId)
            ->where('is_active', true)
            ->first();

        if (!$paymentRecord) {
            Log::error('Active payment record not found for subscription completed', [
                'razorpay_subscription_id' => $razorpaySubscriptionId
            ]);
            return;
        }

        // Update subscription status
        $paymentRecord->update([
            'razorpay_response' => array_merge($paymentRecord->razorpay_response ?? [], [
                'subscription_completed_at' => now()->toISOString(),
                'webhook_event' => 'subscription.completed'
            ])
        ]);

        Log::info('Subscription completed event processed', [
            'payment_id' => $paymentRecord->id,
            'user_id' => $paymentRecord->user_id
        ]);
    }

    /**
     * Handle subscription halted event
     */
    private function handleSubscriptionHalted($payload)
    {
        $subscription = $payload['subscription'] ?? null;

        if (!$subscription) {
            Log::error('Invalid subscription halted payload', ['payload' => $payload]);
            return;
        }

        $razorpaySubscriptionId = $subscription['id'];

        Log::info('Processing subscription halted event', [
            'subscription_id' => $razorpaySubscriptionId,
            'status' => $subscription['status']
        ]);

        // Find the payment record
        $paymentRecord = Payment::where('razorpay_subscription_id', $razorpaySubscriptionId)
            ->where('is_active', true)
            ->first();

        if (!$paymentRecord) {
            Log::error('Active payment record not found for subscription halted', [
                'razorpay_subscription_id' => $razorpaySubscriptionId
            ]);
            return;
        }

        // Deactivate the subscription
        $paymentRecord->update([
            'is_active' => false,
            'auto_renew' => false,
            'razorpay_response' => array_merge($paymentRecord->razorpay_response ?? [], [
                'subscription_halted_at' => now()->toISOString(),
                'webhook_event' => 'subscription.halted'
            ])
        ]);

        Log::info('Subscription halted event processed', [
            'payment_id' => $paymentRecord->id,
            'user_id' => $paymentRecord->user_id
        ]);

        // TODO: Send notification to user about subscription halt
    }

    /**
     * Handle subscription cancelled event
     */
    private function handleSubscriptionCancelled($payload)
    {
        $subscription = $payload['subscription'] ?? null;

        if (!$subscription) {
            Log::error('Invalid subscription cancelled payload', ['payload' => $payload]);
            return;
        }

        $razorpaySubscriptionId = $subscription['id'];

        Log::info('Processing subscription cancelled event', [
            'subscription_id' => $razorpaySubscriptionId,
            'status' => $subscription['status']
        ]);

        // Find the payment record
        $paymentRecord = Payment::where('razorpay_subscription_id', $razorpaySubscriptionId)
            ->where('is_active', true)
            ->first();

        if (!$paymentRecord) {
            Log::error('Active payment record not found for subscription cancelled', [
                'razorpay_subscription_id' => $razorpaySubscriptionId
            ]);
            return;
        }

        // Deactivate the subscription
        $paymentRecord->update([
            'is_active' => false,
            'auto_renew' => false,
            'razorpay_response' => array_merge($paymentRecord->razorpay_response ?? [], [
                'subscription_cancelled_at' => now()->toISOString(),
                'webhook_event' => 'subscription.cancelled'
            ])
        ]);

        Log::info('Subscription cancelled event processed', [
            'payment_id' => $paymentRecord->id,
            'user_id' => $paymentRecord->user_id
        ]);

        // TODO: Send notification to user about subscription cancellation
    }

    /**
     * Handle payment captured event
     */
    private function handlePaymentCaptured($payload)
    {
        $payment = $payload['payment'] ?? null;

        if (!$payment) {
            Log::error('Invalid payment captured payload', ['payload' => $payload]);
            return;
        }

        $razorpayPaymentId = $payment['id'];

        Log::info('Processing payment captured event', [
            'payment_id' => $razorpayPaymentId,
            'amount' => $payment['amount'],
            'currency' => $payment['currency']
        ]);

        // Find the payment record
        $paymentRecord = Payment::where('razorpay_payment_id', $razorpayPaymentId)->first();

        if (!$paymentRecord) {
            Log::error('Payment record not found for payment captured', [
                'razorpay_payment_id' => $razorpayPaymentId
            ]);
            return;
        }

        // Update payment record
        $paymentRecord->update([
            'status' => 'completed',
            'payment_date' => now(),
            'is_active' => true,
            'razorpay_response' => array_merge($paymentRecord->razorpay_response ?? [], [
                'payment_captured_at' => now()->toISOString(),
                'webhook_event' => 'payment.captured'
            ])
        ]);

        Log::info('Payment captured event processed', [
            'payment_id' => $paymentRecord->id,
            'user_id' => $paymentRecord->user_id
        ]);
    }

    /**
     * Handle payment failed event
     */
    private function handlePaymentFailed($payload)
    {
        $payment = $payload['payment'] ?? null;

        if (!$payment) {
            Log::error('Invalid payment failed payload', ['payload' => $payload]);
            return;
        }

        $razorpayPaymentId = $payment['id'];

        Log::info('Processing payment failed event', [
            'payment_id' => $razorpayPaymentId,
            'amount' => $payment['amount'],
            'currency' => $payment['currency']
        ]);

        // Find the payment record
        $paymentRecord = Payment::where('razorpay_payment_id', $razorpayPaymentId)->first();

        if (!$paymentRecord) {
            Log::error('Payment record not found for payment failed', [
                'razorpay_payment_id' => $razorpayPaymentId
            ]);
            return;
        }

        // Update payment record
        $paymentRecord->update([
            'status' => 'failed',
            'razorpay_response' => array_merge($paymentRecord->razorpay_response ?? [], [
                'payment_failed_at' => now()->toISOString(),
                'webhook_event' => 'payment.failed'
            ])
        ]);

        Log::info('Payment failed event processed', [
            'payment_id' => $paymentRecord->id,
            'user_id' => $paymentRecord->user_id
        ]);

        // TODO: Send notification to user about payment failure
    }
}
