<?php

namespace App\DataTables;

use App\Models\Opd;
use App\Repositories\Contracts\OpdRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class OpdDataTable
{
    public function __construct(private readonly OpdRepositoryInterface $opds) {}

    /**
     * Build the OPD DataTables JSON response.
     */
    public function json(): JsonResponse
    {
        return DataTables::eloquent($this->opds->queryWithUserCount())
            ->editColumn('nama_opd', fn (Opd $opd): string => $this->nameColumn($opd))
            ->addColumn('kontak', fn (Opd $opd): string => $this->contactColumn($opd))
            ->editColumn('users_count', fn (Opd $opd): string => $this->usersColumn($opd))
            ->addColumn('status_label', fn (Opd $opd): string => $this->statusColumn($opd))
            ->addColumn('action', fn (Opd $opd): string => $this->actionColumn($opd))
            ->filterColumn('kontak', function (Builder $query, string $keyword): void {
                $query->where(function (Builder $contactQuery) use ($keyword): void {
                    $contactQuery->where('email', 'like', "%{$keyword}%")
                        ->orWhere('telepon', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('users_count', function (Builder $query): void {
                $query->whereRaw('1 = 0');
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
            ->rawColumns(['nama_opd', 'kontak', 'users_count', 'status_label', 'action'])
            ->toJson();
    }

    /**
     * Render the OPD name with its abbreviation.
     */
    private function nameColumn(Opd $opd): string
    {
        $initial = mb_substr($opd->singkatan ?? $opd->nama_opd, 0, 1);

        return '<div class="flex items-center gap-3">'
            .'<span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-emerald-100 text-xs font-bold text-emerald-700">'.e($initial).'</span>'
            .'<div class="min-w-0"><p class="truncate font-semibold text-slate-800">'.e($opd->nama_opd).'</p>'
            .'<p class="truncate text-xs text-slate-500">'.e($opd->singkatan ?? 'Tanpa singkatan').'</p></div>'
            .'</div>';
    }

    /**
     * Render the OPD contact details.
     */
    private function contactColumn(Opd $opd): string
    {
        return '<p>'.e($opd->email ?? '—').'</p>'
            .'<p class="text-xs text-slate-500">'.e($opd->telepon ?? '—').'</p>';
    }

    /**
     * Render the OPD user count.
     */
    private function usersColumn(Opd $opd): string
    {
        return '<span class="inline-flex items-center gap-1.5 rounded-full bg-sky-50 px-2.5 py-1 text-[11px] font-semibold text-sky-700 ring-1 ring-inset ring-sky-100">'
            .$opd->users_count.' pengguna</span>';
    }

    /**
     * Render the OPD active status.
     */
    private function statusColumn(Opd $opd): string
    {
        $classes = $opd->is_active
            ? 'bg-emerald-50 text-emerald-700 ring-emerald-100'
            : 'bg-slate-100 text-slate-600 ring-slate-200';

        $dotClasses = $opd->is_active ? 'bg-emerald-500' : 'bg-slate-400';

        return '<span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset '.$classes.'">'
            .'<span class="h-1.5 w-1.5 rounded-full '.$dotClasses.'"></span>'
            .($opd->is_active ? 'Aktif' : 'Nonaktif')
            .'</span>';
    }

    /**
     * Render the OPD actions.
     */
    private function actionColumn(Opd $opd): string
    {
        $currentUser = auth()->user();
        $canUpdate = $currentUser?->can('opd.update') ?? false;
        $canDelete = $currentUser?->can('opd.delete') ?? false;

        if (! $canUpdate && ! $canDelete) {
            return '<span class="text-xs text-slate-400">—</span>';
        }

        $html = '<div class="flex items-center justify-end gap-2">';

        if ($canUpdate) {
            $html .= '<a href="'.e(route('admin.opds.edit', $opd)).'" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50">Edit</a>';
        }

        if ($canDelete) {
            $html .= '<form method="POST" action="'.e(route('admin.opds.destroy', $opd)).'" data-confirm="OPD ini akan dihapus dari daftar pilihan pengguna." data-confirm-title="Hapus OPD?" data-confirm-button="Ya, hapus">'
                .csrf_field()
                .method_field('DELETE')
                .'<button type="submit" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-50">Hapus</button>'
                .'</form>';
        }

        return $html.'</div>';
    }
}
