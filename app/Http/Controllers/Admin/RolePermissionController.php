<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRolePermissionsRequest;
use App\Services\RolePermissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RolePermissionController extends Controller
{
    public function __construct(private readonly RolePermissionService $rolePermissionService) {}

    /**
     * Display the role and permission matrix.
     */
    public function index(): View
    {
        return view('admin.roles.index', [
            'roles' => $this->rolePermissionService->rolesWithPermissions(),
            'permissions' => $this->rolePermissionService->permissions(),
        ]);
    }

    /**
     * Update permissions for all roles.
     */
    public function update(UpdateRolePermissionsRequest $request): RedirectResponse
    {
        $this->rolePermissionService->sync($request->validated('permissions') ?? []);

        return redirect()
            ->route('admin.roles.index')
            ->with('status', 'Permission role berhasil diperbarui.');
    }
}
