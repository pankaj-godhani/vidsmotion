<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use Carbon\Carbon;

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
                    'plan' => $request->plan,
                    'timestamp' => now()->toISOString()
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'subscription_start' => now(),
                'subscription_end' => $this->calculateSubscriptionEnd($planDetails['type']),
                'is_active' => true,
            ]);

            Log::info('Payment successful and stored in database', [
                'payment_id' => $request->razorpay_payment_id,
                'order_id' => $request->razorpay_order_id,
                'plan' => $request->plan,
                'user_id' => $userId,
                'is_guest' => $isGuest,
                'payment_record_id' => $payment->id,
                'subscription_end' => $payment->subscription_end,
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
                'amount' => 50.00,
                'duration_days' => 3
            ],
            'Pro Monthly' => [
                'type' => 'Pro Monthly',
                'amount' => 100.00,
                'duration_days' => 30
            ],
            'Premier Yearly' => [
                'type' => 'Premier Yearly',
                'amount' => 1100.00,
                'duration_days' => 365
            ],
            default => [
                'type' => 'Unknown',
                'amount' => 0.00,
                'duration_days' => 0
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
            'Standard' => $startDate->addDays(3),
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
