<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    public function createRazorpayOrder(Request $request)
    {
        try {
            Log::info('Creating Razorpay order', [
                'all_data' => $request->all(),
                'headers' => $request->headers->all(),
                'method' => $request->method(),
                'content_type' => $request->header('Content-Type')
            ]);

            $request->validate([
                'amount' => 'required|integer|min:1',
                'currency' => 'required|string',
                'plan' => 'required|string'
            ]);

            // Razorpay configuration
            $razorpayKeyId = env('RAZORPAY_KEY_ID', 'rzp_test_RFSBxMsJNmTtzD');
            $razorpayKeySecret = env('RAZORPAY_KEY_SECRET', 'Aa4e6N4t1w9LM3HxFM4v2dHI');

            Log::info('Razorpay config check', [
                'key_id_exists' => !empty($razorpayKeyId),
                'key_secret_exists' => !empty($razorpayKeySecret)
            ]);

            if (!$razorpayKeyId || !$razorpayKeySecret) {
                Log::error('Razorpay configuration missing');
                return response()->json(['error' => 'Razorpay configuration missing. Please check your .env file.'], 500);
            }

            // Create order data
            $userId = Auth::check() ? Auth::id() : null;
            $isGuest = !$userId;

            $orderData = [
                'amount' => $request->amount, // Amount in paise
                'currency' => $request->currency,
                'receipt' => 'receipt_' . time() . '_' . $request->plan . ($isGuest ? '_guest' : ''),
                'notes' => [
                    'plan' => $request->plan,
                    'user_id' => $userId ?? 'guest_' . uniqid(),
                    'is_guest' => $isGuest,
                    'created_at' => now()->toISOString(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]
            ];

            // Make API call to Razorpay
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($razorpayKeyId . ':' . $razorpayKeySecret)
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                Log::error('Razorpay order creation failed', [
                    'response' => $response,
                    'http_code' => $httpCode
                ]);
                return response()->json(['error' => 'Failed to create order'], 500);
            }

            $order = json_decode($response, true);

            return response()->json([
                'id' => $order['id'],
                'amount' => $order['amount'],
                'currency' => $order['currency'],
                'receipt' => $order['receipt']
            ]);

        } catch (\Exception $e) {
            Log::error('Payment order creation error', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Create Razorpay subscription for recurring payments
     */
    public function createRazorpaySubscription(Request $request)
    {
        try {
            $request->validate([
                'plan' => 'required|string',
                'amount' => 'required|numeric',
                'currency' => 'required|string'
            ]);

            // Get plan details
            $planDetails = $this->getPlanDetails($request->plan);

            if (!$planDetails['subscription_id']) {
                return response()->json(['error' => 'Subscription plan not found'], 400);
            }

            // Initialize Razorpay API
            $api = new Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));

            $userId = Auth::check() ? Auth::id() : null;
            $user = Auth::user();

            // Get or create customer
            $customerId = $this->getOrCreateCustomerId($api, $user);

            // Create subscription data
            $subscriptionData = [
                'plan_id' => $planDetails['subscription_id'],
                'customer_id' => $customerId,
                'total_count' => 1, // One-time payment for now
                'quantity' => 1,
                'start_at' => now()->timestamp,
                'expire_by' => now()->addDays(7)->timestamp, // 7 days buffer
                'notify_info' => [
                    'notify_phone' => $user->phone ?? '',
                    'notify_email' => $user->email
                ]
            ];

            // Create subscription in Razorpay
            $subscription = $api->subscription->create($subscriptionData);

            Log::info('Razorpay subscription created', [
                'subscription_id' => $subscription->id,
                'user_id' => $userId,
                'plan' => $request->plan,
                'amount' => $request->amount
            ]);

            return response()->json([
                'success' => true,
                'subscription_id' => $subscription->id,
                'plan_id' => $planDetails['subscription_id'],
                'amount' => $request->amount,
                'currency' => $request->currency,
                'key' => env('RAZORPAY_KEY_ID'),
                'customer_id' => $customerId
            ]);

        } catch (\Exception $e) {
            Log::error('Razorpay subscription creation failed', [
                'error' => $e->getMessage(),
                'plan' => $request->plan ?? 'unknown',
                'user_id' => Auth::id()
            ]);

            return response()->json(['error' => 'Failed to create subscription'], 500);
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

    public function handlePaymentSuccess(Request $request)
    {
        try {
            $request->validate([
                'razorpay_payment_id' => 'required|string',
                'razorpay_order_id' => 'required|string',
                'razorpay_signature' => 'required|string',
                'plan' => 'required|string'
            ]);

            // Verify payment signature
            $razorpayKeySecret = env('RAZORPAY_KEY_SECRET', 'Aa4e6N4t1w9LM3HxFM4v2dHI'); // Same key used in order creation
            $expectedSignature = hash_hmac('sha256',
                $request->razorpay_order_id . '|' . $request->razorpay_payment_id,
                $razorpayKeySecret
            );

            Log::info('Payment signature verification', [
                'order_id' => $request->razorpay_order_id,
                'payment_id' => $request->razorpay_payment_id,
                'received_signature' => $request->razorpay_signature,
                'expected_signature' => $expectedSignature,
                'signature_match' => $expectedSignature === $request->razorpay_signature
            ]);

            if ($expectedSignature !== $request->razorpay_signature) {
                Log::error('Payment signature verification failed', [
                    'order_id' => $request->razorpay_order_id,
                    'payment_id' => $request->razorpay_payment_id,
                    'received_signature' => $request->razorpay_signature,
                    'expected_signature' => $expectedSignature
                ]);
                return response()->json(['error' => 'Invalid signature'], 400);
            }

            // Store payment data in database
            $userId = Auth::check() ? Auth::id() : null;
            $isGuest = !$userId;

            // Get user details if authenticated
            $user = Auth::user();
            $userEmail = $user ? $user->email : null;
            $userName = $user ? $user->name : null;

            // Calculate plan details
            $planDetails = $this->getPlanDetails($request->plan);

            // Create payment record
            $payment = Payment::create([
                'user_id' => $userId,
                'user_email' => $userEmail,
                'user_name' => $userName,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_signature' => $request->razorpay_signature,
                'razorpay_subscription_id' => $planDetails['subscription_id'],
                'plan_name' => $request->plan,
                'plan_type' => $planDetails['type'],
                'amount' => $planDetails['amount'],
                'currency' => 'INR',
                'status' => 'completed',
                'payment_date' => now(),
                'receipt_id' => 'receipt_' . time() . '_' . $request->plan,
                'razorpay_response' => [
                    'payment_id' => $request->razorpay_payment_id,
                    'order_id' => $request->razorpay_order_id,
                    'signature' => $request->razorpay_signature,
                    'subscription_id' => $planDetails['subscription_id'],
                    'plan' => $request->plan,
                    'timestamp' => now()->toISOString()
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'subscription_start' => now(),
                'subscription_end' => $this->calculateSubscriptionEnd($planDetails['type']),
                'is_active' => true,
                'auto_renew' => true,
            ]);

            // Assign credits to user if authenticated
            if ($user) {
                $creditsToAdd = \App\Models\User::getCreditsForPlan($request->plan);
                if ($creditsToAdd > 0) {
                    $user->addCredits($creditsToAdd, "Plan purchase: {$request->plan}");

                    Log::info('Credits assigned to user', [
                        'user_id' => $userId,
                        'plan' => $request->plan,
                        'credits_added' => $creditsToAdd,
                        'new_balance' => $user->fresh()->credits
                    ]);
                }
            }

            Log::info('Payment successful and stored in database', [
                'payment_id' => $request->razorpay_payment_id,
                'order_id' => $request->razorpay_order_id,
                'plan' => $request->plan,
                'user_id' => $userId,
                'is_guest' => $isGuest,
                'payment_record_id' => $payment->id,
                'subscription_end' => $payment->subscription_end,
                'credits_assigned' => $user ? \App\Models\User::getCreditsForPlan($request->plan) : 0,
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment successful',
                'payment_id' => $request->razorpay_payment_id,
                'is_guest' => $isGuest,
                'redirect_url' => $isGuest ? route('register') : route('video-generator'),
                'subscription_end' => $payment->subscription_end->toISOString()
            ]);

        } catch (\Exception $e) {
            Log::error('Payment success handling error', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Get plan details based on plan name.
     */
    private function getPlanDetails(string $planName): array
    {
        return match ($planName) {
            'Standard' => [
                'type' => 'Standard',
                'amount' => 99.00,
                'duration_days' => 7,
                'subscription_id' => 'plan_RLkdekMmp7MPNq'
            ],
            'Pro Monthly' => [
                'type' => 'Pro Monthly',
                'amount' => 299.00,
                'duration_days' => 30,
                'subscription_id' => 'plan_RLkeK02p6JmwOU'
            ],
            'Premier Yearly' => [
                'type' => 'Premier Yearly',
                'amount' => 3999.00,
                'duration_days' => 365,
                'subscription_id' => 'plan_RLkeYGnOcW1fsD'
            ],
            default => [
                'type' => 'Unknown',
                'amount' => 0.00,
                'duration_days' => 0,
                'subscription_id' => null
            ]
        };
    }

    /**
     * Calculate subscription end date based on plan type.
     */
    private function calculateSubscriptionEnd(string $planType): Carbon
    {
        $startDate = now();

        return match ($planType) {
            'Standard' => $startDate->addDays(7),
            'Pro Monthly' => $startDate->addDays(30),
            'Premier Yearly' => $startDate->addDays(365),
            default => $startDate->addDay()
        };
    }

    /**
     * Get user's active payments.
     */
    public function getUserPayments(Request $request)
    {
        try {
            $userId = Auth::id();
            if (!$userId) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $payments = Payment::forUser($userId)
                ->completed()
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'payments' => $payments
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching user payments', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Get user's active subscription.
     */
    public function getActiveSubscription(Request $request)
    {
        try {
            $userId = Auth::id();
            if (!$userId) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $activePayment = Payment::forUser($userId)
                ->active()
                ->where('subscription_end', '>', now())
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$activePayment) {
                return response()->json([
                    'success' => true,
                    'has_active_subscription' => false,
                    'message' => 'No active subscription found'
                ]);
            }

            return response()->json([
                'success' => true,
                'has_active_subscription' => true,
                'subscription' => $activePayment
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching active subscription', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
