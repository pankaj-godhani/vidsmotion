<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;

class WebhookTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up test environment variables
        config([
            'app.env' => 'testing',
            'razorpay.webhook_secret' => 'test_webhook_secret',
        ]);
    }

    /**
     * Test webhook signature verification
     */
    public function test_webhook_signature_verification()
    {
        $webhookBody = json_encode([
            'event' => 'subscription.charged',
            'payload' => [
                'subscription' => ['id' => 'sub_test123'],
                'payment' => ['id' => 'pay_test123']
            ]
        ]);

        $signature = hash_hmac('sha256', $webhookBody, 'test_webhook_secret');

        $response = $this->post('/razorpay/webhook', [], [
            'X-Razorpay-Signature' => $signature,
            'Content-Type' => 'application/json',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
    }

    /**
     * Test webhook with invalid signature
     */
    public function test_webhook_rejects_invalid_signature()
    {
        $webhookBody = json_encode([
            'event' => 'subscription.charged',
            'payload' => []
        ]);

        $response = $this->post('/razorpay/webhook', [], [
            'X-Razorpay-Signature' => 'invalid_signature',
            'Content-Type' => 'application/json',
        ]);

        $response->assertStatus(400);
        $response->assertJson(['error' => 'Invalid signature']);
    }

    /**
     * Test subscription charged webhook
     */
    public function test_subscription_charged_webhook()
    {
        $user = User::factory()->create();

        // Create a pending payment record
        $payment = Payment::factory()->create([
            'user_id' => $user->id,
            'razorpay_subscription_id' => 'sub_test123',
            'status' => 'pending',
            'is_active' => false,
        ]);

        $webhookData = [
            'event' => 'subscription.charged',
            'payload' => [
                'subscription' => [
                    'id' => 'sub_test123',
                    'status' => 'active',
                ],
                'payment' => [
                    'id' => 'pay_test123',
                    'amount' => 5000,
                    'currency' => 'INR',
                ]
            ]
        ];

        $webhookBody = json_encode($webhookData);
        $signature = hash_hmac('sha256', $webhookBody, 'test_webhook_secret');

        $response = $this->post('/razorpay/webhook', $webhookData, [
            'X-Razorpay-Signature' => $signature,
            'Content-Type' => 'application/json',
        ]);

        $response->assertStatus(200);

        // Verify payment was updated
        $payment->refresh();
        $this->assertEquals('completed', $payment->status);
        $this->assertEquals('pay_test123', $payment->razorpay_payment_id);
        $this->assertTrue($payment->is_active);
    }

    /**
     * Test subscription completed webhook
     */
    public function test_subscription_completed_webhook()
    {
        $user = User::factory()->create();

        // Create an active payment record
        $payment = Payment::factory()->create([
            'user_id' => $user->id,
            'razorpay_subscription_id' => 'sub_test123',
            'status' => 'completed',
            'is_active' => true,
        ]);

        $webhookData = [
            'event' => 'subscription.completed',
            'payload' => [
                'subscription' => [
                    'id' => 'sub_test123',
                    'status' => 'completed',
                ]
            ]
        ];

        $webhookBody = json_encode($webhookData);
        $signature = hash_hmac('sha256', $webhookBody, 'test_webhook_secret');

        $response = $this->post('/razorpay/webhook', $webhookData, [
            'X-Razorpay-Signature' => $signature,
            'Content-Type' => 'application/json',
        ]);

        $response->assertStatus(200);

        // Verify payment was updated with completion info
        $payment->refresh();
        $this->assertArrayHasKey('subscription_completed_at', $payment->razorpay_response);
    }

    /**
     * Test subscription halted webhook
     */
    public function test_subscription_halted_webhook()
    {
        $user = User::factory()->create();

        // Create an active payment record
        $payment = Payment::factory()->create([
            'user_id' => $user->id,
            'razorpay_subscription_id' => 'sub_test123',
            'status' => 'completed',
            'is_active' => true,
            'auto_renew' => true,
        ]);

        $webhookData = [
            'event' => 'subscription.halted',
            'payload' => [
                'subscription' => [
                    'id' => 'sub_test123',
                    'status' => 'paused',
                ]
            ]
        ];

        $webhookBody = json_encode($webhookData);
        $signature = hash_hmac('sha256', $webhookBody, 'test_webhook_secret');

        $response = $this->post('/razorpay/webhook', $webhookData, [
            'X-Razorpay-Signature' => $signature,
            'Content-Type' => 'application/json',
        ]);

        $response->assertStatus(200);

        // Verify payment was deactivated
        $payment->refresh();
        $this->assertFalse($payment->is_active);
        $this->assertFalse($payment->auto_renew);
        $this->assertArrayHasKey('subscription_halted_at', $payment->razorpay_response);
    }

    /**
     * Test subscription cancelled webhook
     */
    public function test_subscription_cancelled_webhook()
    {
        $user = User::factory()->create();

        // Create an active payment record
        $payment = Payment::factory()->create([
            'user_id' => $user->id,
            'razorpay_subscription_id' => 'sub_test123',
            'status' => 'completed',
            'is_active' => true,
            'auto_renew' => true,
        ]);

        $webhookData = [
            'event' => 'subscription.cancelled',
            'payload' => [
                'subscription' => [
                    'id' => 'sub_test123',
                    'status' => 'cancelled',
                ]
            ]
        ];

        $webhookBody = json_encode($webhookData);
        $signature = hash_hmac('sha256', $webhookBody, 'test_webhook_secret');

        $response = $this->post('/razorpay/webhook', $webhookData, [
            'X-Razorpay-Signature' => $signature,
            'Content-Type' => 'application/json',
        ]);

        $response->assertStatus(200);

        // Verify payment was deactivated
        $payment->refresh();
        $this->assertFalse($payment->is_active);
        $this->assertFalse($payment->auto_renew);
        $this->assertArrayHasKey('subscription_cancelled_at', $payment->razorpay_response);
    }

    /**
     * Test payment captured webhook
     */
    public function test_payment_captured_webhook()
    {
        $user = User::factory()->create();

        // Create a payment record
        $payment = Payment::factory()->create([
            'user_id' => $user->id,
            'razorpay_payment_id' => 'pay_test123',
            'status' => 'pending',
            'is_active' => false,
        ]);

        $webhookData = [
            'event' => 'payment.captured',
            'payload' => [
                'payment' => [
                    'id' => 'pay_test123',
                    'amount' => 5000,
                    'currency' => 'INR',
                ]
            ]
        ];

        $webhookBody = json_encode($webhookData);
        $signature = hash_hmac('sha256', $webhookBody, 'test_webhook_secret');

        $response = $this->post('/razorpay/webhook', $webhookData, [
            'X-Razorpay-Signature' => $signature,
            'Content-Type' => 'application/json',
        ]);

        $response->assertStatus(200);

        // Verify payment was updated
        $payment->refresh();
        $this->assertEquals('completed', $payment->status);
        $this->assertTrue($payment->is_active);
        $this->assertArrayHasKey('payment_captured_at', $payment->razorpay_response);
    }

    /**
     * Test payment failed webhook
     */
    public function test_payment_failed_webhook()
    {
        $user = User::factory()->create();

        // Create a payment record
        $payment = Payment::factory()->create([
            'user_id' => $user->id,
            'razorpay_payment_id' => 'pay_test123',
            'status' => 'pending',
        ]);

        $webhookData = [
            'event' => 'payment.failed',
            'payload' => [
                'payment' => [
                    'id' => 'pay_test123',
                    'amount' => 5000,
                    'currency' => 'INR',
                ]
            ]
        ];

        $webhookBody = json_encode($webhookData);
        $signature = hash_hmac('sha256', $webhookBody, 'test_webhook_secret');

        $response = $this->post('/razorpay/webhook', $webhookData, [
            'X-Razorpay-Signature' => $signature,
            'Content-Type' => 'application/json',
        ]);

        $response->assertStatus(200);

        // Verify payment was marked as failed
        $payment->refresh();
        $this->assertEquals('failed', $payment->status);
        $this->assertArrayHasKey('payment_failed_at', $payment->razorpay_response);
    }

    /**
     * Test webhook with missing payment record
     */
    public function test_webhook_with_missing_payment_record()
    {
        $webhookData = [
            'event' => 'subscription.charged',
            'payload' => [
                'subscription' => [
                    'id' => 'sub_nonexistent',
                    'status' => 'active',
                ],
                'payment' => [
                    'id' => 'pay_nonexistent',
                    'amount' => 5000,
                    'currency' => 'INR',
                ]
            ]
        ];

        $webhookBody = json_encode($webhookData);
        $signature = hash_hmac('sha256', $webhookBody, 'test_webhook_secret');

        $response = $this->post('/razorpay/webhook', $webhookData, [
            'X-Razorpay-Signature' => $signature,
            'Content-Type' => 'application/json',
        ]);

        $response->assertStatus(200);
        // Should not throw error, just log and continue
    }

    /**
     * Test unhandled webhook events
     */
    public function test_unhandled_webhook_events()
    {
        $webhookData = [
            'event' => 'subscription.unknown_event',
            'payload' => []
        ];

        $webhookBody = json_encode($webhookData);
        $signature = hash_hmac('sha256', $webhookBody, 'test_webhook_secret');

        $response = $this->post('/razorpay/webhook', $webhookData, [
            'X-Razorpay-Signature' => $signature,
            'Content-Type' => 'application/json',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
    }

    /**
     * Test webhook error handling
     */
    public function test_webhook_error_handling()
    {
        // Test with malformed JSON
        $response = $this->post('/razorpay/webhook', 'invalid json', [
            'X-Razorpay-Signature' => 'test_signature',
            'Content-Type' => 'application/json',
        ]);

        $response->assertStatus(500);
        $response->assertJson(['error' => 'Internal server error']);
    }

    /**
     * Test webhook without signature header
     */
    public function test_webhook_without_signature_header()
    {
        $webhookData = [
            'event' => 'subscription.charged',
            'payload' => []
        ];

        $response = $this->post('/razorpay/webhook', $webhookData, [
            'Content-Type' => 'application/json',
        ]);

        $response->assertStatus(400);
        $response->assertJson(['error' => 'Invalid signature']);
    }
}
