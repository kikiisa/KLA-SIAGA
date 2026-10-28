<?php

namespace App\Repositories;

use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EloquentRoleRepository implements RoleRepositoryInterface
{
    /**
     * Get all roles ordered by name.
     *
     * @return Collection<int, Role>
     */
    public function all(): Collection
    {
        return Role::query()
            ->orderBy('name')
            ->get();
    }

    /**
     * Get all roles with their permissions.
     *
     * @return Collection<int, Role>
     */
    public function allWithPermissions(): Collection
    {
        return Role::query()
            ->with('permissions')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get all permissions ordered by name.
     *
     * @return Collection<int, Permission>
     */
    public function allPermissions(): Collection
    {
        return Permission::query()
            ->orderBy('name')
            ->get();
    }

    /**
     * Synchronize permissions for the given role.
     *
     * @param  array<int, string>  $permissions
     */
    public function syncPermissions(Role $role, array $permissions): void
    {
        $role->syncPermissions($permissions);
    }
}
