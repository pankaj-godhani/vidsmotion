<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

test('reset password link screen can be rendered', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
});

test('reset password link can be requested', function () {
    Mail::fake();

    $user = User::factory()->create();

    $response = $this->from('/forgot-password')->post('/forgot-password', ['email' => $user->email]);

    $response->assertStatus(302); // Should redirect back
    $response->assertSessionHas('status'); // Should have status message
});

test('reset password screen can be rendered', function () {
    $user = User::factory()->create();

    // Create a password reset token
    $token = Password::createToken($user);

    $response = $this->get('/reset-password/'.$token.'?email='.$user->email);

    $response->assertStatus(200);
});

test('password can be reset with valid token', function () {
    $user = User::factory()->create();

    // Create a password reset token
    $token = Password::createToken($user);

    $response = $this->from('/reset-password/'.$token)->post('/reset-password', [
        'token' => $token,
        'email' => $user->email,
        'password' => 'newpassword',
        'password_confirmation' => 'newpassword',
    ]);

    $response->assertStatus(302); // Should redirect
    $response->assertSessionHas('status'); // Should have status message

    // Verify password was actually changed
    $user->refresh();
    expect(Hash::check('newpassword', $user->password))->toBeTrue();
});
