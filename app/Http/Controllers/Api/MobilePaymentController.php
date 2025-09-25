<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Razorpay\Api\Api;

class MobilePaymentController extends Controller
{
    /**
     * Create Razorpay order for mobile app
     */
    public function createOrder(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'amount' => 'required|integer|min:1',
                'currency' => 'required|string|in:INR',
                'plan' => 'required|string|in:Standard,Pro Monthly,Premier Yearly',
            ]);
            //dd('df');
            // Get authenticated user
            $user = $request->user();
            if (!$user) {
                return response()->json(['error' => 'Authentication required'], 401);
            }

            // Get plan details
            $planDetails = $this->getPlanDetails($request->plan);
            
            // Razorpay configuration
            $razorpayKeyId = env('RAZORPAY_KEY_ID','rzp_test_RFSBxMsJNmTtzD');
            $razorpayKeySecret = env('RAZORPAY_KEY_SECRET','Aa4e6N4t1w9LM3HxFM4v2dHI');

            if (!$razorpayKeyId || !$razorpayKeySecret) {
                return response()->json(['error' => 'Payment configuration missing'], 500);
            }

            // Create order data
            $orderData = [
                'amount' => $request->amount, // Amount in paise
                'currency' => $request->currency,
                'receipt' => 'mobile_' . time() . '_' . $request->plan . '_' . $user->id,
                'notes' => [
                    'plan' => $request->plan,
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'platform' => 'mobile',
                    'created_at' => now()->toISOString(),
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
                Log::error('Mobile Razorpay order creation failed', [
                    'response' => $response,
                    'http_code' => $httpCode,
                    'user_id' => $user->id
                ]);
                return response()->json(['error' => 'Failed to create order'], 500);
            }

            $order = json_decode($response, true);

            Log::info('Mobile Razorpay order created', [
                'order_id' => $order['id'],
                'user_id' => $user->id,
                'plan' => $request->plan,
                'amount' => $request->amount
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'order_id' => $order['id'],
                    'amount' => $order['amount'],
                    'currency' => $order['currency'],
                    'receipt' => $order['receipt'],
                    'key_id' => $razorpayKeyId,
                    'plan' => $request->plan,
                    'plan_details' => $planDetails
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Mobile payment order creation error', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
                'request' => $request->all()
            ]);

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Verify and process payment for mobile app
     */
    public function verifyPayment(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'razorpay_payment_id' => 'required|string',
                'razorpay_order_id' => 'required|string',
                'razorpay_signature' => 'required|string',
                'plan' => 'required|string|in:Standard,Pro Monthly,Premier Yearly',
            ]);

            // Get authenticated user
            $user = $request->user();
            if (!$user) {
                return response()->json(['error' => 'Authentication required'], 401);
            }

            // Verify payment signature
            $razorpayKeySecret = env('RAZORPAY_KEY_SECRET');
            $expectedSignature = hash_hmac('sha256',
                $request->razorpay_order_id . '|' . $request->razorpay_payment_id,
                $razorpayKeySecret
            );

            if ($expectedSignature !== $request->razorpay_signature) {
                Log::error('Mobile payment signature verification failed', [
                    'order_id' => $request->razorpay_order_id,
                    'payment_id' => $request->razorpay_payment_id,
                    'user_id' => $user->id
                ]);
                return response()->json(['error' => 'Invalid payment signature'], 400);
            }

            // Get plan details
            $planDetails = $this->getPlanDetails($request->plan);

            // Create payment record
            $payment = Payment::create([
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_name' => $user->name,
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
                'receipt_id' => 'mobile_' . time() . '_' . $request->plan,
                'razorpay_response' => [
                    'payment_id' => $request->razorpay_payment_id,
                    'order_id' => $request->razorpay_order_id,
                    'signature' => $request->razorpay_signature,
                    'subscription_id' => $planDetails['subscription_id'],
                    'plan' => $request->plan,
                    'platform' => 'mobile',
                    'timestamp' => now()->toISOString()
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'subscription_start' => now(),
                'subscription_end' => $this->calculateSubscriptionEnd($planDetails['type']),
                'is_active' => true,
                'auto_renew' => true,
            ]);

            // Assign credits to user
            $creditsToAdd = User::getCreditsForPlan($request->plan);
            if ($creditsToAdd > 0) {
                $user->addCredits($creditsToAdd, "Mobile plan purchase: {$request->plan}");
            }

            Log::info('Mobile payment successful', [
                'payment_id' => $payment->id,
                'user_id' => $user->id,
                'plan' => $request->plan,
                'amount' => $planDetails['amount'],
                'credits_added' => $creditsToAdd
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment successful',
                'data' => [
                    'payment_id' => $payment->id,
                    'plan' => $request->plan,
                    'amount' => $planDetails['amount'],
                    'currency' => 'INR',
                    'subscription_start' => $payment->subscription_start,
                    'subscription_end' => $payment->subscription_end,
                    'credits_added' => $creditsToAdd,
                    'user_credits' => $user->credits
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Mobile payment verification error', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
                'request' => $request->all()
            ]);

            return response()->json(['error' => 'Payment verification failed'], 500);
        }
    }

    /**
     * Get user's payment history for mobile app
     */
    public function getPaymentHistory(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json(['error' => 'Authentication required'], 401);
            }

            $payments = Payment::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            $paymentHistory = $payments->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'plan_name' => $payment->plan_name,
                    'amount' => $payment->amount,
                    'currency' => $payment->currency,
                    'status' => $payment->status,
                    'payment_date' => $payment->payment_date,
                    'subscription_start' => $payment->subscription_start,
                    'subscription_end' => $payment->subscription_end,
                    'is_active' => $payment->is_active,
                    'receipt_id' => $payment->receipt_id,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'payments' => $paymentHistory,
                    'pagination' => [
                        'current_page' => $payments->currentPage(),
                        'per_page' => $payments->perPage(),
                        'total' => $payments->total(),
                        'last_page' => $payments->lastPage(),
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Mobile payment history error', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id
            ]);

            return response()->json(['error' => 'Failed to fetch payment history'], 500);
        }
    }

    /**
     * Get available plans for mobile app
     */
    public function getPlans(): JsonResponse
    {
        try {
            $plans = [
                [
                    'id' => 'Standard',
                    'name' => 'Standard Plan',
                    'description' => '7 days access with basic features',
                    'amount' => 99.00,
                    'currency' => 'INR',
                    'duration_days' => 7,
                    'features' => [
                        'Basic video generation',
                        'Standard quality output',
                        '7 days access',
                        'Email support'
                    ]
                ],
                [
                    'id' => 'Pro Monthly',
                    'name' => 'Pro Monthly Plan',
                    'description' => 'Monthly access with premium features',
                    'amount' => 299.00,
                    'currency' => 'INR',
                    'duration_days' => 30,
                    'features' => [
                        'Premium video generation',
                        'HD quality output',
                        '30 days access',
                        'Priority support',
                        'Advanced templates'
                    ]
                ],
                [
                    'id' => 'Premier Yearly',
                    'name' => 'Premier Yearly Plan',
                    'description' => 'Yearly access with all features',
                    'amount' => 3999.00,
                    'currency' => 'INR',
                    'duration_days' => 365,
                    'features' => [
                        'Unlimited video generation',
                        '4K quality output',
                        '365 days access',
                        '24/7 premium support',
                        'All templates',
                        'API access'
                    ]
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'plans' => $plans,
                    'razorpay_key' => env('RAZORPAY_KEY_ID')
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Mobile plans fetch error', [
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Failed to fetch plans'], 500);
        }
    }

    /**
     * Get plan details based on plan name
     */
    private function getPlanDetails(string $planName): array
    {
        return match ($planName) {
            'Standard' => [
                'type' => 'Standard',
                'amount' => 99.00,
                'duration_days' => 7,
                'subscription_id' => 'sub_RLmN8cDJVph0DQ'
            ],
            'Pro Monthly' => [
                'type' => 'Pro Monthly',
                'amount' => 299.00,
                'duration_days' => 30,
                'subscription_id' => 'sub_RLmMs3kifmc6qk'
            ],
            'Premier Yearly' => [
                'type' => 'Premier Yearly',
                'amount' => 3999.00,
                'duration_days' => 365,
                'subscription_id' => 'sub_RLmMUPcT4AxFFV'
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
     * Calculate subscription end date
     */
    private function calculateSubscriptionEnd(string $planType): Carbon
    {
        return match ($planType) {
            'Standard' => now()->addDays(7),
            'Pro Monthly' => now()->addDays(30),
            'Premier Yearly' => now()->addDays(365),
            default => now()->addDays(7)
        };
    }
}
