<?php

namespace App\Services;

use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionService
{
    public function __construct(private readonly RoleRepositoryInterface $roles) {}

    /**
     * Get roles with their permissions.
     *
     * @return Collection<int, Role>
     */
    public function rolesWithPermissions(): Collection
    {
        return $this->roles->allWithPermissions();
    }

    /**
     * Get all available permissions.
     *
     * @return Collection<int, Permission>
     */
    public function permissions(): Collection
    {
        return $this->roles->allPermissions();
    }

    /**
     * Synchronize permissions for every role.
     *
     * @param  array<string, array<int, string>>  $permissionsByRole
     */
    public function sync(array $permissionsByRole): void
    {
        DB::transaction(function () use ($permissionsByRole): void {
            foreach ($this->roles->all() as $role) {
                $this->roles->syncPermissions($role, $permissionsByRole[$role->name] ?? []);
            }
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
