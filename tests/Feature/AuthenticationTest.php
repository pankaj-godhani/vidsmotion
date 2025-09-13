<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test user registration functionality
     */
    public function test_user_can_register()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/register', $userData);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
            'name' => $userData['name'],
        ]);
    }

    /**
     * Test user login functionality
     */
    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test user logout functionality
     */
    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /**
     * Test password reset functionality
     */
    public function test_user_can_request_password_reset()
    {
        $user = User::factory()->create();

        $response = $this->post('/forgot-password', [
            'email' => $user->email,
        ]);

        $response->assertSessionHas('status');
    }

    /**
     * Test email verification
     */
    public function test_user_email_verification()
    {
        $user = User::factory()->unverified()->create();
        $this->actingAs($user);

        $response = $this->get('/email/verify');

        $response->assertStatus(200);
    }

    /**
     * Test protected routes require authentication
     */
    public function test_protected_routes_require_authentication()
    {
        $protectedRoutes = [
            '/dashboard',
            '/my-profile',
            '/video-generator',
            '/user/dashboard',
        ];

        foreach ($protectedRoutes as $route) {
            $response = $this->get($route);
            $response->assertRedirect('/login');
        }
    }

    /**
     * Test user profile update
     */
    public function test_user_can_update_profile()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];

        $response = $this->patch('/profile', $updateData);

        $response->assertRedirect('/profile');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    /**
     * Test user account deletion
     */
    public function test_user_can_delete_account()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->delete('/profile', [
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
