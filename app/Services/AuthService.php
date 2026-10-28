<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(private readonly UserRepositoryInterface $users) {}

    /**
     * Attempt to authenticate a user with the given credentials.
     */
    public function attempt(string $email, string $password, bool $remember = false): bool
    {
        $user = $this->users->findByEmail($email);

        if (! $user instanceof User) {
            return false;
        }

        if (! Hash::check($password, $user->password)) {
            return false;
        }

        Auth::login($user, $remember);

        if (Hash::needsRehash($user->password)) {
            $this->users->updatePassword($user, Hash::make($password));
        }

        return true;
    }

    /**
     * Log the current user out.
     */
    public function logout(): void
    {
        Auth::logout();
    }
}
