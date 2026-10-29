<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

interface UserRepositoryInterface
{
    /**
     * Find a user by their email address.
     */
    public function findByEmail(string $email): ?User;

    /**
     * Persist a new password hash for the given user.
     */
    public function updatePassword(User $user, string $passwordHash): void;

    /**
     * Paginate users with their roles and OPD.
     */
    public function paginateWithRoles(int $perPage = 10): LengthAwarePaginator;

    /**
     * Get a query builder for users with their roles and OPD.
     *
     * @return Builder<User>
     */
    public function queryWithRoles(): Builder;

    /**
     * Create a new user.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): User;

    /**
     * Update the given user.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function update(User $user, array $attributes): User;

    /**
     * Delete the given user.
     */
    public function delete(User $user): void;
}
