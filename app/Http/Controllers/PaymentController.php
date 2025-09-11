<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function createRazorpayOrder(Request $request)
    {
        try {
            Log::info('Creating Razorpay order', $request->all());

            $request->validate([
                'amount' => 'required|integer|min:1',
                'currency' => 'required|string',
                'plan' => 'required|string'
            ]);

            // Razorpay configuration
            $razorpayKeyId = 'rzp_test_RFSBxMsJNmTtzD';
            $razorpayKeySecret = 'Aa4e6N4t1w9LM3HxFM4v2dHI';

            Log::info('Razorpay config check', [
                'key_id_exists' => !empty($razorpayKeyId),
                'key_secret_exists' => !empty($razorpayKeySecret)
            ]);

            if (!$razorpayKeyId || !$razorpayKeySecret) {
                Log::error('Razorpay configuration missing');
                return response()->json(['error' => 'Razorpay configuration missing. Please check your .env file.'], 500);
            }

            // Create order data
            $orderData = [
                'amount' => $request->amount, // Amount in paise
                'currency' => $request->currency,
                'receipt' => 'receipt_' . time() . '_' . $request->plan,
                'notes' => [
                    'plan' => $request->plan,
                    'user_id' => auth()->id() ?? 'guest',
                    'created_at' => now()->toISOString()
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
            $razorpayKeySecret = env('RAZORPAY_KEY_SECRET');
            $expectedSignature = hash_hmac('sha256',
                $request->razorpay_order_id . '|' . $request->razorpay_payment_id,
                $razorpayKeySecret
            );

            if ($expectedSignature !== $request->razorpay_signature) {
                return response()->json(['error' => 'Invalid signature'], 400);
            }

            // Here you would typically:
            // 1. Update user's subscription status in database
            // 2. Send confirmation email
            // 3. Log the transaction
            // 4. Activate the plan features

            Log::info('Payment successful', [
                'payment_id' => $request->razorpay_payment_id,
                'order_id' => $request->razorpay_order_id,
                'plan' => $request->plan,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment successful',
                'payment_id' => $request->razorpay_payment_id
            ]);

        } catch (\Exception $e) {
            Log::error('Payment success handling error', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
