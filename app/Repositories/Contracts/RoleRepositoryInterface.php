<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

interface RoleRepositoryInterface
{
    /**
     * Get all roles ordered by name.
     *
     * @return Collection<int, Role>
     */
    public function all(): Collection;

    /**
     * Get all roles with their permissions.
     *
     * @return Collection<int, Role>
     */
    public function allWithPermissions(): Collection;

    /**
     * Get all permissions ordered by name.
     *
     * @return Collection<int, Permission>
     */
    public function allPermissions(): Collection;

    /**
     * Synchronize permissions for the given role.
     *
     * @param  array<int, string>  $permissions
     */
    public function syncPermissions(Role $role, array $permissions): void;
}
