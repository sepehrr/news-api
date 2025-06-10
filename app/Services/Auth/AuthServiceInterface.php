<?php

namespace App\Services\Auth;

use App\Models\User;

interface AuthServiceInterface
{
    /**
     * Login a user
     */
    public function login(array $credentials): string;

    /**
     * Register a user
     */
    public function register(array $data): User;

    /**
     * Logout a user
     */
    public function logout(): void;

    /**
     * Reset a user's password
     */
    public function resetPassword(string $email): void;

    /**
     * Set a user's password
     */
    public function setPassword(string $token, string $password): void;
}
