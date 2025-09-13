<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;

class PaymentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up test environment variables
        config([
            'app.env' => 'testing',
            'razorpay.key_id' => 'rzp_test_test_key',
            'razorpay.key_secret' => 'test_secret',
        ]);
    }

    /**
     * Test Razorpay order creation
     */
    public function test_can_create_razorpay_order()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $orderData = [
            'amount' => 5000, // ₹50 in paise
            'currency' => 'INR',
            'plan' => 'Standard',
        ];

        $response = $this->post('/api/create-razorpay-order', $orderData);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'order_id',
            'amount',
            'currency',
            'key',
        ]);
    }

    /**
     * Test payment success handling
     */
    public function test_can_handle_payment_success()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $paymentData = [
            'razorpay_payment_id' => 'pay_test123',
            'razorpay_order_id' => 'order_test123',
            'razorpay_signature' => 'signature_test123',
            'plan' => 'Standard',
        ];

        $response = $this->post('/api/payment-success', $paymentData);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'payment_id',
            'is_guest',
            'redirect_url',
        ]);

        // Verify payment record was created
        $this->assertDatabaseHas('payments', [
            'user_id' => $user->id,
            'razorpay_payment_id' => 'pay_test123',
            'plan_name' => 'Standard',
            'status' => 'completed',
        ]);
    }

    /**
     * Test subscription creation
     */
    public function test_can_create_razorpay_subscription()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $subscriptionData = [
            'plan' => 'Standard',
            'amount' => 5000,
            'currency' => 'INR',
        ];

        $response = $this->post('/api/create-subscription', $subscriptionData);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'subscription_id',
            'plan_id',
            'amount',
            'currency',
            'key',
            'customer_id',
        ]);
    }

    /**
     * Test user payments retrieval
     */
    public function test_can_get_user_payments()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create test payments
        Payment::factory()->count(3)->create([
            'user_id' => $user->id,
            'status' => 'completed',
        ]);

        $response = $this->get('/api/user/payments');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'payments' => [
                '*' => [
                    'id',
                    'plan_name',
                    'amount',
                    'status',
                    'payment_date',
                ]
            ]
        ]);
    }

    /**
     * Test active subscription retrieval
     */
    public function test_can_get_active_subscription()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create an active subscription
        Payment::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'is_active' => true,
            'subscription_end' => Carbon::now()->addDays(7),
        ]);

        $response = $this->get('/api/user/active-subscription');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'has_active_subscription',
            'subscription',
        ]);
    }

    /**
     * Test subscription deactivation
     */
    public function test_can_deactivate_subscription()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create an active subscription
        $subscription = Payment::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'is_active' => true,
            'subscription_end' => Carbon::now()->addDays(7),
        ]);

        $response = $this->post('/subscription/deactivate');

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify subscription was deactivated
        $this->assertDatabaseHas('payments', [
            'id' => $subscription->id,
            'is_active' => false,
        ]);
    }

    /**
     * Test auto-renewal toggle
     */
    public function test_can_toggle_auto_renewal()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create an active subscription
        $subscription = Payment::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'is_active' => true,
            'auto_renew' => true,
            'subscription_end' => Carbon::now()->addDays(7),
        ]);

        // Toggle auto-renewal off
        $response = $this->post('/subscription/auto-renew', [
            'auto_renew' => false,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify auto-renewal was toggled
        $this->assertDatabaseHas('payments', [
            'id' => $subscription->id,
            'auto_renew' => false,
        ]);
    }

    /**
     * Test payment signature verification
     */
    public function test_payment_signature_verification()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Test with invalid signature
        $paymentData = [
            'razorpay_payment_id' => 'pay_test123',
            'razorpay_order_id' => 'order_test123',
            'razorpay_signature' => 'invalid_signature',
            'plan' => 'Standard',
        ];

        $response = $this->post('/api/payment-success', $paymentData);

        $response->assertStatus(400);
        $response->assertJson([
            'error' => 'Invalid signature'
        ]);
    }

    /**
     * Test guest payment flow
     */
    public function test_guest_can_make_payment()
    {
        $orderData = [
            'amount' => 5000,
            'currency' => 'INR',
            'plan' => 'Standard',
        ];

        $response = $this->post('/api/create-razorpay-order', $orderData);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'order_id',
            'amount',
            'currency',
            'key',
        ]);
    }

    /**
     * Test plan details retrieval
     */
    public function test_plan_details_are_correct()
    {
        $plans = [
            'Standard' => [
                'amount' => 50.00,
                'duration_days' => 7,
                'subscription_id' => 'sub_RGmRftCSxQxz1X',
            ],
            'Pro Monthly' => [
                'amount' => 100.00,
                'duration_days' => 30,
                'subscription_id' => 'sub_RGmS1mvLpdkjej',
            ],
            'Premier Yearly' => [
                'amount' => 1100.00,
                'duration_days' => 365,
                'subscription_id' => 'sub_RGmSOxU11fg088',
            ],
        ];

        foreach ($plans as $planName => $expectedDetails) {
            $user = User::factory()->create();
            $this->actingAs($user);

            $response = $this->post('/api/create-razorpay-order', [
                'amount' => $expectedDetails['amount'] * 100, // Convert to paise
                'currency' => 'INR',
                'plan' => $planName,
            ]);

            $response->assertStatus(200);
        }
    }

    /**
     * Test subscription expiry calculation
     */
    public function test_subscription_end_date_calculation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $plans = [
            'Standard' => 7,
            'Pro Monthly' => 30,
            'Premier Yearly' => 365,
        ];

        foreach ($plans as $planName => $expectedDays) {
            $paymentData = [
                'razorpay_payment_id' => 'pay_test_' . $planName,
                'razorpay_order_id' => 'order_test_' . $planName,
                'razorpay_signature' => 'signature_test_' . $planName,
                'plan' => $planName,
            ];

            $response = $this->post('/api/payment-success', $paymentData);
            $response->assertStatus(200);

            // Verify subscription end date
            $payment = Payment::where('plan_name', $planName)->first();
            $expectedEndDate = Carbon::now()->addDays($expectedDays);

            $this->assertTrue(
                $payment->subscription_end->diffInDays($expectedEndDate) <= 1,
                "Subscription end date for {$planName} is not correct"
            );
        }
    }
}
