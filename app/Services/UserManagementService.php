<?php

namespace App\Services;

use App\Models\Opd;
use App\Models\User;
use App\Repositories\Contracts\OpdRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserManagementService
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
        private readonly OpdRepositoryInterface $opds,
        private readonly RoleRepositoryInterface $roles,
    ) {}

    /**
     * Paginate users for the management table.
     */
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->users->paginateWithRoles($perPage);
    }

    /**
     * Get all OPDs for the user form.
     *
     * @return Collection<int, Opd>
     */
    public function opds(): Collection
    {
        return $this->opds->all();
    }

    /**
     * Get roles for the user form.
     *
     * @return Collection<int, Role>
     */
    public function roles(): Collection
    {
        return $this->roles->all();
    }

    /**
     * Create a user and assign their role.
     *
     * @param  array{name: string, email: string, password: string, opd_id?: int|null, is_active?: bool, role: string}  $data
     */
    public function create(array $data): User
    {
        return DB::transaction(function () use ($data): User {
            $user = $this->users->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'opd_id' => $data['opd_id'] ?? null,
                'is_active' => $data['is_active'] ?? true,
            ]);

            $user->syncRoles([$data['role']]);

            return $user;
        });
    }

    /**
     * Update a user and synchronize their role.
     *
     * @param  array{name: string, email: string, password?: string|null, opd_id?: int|null, is_active?: bool, role: string}  $data
     */
    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data): User {
            $attributes = [
                'name' => $data['name'],
                'email' => $data['email'],
                'opd_id' => $data['opd_id'] ?? null,
                'is_active' => $data['is_active'] ?? false,
            ];

            if (! empty($data['password'])) {
                $attributes['password'] = $data['password'];
            }

            $user = $this->users->update($user, $attributes);
            $user->syncRoles([$data['role']]);

            return $user;
        });
    }

    /**
     * Delete a user.
     */
    public function delete(User $user): void
    {
        $this->users->delete($user);
    }
}
