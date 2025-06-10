<?php

namespace App\Services\Auth;

use App\Mail\ResetPasswordMail;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthService implements AuthServiceInterface
{
    public function login(array $credentials): string
    {
        if (!Auth::attempt($credentials)) {
            throw new \Exception('Invalid credentials', 401);
        }

        event(new Login('sanctum', Auth::user(), false));
        $token = Auth::user()->createToken('auth_token')->plainTextToken;

        return $token;
    }

    public function register(array $data): User
    {
        $user = User::create($data);
        event(new Registered($user));

        return $user;
    }

    public function logout(): void
    {
        Auth::user()->currentAccessToken()->delete();
    }

    public function resetPassword(string $email): void
    {
        $user = User::where('email', $email)->first();

        if ($user) {
            $user->passwordResetTokens()->delete();
            $token = $user->passwordResetTokens()->create()->token;
            Mail::to($user->email)->send(new ResetPasswordMail($token));
        }
    }

    public function setPassword(string $token, string $password): void
    {
        $resetToken = PasswordResetToken::findByToken($token);
        $resetToken->user->update(['password' => $password]);
        $resetToken->user->passwordResetTokens()->delete();
    }
}
