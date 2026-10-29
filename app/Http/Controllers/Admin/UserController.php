<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(private readonly UserManagementService $userManagementService) {}

    /**
     * Display a paginated list of users.
     */
    public function index(): View
    {
        return view('admin.users.index');
    }

    /**
     * Return the users data for DataTables.
     */
    public function data(UserDataTable $dataTable): JsonResponse
    {
        return $dataTable->json();
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('admin.users.create', [
            'opds' => $this->userManagementService->opds(),
            'roles' => $this->userManagementService->roles(),
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->userManagementService->create($request->validated());

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the given user.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user->load('roles', 'opd'),
            'opds' => $this->userManagementService->opds(),
            'roles' => $this->userManagementService->roles(),
        ]);
    }

    /**
     * Update the given user.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->userManagementService->update($user, $request->validated());

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Delete the given user.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->is(auth()->user())) {
            return back()->withErrors(['user' => 'Anda tidak dapat menghapus akun sendiri.']);
        }

        $this->userManagementService->delete($user);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Pengguna berhasil dihapus.');
    }
}
