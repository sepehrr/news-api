<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Mail\ResetPasswordMail;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_user_can_register_with_valid_data()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/v1/auth/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'name',
                    'email',
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
            'name' => $userData['name'],
        ]);
    }

    public function test_user_cannot_register_with_invalid_data()
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123', // too short
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'user' => [
                        'name',
                        'email',
                    ],
                    'token'
                ]
            ]);

        $this->assertNotNull($response->json('data.token'));
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'wrong-password'
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid credentials']);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logout successful.']);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_user_can_request_password_reset()
    {
        Mail::fake();

        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/auth/reset-password', [
            'email' => $user->email
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Password reset email sent.']);

        Mail::assertSent(ResetPasswordMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $user->email
        ]);
    }

    public function test_user_can_reset_password_with_valid_token()
    {
        $user = User::factory()->create();
        $token = PasswordResetToken::create([
            'email' => $user->email,
            'token' => 'valid-token'
        ]);


        $response = $this->postJson('/api/v1/auth/set-password', [
            'token' => 'valid-token',
            'password' => 'new-password123'
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Password has been set successfully.']);

        // Verify the token was deleted after reset
        $this->assertDatabaseMissing('password_reset_tokens', [
            'token' => 'valid-token'
        ]);

        // Verify we can login with new password after reset
        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'new-password123'
        ]);

        $loginResponse->assertStatus(200);
    }

    public function test_user_cannot_reset_password_with_invalid_token()
    {
        $response = $this->postJson('/api/v1/auth/set-password', [
            'token' => 'invalid-token',
            'password' => 'new-password123'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['token']);
    }
}
