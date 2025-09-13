<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Carbon\Carbon;

class ProfileTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test profile page access
     */
    public function test_user_can_access_profile_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/my-profile');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('MyProfile')
            ->has('canLogin')
            ->has('payments')
            ->has('activeSubscription')
        );
    }

    /**
     * Test profile page requires authentication
     */
    public function test_profile_page_requires_authentication()
    {
        $response = $this->get('/my-profile');

        $response->assertRedirect('/login');
    }

    /**
     * Test profile displays user payments
     */
    public function test_profile_displays_user_payments()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create test payments
        $payments = Payment::factory()->count(3)->create([
            'user_id' => $user->id,
            'status' => 'completed',
        ]);

        $response = $this->get('/my-profile');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('MyProfile')
            ->has('payments', 3)
        );
    }

    /**
     * Test profile displays active subscription
     */
    public function test_profile_displays_active_subscription()
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

        $response = $this->get('/my-profile');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('MyProfile')
            ->has('activeSubscription')
            ->where('activeSubscription.id', $subscription->id)
        );
    }

    /**
     * Test profile displays deactivated subscription
     */
    public function test_profile_displays_deactivated_subscription()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a deactivated subscription that's still within paid period
        $subscription = Payment::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'is_active' => false,
            'subscription_end' => Carbon::now()->addDays(5), // Still within paid period
        ]);

        $response = $this->get('/my-profile');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('MyProfile')
            ->has('activeSubscription')
            ->where('activeSubscription.id', $subscription->id)
        );
    }

    /**
     * Test subscription deactivation
     */
    public function test_user_can_deactivate_subscription()
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
        $response->assertSessionHas('success', 'Your subscription has been deactivated successfully.');

        // Verify subscription was deactivated
        $this->assertDatabaseHas('payments', [
            'id' => $subscription->id,
            'is_active' => false,
        ]);
    }

    /**
     * Test deactivation fails when no active subscription
     */
    public function test_deactivation_fails_without_active_subscription()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/subscription/deactivate');

        $response->assertRedirect();
        $response->assertSessionHas('error', 'No active subscription found.');
    }

    /**
     * Test auto-renewal toggle
     */
    public function test_user_can_toggle_auto_renewal()
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
        $response->assertSessionHas('success', 'Auto-renewal has been disabled for your subscription.');

        // Verify auto-renewal was toggled
        $this->assertDatabaseHas('payments', [
            'id' => $subscription->id,
            'auto_renew' => false,
        ]);
    }

    /**
     * Test auto-renewal toggle fails without active subscription
     */
    public function test_auto_renewal_toggle_fails_without_active_subscription()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/subscription/auto-renew', [
            'auto_renew' => false,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'No active subscription found.');
    }

    /**
     * Test avatar upload
     */
    public function test_user_can_upload_avatar()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $this->actingAs($user);

        $file = UploadedFile::fake()->image('avatar.jpg', 100, 100);

        $response = $this->post('/profile/avatar', [
            'avatar' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify file was stored
        $user->refresh();
        $this->assertNotNull($user->avatar);
        Storage::disk('public')->assertExists('avatars/' . $user->avatar);
    }

    /**
     * Test avatar update replaces old avatar
     */
    public function test_avatar_update_replaces_old_avatar()
    {
        Storage::fake('public');

        $user = User::factory()->create(['avatar' => 'old_avatar.jpg']);
        $this->actingAs($user);

        // Create old avatar file
        Storage::disk('public')->put('avatars/old_avatar.jpg', 'fake content');

        $file = UploadedFile::fake()->image('new_avatar.jpg', 100, 100);

        $response = $this->post('/profile/avatar', [
            'avatar' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify old avatar was deleted
        Storage::disk('public')->assertMissing('avatars/old_avatar.jpg');

        // Verify new avatar was stored
        $user->refresh();
        $this->assertNotEquals('old_avatar.jpg', $user->avatar);
        Storage::disk('public')->assertExists('avatars/' . $user->avatar);
    }

    /**
     * Test avatar validation
     */
    public function test_avatar_upload_validation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Test with invalid file type
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->post('/profile/avatar', [
            'avatar' => $file,
        ]);

        $response->assertSessionHasErrors(['avatar']);
    }

    /**
     * Test avatar size validation
     */
    public function test_avatar_size_validation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Test with file too large
        $file = UploadedFile::fake()->image('large_avatar.jpg', 100, 100)->size(2048); // 2MB

        $response = $this->post('/profile/avatar', [
            'avatar' => $file,
        ]);

        $response->assertSessionHasErrors(['avatar']);
    }

    /**
     * Test profile displays subscription expiry information
     */
    public function test_profile_displays_subscription_expiry()
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

        $response = $this->get('/my-profile');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('MyProfile')
            ->has('activeSubscription')
            ->where('activeSubscription.subscription_end', $subscription->subscription_end->toISOString())
        );
    }

    /**
     * Test profile displays auto-renewal status
     */
    public function test_profile_displays_auto_renewal_status()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create an active subscription with auto-renewal enabled
        $subscription = Payment::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'is_active' => true,
            'auto_renew' => true,
            'subscription_end' => Carbon::now()->addDays(7),
        ]);

        $response = $this->get('/my-profile');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('MyProfile')
            ->has('activeSubscription')
            ->where('activeSubscription.auto_renew', true)
        );
    }

    /**
     * Test profile handles multiple subscriptions correctly
     */
    public function test_profile_handles_multiple_subscriptions()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create multiple subscriptions
        $oldSubscription = Payment::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'is_active' => false,
            'subscription_end' => Carbon::now()->subDays(5), // Expired
            'created_at' => Carbon::now()->subDays(10),
        ]);

        $activeSubscription = Payment::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'is_active' => true,
            'subscription_end' => Carbon::now()->addDays(7),
            'created_at' => Carbon::now()->subDays(2),
        ]);

        $response = $this->get('/my-profile');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('MyProfile')
            ->has('activeSubscription')
            ->where('activeSubscription.id', $activeSubscription->id) // Should show the active one
            ->has('payments', 2) // Should show all payments
        );
    }
}
