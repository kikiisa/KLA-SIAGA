<?php

namespace App\DataTables;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class UserDataTable
{
    public function __construct(private readonly UserRepositoryInterface $users) {}

    /**
     * Build the users DataTables JSON response.
     */
    public function json(): JsonResponse
    {
        return DataTables::eloquent($this->users->queryWithRoles())
            ->editColumn('name', fn (User $user): string => $this->nameColumn($user))
            ->addColumn('opd_label', fn (User $user): string => $this->opdColumn($user))
            ->addColumn('roles_label', fn (User $user): string => $this->rolesColumn($user))
            ->addColumn('status_label', fn (User $user): string => $this->statusColumn($user))
            ->addColumn('action', fn (User $user): string => $this->actionColumn($user))
            ->filterColumn('opd_label', function (Builder $query, string $keyword): void {
                $query->whereHas('opd', function (Builder $opdQuery) use ($keyword): void {
                    $opdQuery->where('nama_opd', 'like', "%{$keyword}%")
                        ->orWhere('singkatan', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('roles_label', function (Builder $query, string $keyword): void {
                $query->whereHas('roles', function (Builder $roleQuery) use ($keyword): void {
                    $roleQuery->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('status_label', function (Builder $query, string $keyword): void {
                $keyword = strtolower(trim($keyword));

                if ($keyword === 'aktif') {
                    $query->where('is_active', true);

                    return;
                }

                if ($keyword === 'nonaktif') {
                    $query->where('is_active', false);

                    return;
                }

                $query->whereRaw('1 = 0');
            })
            ->filterColumn('action', function (Builder $query): void {
                $query->whereRaw('1 = 0');
            })
            ->rawColumns(['name', 'opd_label', 'roles_label', 'status_label', 'action'])
            ->toJson();
    }

    /**
     * Render the user name with their initials.
     */
    private function nameColumn(User $user): string
    {
        $initials = collect(explode(' ', $user->name))
            ->filter()
            ->take(2)
            ->map(fn (string $part): string => mb_substr($part, 0, 1))
            ->implode('');

        return '<div class="flex items-center gap-3">'
            .'<span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-700">'.e($initials).'</span>'
            .'<span class="font-semibold text-slate-800">'.e($user->name).'</span>'
            .'</div>';
    }

    /**
     * Render the OPD label for the user.
     */
    private function opdColumn(User $user): string
    {
        if ($user->opd === null) {
            return '<span class="text-slate-400">—</span>';
        }

        $label = $user->opd->singkatan
            ? $user->opd->singkatan.' — '.$user->opd->nama_opd
            : $user->opd->nama_opd;

        return e($label);
    }

    /**
     * Render the roles of the user.
     */
    private function rolesColumn(User $user): string
    {
        if ($user->roles->isEmpty()) {
            return '<span class="text-xs text-slate-400">Belum ada role</span>';
        }

        $labels = [
            'admin' => 'Admin',
            'pic' => 'PIC',
            'operator_opd' => 'Operator OPD',
        ];

        $badges = $user->roles
            ->map(fn ($role): string => '<span class="inline-flex items-center rounded-full bg-sky-50 px-2.5 py-1 text-[11px] font-semibold text-sky-700 ring-1 ring-inset ring-sky-100">'.e($labels[$role->name] ?? $role->name).'</span>')
            ->implode('');

        return '<div class="flex flex-wrap gap-1.5">'.$badges.'</div>';
    }

    /**
     * Render the user active status.
     */
    private function statusColumn(User $user): string
    {
        $classes = $user->is_active
            ? 'bg-emerald-50 text-emerald-700 ring-emerald-100'
            : 'bg-slate-100 text-slate-600 ring-slate-200';

        $dotClasses = $user->is_active ? 'bg-emerald-500' : 'bg-slate-400';

        return '<span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset '.$classes.'">'
            .'<span class="h-1.5 w-1.5 rounded-full '.$dotClasses.'"></span>'
            .($user->is_active ? 'Aktif' : 'Nonaktif')
            .'</span>';
    }

    /**
     * Render the user actions.
     */
    private function actionColumn(User $user): string
    {
        $currentUser = auth()->user();
        $canUpdate = $currentUser?->can('user.update') ?? false;
        $canDelete = $currentUser?->can('user.delete') ?? false;
        $isSelf = $currentUser?->is($user) ?? false;

        if (! $canUpdate && ! $canDelete) {
            return '<span class="text-xs text-slate-400">—</span>';
        }

        $html = '<div class="flex items-center justify-end gap-2">';

        if ($canUpdate) {
            $html .= '<a href="'.e(route('admin.users.edit', $user)).'" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50">Edit</a>';
        }

        if ($canDelete && ! $isSelf) {
            $html .= '<form method="POST" action="'.e(route('admin.users.destroy', $user)).'" data-confirm="Pengguna ini akan dihapus permanen dari daftar." data-confirm-title="Hapus pengguna?" data-confirm-button="Ya, hapus">'
                .csrf_field()
                .method_field('DELETE')
                .'<button type="submit" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-50">Hapus</button>'
                .'</form>';
        }

        return $html.'</div>';
    }
}
