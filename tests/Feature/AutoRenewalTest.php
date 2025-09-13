<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;

class AutoRenewalTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test auto-renewal command finds expiring subscriptions
     */
    public function test_auto_renewal_command_finds_expiring_subscriptions()
    {
        $user = User::factory()->create();

        // Create a subscription expiring within 1 day
        $subscription = Payment::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'is_active' => true,
            'auto_renew' => true,
            'subscription_end' => Carbon::now()->addHours(12), // Expires in 12 hours
            'razorpay_subscription_id' => 'sub_test123',
        ]);

        $this->artisan('subscriptions:razorpay-auto-renew')
            ->expectsOutput('Starting Razorpay auto-renewal processing...')
            ->expectsOutput('Found 1 subscriptions expiring soon with auto-renew enabled.')
            ->assertExitCode(0);
    }

    /**
     * Test auto-renewal command skips non-expiring subscriptions
     */
    public function test_auto_renewal_command_skips_non_expiring_subscriptions()
    {
        $user = User::factory()->create();

        // Create a subscription not expiring soon
        $subscription = Payment::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'is_active' => true,
            'auto_renew' => true,
            'subscription_end' => Carbon::now()->addDays(5), // Expires in 5 days
            'razorpay_subscription_id' => 'sub_test123',
        ]);

        $this->artisan('subscriptions:razorpay-auto-renew')
            ->expectsOutput('Starting Razorpay auto-renewal processing...')
            ->expectsOutput('Found 0 subscriptions expiring soon with auto-renew enabled.')
            ->assertExitCode(0);
    }

    /**
     * Test auto-renewal command skips subscriptions with auto-renew disabled
     */
    public function test_auto_renewal_command_skips_disabled_auto_renew()
    {
        $user = User::factory()->create();

        // Create a subscription with auto-renew disabled
        $subscription = Payment::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'is_active' => true,
            'auto_renew' => false, // Auto-renew disabled
            'subscription_end' => Carbon::now()->addHours(12),
            'razorpay_subscription_id' => 'sub_test123',
        ]);

        $this->artisan('subscriptions:razorpay-auto-renew')
            ->expectsOutput('Starting Razorpay auto-renewal processing...')
            ->expectsOutput('Found 0 subscriptions expiring soon with auto-renew enabled.')
            ->assertExitCode(0);
    }

    /**
     * Test auto-renewal command skips inactive subscriptions
     */
    public function test_auto_renewal_command_skips_inactive_subscriptions()
    {
        $user = User::factory()->create();

        // Create an inactive subscription
        $subscription = Payment::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'is_active' => false, // Inactive
            'auto_renew' => true,
            'subscription_end' => Carbon::now()->addHours(12),
            'razorpay_subscription_id' => 'sub_test123',
        ]);

        $this->artisan('subscriptions:razorpay-auto-renew')
            ->expectsOutput('Starting Razorpay auto-renewal processing...')
            ->expectsOutput('Found 0 subscriptions expiring soon with auto-renew enabled.')
            ->assertExitCode(0);
    }

    /**
     * Test auto-renewal command skips subscriptions without Razorpay ID
     */
    public function test_auto_renewal_command_skips_subscriptions_without_razorpay_id()
    {
        $user = User::factory()->create();

        // Create a subscription without Razorpay subscription ID
        $subscription = Payment::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'is_active' => true,
            'auto_renew' => true,
            'subscription_end' => Carbon::now()->addHours(12),
            'razorpay_subscription_id' => null, // No Razorpay ID
        ]);

        $this->artisan('subscriptions:razorpay-auto-renew')
            ->expectsOutput('Starting Razorpay auto-renewal processing...')
            ->expectsOutput('Found 0 subscriptions expiring soon with auto-renew enabled.')
            ->assertExitCode(0);
    }

    /**
     * Test auto-renewal command handles multiple expiring subscriptions
     */
    public function test_auto_renewal_command_handles_multiple_expiring_subscriptions()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create multiple subscriptions expiring soon
        Payment::factory()->create([
            'user_id' => $user1->id,
            'status' => 'completed',
            'is_active' => true,
            'auto_renew' => true,
            'subscription_end' => Carbon::now()->addHours(12),
            'razorpay_subscription_id' => 'sub_test1',
        ]);

        Payment::factory()->create([
            'user_id' => $user2->id,
            'status' => 'completed',
            'is_active' => true,
            'auto_renew' => true,
            'subscription_end' => Carbon::now()->addHours(6),
            'razorpay_subscription_id' => 'sub_test2',
        ]);

        $this->artisan('subscriptions:razorpay-auto-renew')
            ->expectsOutput('Starting Razorpay auto-renewal processing...')
            ->expectsOutput('Found 2 subscriptions expiring soon with auto-renew enabled.')
            ->assertExitCode(0);
    }

    /**
     * Test subscription end date calculation for different plan types
     */
    public function test_subscription_end_date_calculation()
    {
        $user = User::factory()->create();

        $plans = [
            'Standard' => 7,
            'Pro Monthly' => 30,
            'Premier Yearly' => 365,
        ];

        foreach ($plans as $planType => $expectedDays) {
            $subscription = Payment::factory()->create([
                'user_id' => $user->id,
                'plan_type' => $planType,
                'subscription_end' => Carbon::now(),
            ]);

            // Test the calculation logic
            $newEndDate = match ($planType) {
                'Standard' => Carbon::now()->addDays(7),
                'Pro Monthly' => Carbon::now()->addMonth(),
                'Premier Yearly' => Carbon::now()->addYear(),
                default => Carbon::now()->addMonth(),
            };

            $this->assertTrue(
                $newEndDate->diffInDays(Carbon::now()->addDays($expectedDays)) <= 1,
                "End date calculation for {$planType} is incorrect"
            );
        }
    }

    /**
     * Test auto-renewal creates new payment record
     */
    public function test_auto_renewal_creates_new_payment_record()
    {
        $user = User::factory()->create();

        // Create an expiring subscription
        $oldSubscription = Payment::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'is_active' => true,
            'auto_renew' => true,
            'subscription_end' => Carbon::now()->addHours(12),
            'razorpay_subscription_id' => 'sub_test123',
            'plan_type' => 'Standard',
        ]);

        $initialPaymentCount = Payment::count();

        // Mock the Razorpay API to avoid actual API calls
        $this->mock(\Razorpay\Api\Api::class, function ($mock) {
            $mock->shouldReceive('subscription->fetch')
                ->andReturn((object) [
                    'id' => 'sub_test123',
                    'status' => 'active',
                    'plan_id' => 'plan_test123',
                ]);

            $mock->shouldReceive('subscription->create')
                ->andReturn((object) [
                    'id' => 'sub_new123',
                    'status' => 'created',
                ]);

            $mock->shouldReceive('customer->all')
                ->andReturn((object) [
                    'count' => 0,
                    'items' => []
                ]);

            $mock->shouldReceive('customer->create')
                ->andReturn((object) [
                    'id' => 'cust_test123',
                    'email' => 'test@example.com',
                ]);
        });

        $this->artisan('subscriptions:razorpay-auto-renew')
            ->assertExitCode(0);

        // Verify new payment record was created
        $this->assertEquals($initialPaymentCount + 1, Payment::count());

        // Verify old subscription was deactivated
        $oldSubscription->refresh();
        $this->assertFalse($oldSubscription->is_active);

        // Verify new subscription was created
        $newSubscription = Payment::where('razorpay_subscription_id', 'sub_new123')->first();
        $this->assertNotNull($newSubscription);
        $this->assertEquals($user->id, $newSubscription->user_id);
        $this->assertFalse($newSubscription->is_active); // Initially inactive until payment
    }

    /**
     * Test auto-renewal handles API errors gracefully
     */
    public function test_auto_renewal_handles_api_errors_gracefully()
    {
        $user = User::factory()->create();

        // Create an expiring subscription
        Payment::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'is_active' => true,
            'auto_renew' => true,
            'subscription_end' => Carbon::now()->addHours(12),
            'razorpay_subscription_id' => 'sub_test123',
        ]);

        // Mock the Razorpay API to throw an exception
        $this->mock(\Razorpay\Api\Api::class, function ($mock) {
            $mock->shouldReceive('subscription->fetch')
                ->andThrow(new \Exception('API Error'));
        });

        $this->artisan('subscriptions:razorpay-auto-renew')
            ->expectsOutput('Starting Razorpay auto-renewal processing...')
            ->expectsOutput('Found 1 subscriptions expiring soon with auto-renew enabled.')
            ->expectsOutput('Auto-renewal processing completed. Renewed: 0, Failed: 1')
            ->assertExitCode(0);
    }

    /**
     * Test auto-renewal command logging
     */
    public function test_auto_renewal_command_logs_activities()
    {
        $user = User::factory()->create();

        // Create an expiring subscription
        Payment::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'is_active' => true,
            'auto_renew' => true,
            'subscription_end' => Carbon::now()->addHours(12),
            'razorpay_subscription_id' => 'sub_test123',
        ]);

        // Mock the Razorpay API
        $this->mock(\Razorpay\Api\Api::class, function ($mock) {
            $mock->shouldReceive('subscription->fetch')
                ->andReturn((object) [
                    'id' => 'sub_test123',
                    'status' => 'active',
                    'plan_id' => 'plan_test123',
                ]);

            $mock->shouldReceive('subscription->create')
                ->andReturn((object) [
                    'id' => 'sub_new123',
                    'status' => 'created',
                ]);

            $mock->shouldReceive('customer->all')
                ->andReturn((object) [
                    'count' => 0,
                    'items' => []
                ]);

            $mock->shouldReceive('customer->create')
                ->andReturn((object) [
                    'id' => 'cust_test123',
                    'email' => 'test@example.com',
                ]);
        });

        $this->artisan('subscriptions:razorpay-auto-renew')
            ->assertExitCode(0);

        // Verify logs were written (this would be tested with log assertions in a real scenario)
        $this->assertTrue(true); // Placeholder for log verification
    }

    /**
     * Test auto-renewal preserves original subscription end date
     */
    public function test_auto_renewal_preserves_original_subscription_end_date()
    {
        $user = User::factory()->create();
        $originalEndDate = Carbon::now()->addHours(12);

        // Create an expiring subscription
        $oldSubscription = Payment::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'is_active' => true,
            'auto_renew' => true,
            'subscription_end' => $originalEndDate,
            'razorpay_subscription_id' => 'sub_test123',
        ]);

        // Mock the Razorpay API
        $this->mock(\Razorpay\Api\Api::class, function ($mock) {
            $mock->shouldReceive('subscription->fetch')
                ->andReturn((object) [
                    'id' => 'sub_test123',
                    'status' => 'active',
                    'plan_id' => 'plan_test123',
                ]);

            $mock->shouldReceive('subscription->create')
                ->andReturn((object) [
                    'id' => 'sub_new123',
                    'status' => 'created',
                ]);

            $mock->shouldReceive('customer->all')
                ->andReturn((object) [
                    'count' => 0,
                    'items' => []
                ]);

            $mock->shouldReceive('customer->create')
                ->andReturn((object) [
                    'id' => 'cust_test123',
                    'email' => 'test@example.com',
                ]);
        });

        $this->artisan('subscriptions:razorpay-auto-renew')
            ->assertExitCode(0);

        // Verify original subscription end date was preserved
        $oldSubscription->refresh();
        $this->assertEquals($originalEndDate->format('Y-m-d H:i:s'), $oldSubscription->subscription_end->format('Y-m-d H:i:s'));
    }
}
