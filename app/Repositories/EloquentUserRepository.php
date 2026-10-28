<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentUserRepository implements UserRepositoryInterface
{
    /**
     * Find a user by their email address.
     */
    public function findByEmail(string $email): ?User
    {
        return User::query()
            ->where('email', $email)
            ->first();
    }

    /**
     * Persist a new password hash for the given user.
     */
    public function updatePassword(User $user, string $passwordHash): void
    {
        $user->forceFill([
            'password' => $passwordHash,
        ])->save();
    }

    /**
     * Paginate users with their roles and OPD.
     */
    public function paginateWithRoles(int $perPage = 10): LengthAwarePaginator
    {
        return User::query()
            ->with(['opd', 'roles'])
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Create a new user.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): User
    {
        return User::query()->create($attributes);
    }

    /**
     * Update the given user.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function update(User $user, array $attributes): User
    {
        $user->fill($attributes)->save();

        return $user;
    }

    /**
     * Delete the given user.
     */
    public function delete(User $user): void
    {
        $user->delete();
    }
}
