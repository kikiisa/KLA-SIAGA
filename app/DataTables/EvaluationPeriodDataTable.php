<?php

namespace App\DataTables;

use App\Models\EvaluationPeriod;
use App\Repositories\Contracts\EvaluationPeriodRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class EvaluationPeriodDataTable
{
    public function __construct(private readonly EvaluationPeriodRepositoryInterface $periods) {}

    /**
     * Build the evaluation period DataTables JSON response.
     */
    public function json(): JsonResponse
    {
        return DataTables::eloquent($this->periods->query())
            ->editColumn('name', fn (EvaluationPeriod $period): string => $this->nameColumn($period))
            ->editColumn('year', fn (EvaluationPeriod $period): string => (string) $period->year)
            ->editColumn('start_date', fn (EvaluationPeriod $period): string => $this->dateRangeColumn($period))
            ->addColumn('status_label', fn (EvaluationPeriod $period): string => $this->statusColumn($period))
            ->addColumn('action', fn (EvaluationPeriod $period): string => $this->actionColumn($period))
            ->filterColumn('start_date', function (Builder $query): void {
                $query->whereRaw('1 = 0');
            })
            ->filterColumn('status_label', function (Builder $query, string $keyword): void {
                $keyword = mb_strtolower(trim($keyword));
                $statuses = [];

                foreach (EvaluationPeriod::STATUS_LABELS as $status => $label) {
                    if (mb_strtolower($label) === $keyword) {
                        $statuses[] = $status;
                    }
                }

                if ($statuses === []) {
                    $query->whereRaw('1 = 0');

                    return;
                }

                $query->where('status', $statuses[0]);
            })
            ->filterColumn('action', function (Builder $query): void {
                $query->whereRaw('1 = 0');
            })
            ->rawColumns(['name', 'start_date', 'status_label', 'action'])
            ->toJson();
    }

    /**
     * Render the period name with its code.
     */
    private function nameColumn(EvaluationPeriod $period): string
    {
        $initial = mb_substr($period->name, 0, 1);

        return '<div class="flex items-center gap-3">'
            .'<span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-emerald-100 text-xs font-bold text-emerald-700">'.e($initial).'</span>'
            .'<div class="min-w-0"><p class="truncate font-semibold text-slate-800">'.e($period->name).'</p>'
            .'<p class="truncate text-xs text-slate-500">'.e($period->code).'</p></div>'
            .'</div>';
    }

    /**
     * Render the collection date range of the period.
     */
    private function dateRangeColumn(EvaluationPeriod $period): string
    {
        return '<p>'.e($period->start_date?->format('d M Y')).'</p>'
            .'<p class="text-xs text-slate-500">s.d. '.e($period->end_date?->format('d M Y')).'</p>';
    }

    /**
     * Render the period status.
     */
    private function statusColumn(EvaluationPeriod $period): string
    {
        $classes = match ($period->status) {
            EvaluationPeriod::STATUS_ACTIVE => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
            EvaluationPeriod::STATUS_DRAFT => 'bg-slate-100 text-slate-600 ring-slate-200',
            EvaluationPeriod::STATUS_CLOSED => 'bg-amber-50 text-amber-700 ring-amber-100',
            EvaluationPeriod::STATUS_ARCHIVED => 'bg-sky-50 text-sky-700 ring-sky-100',
            default => 'bg-slate-100 text-slate-600 ring-slate-200',
        };

        $dotClasses = match ($period->status) {
            EvaluationPeriod::STATUS_ACTIVE => 'bg-emerald-500',
            EvaluationPeriod::STATUS_CLOSED => 'bg-amber-500',
            EvaluationPeriod::STATUS_ARCHIVED => 'bg-sky-500',
            default => 'bg-slate-400',
        };

        return '<span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset '.$classes.'">'
            .'<span class="h-1.5 w-1.5 rounded-full '.$dotClasses.'"></span>'
            .e($period->statusLabel())
            .'</span>';
    }

    /**
     * Render the period actions: lifecycle changes, edit, and delete.
     */
    private function actionColumn(EvaluationPeriod $period): string
    {
        $currentUser = auth()->user();

        $parts = [];

        if ($period->canChangeStatusTo(EvaluationPeriod::STATUS_ACTIVE) && $currentUser?->can('period.activate')) {
            $parts[] = $this->statusForm($period, EvaluationPeriod::STATUS_ACTIVE, 'Aktifkan', 'Aktifkan periode ini agar OPD dapat mengisi bukti dukung.', 'Ya, aktifkan');
        }

        if ($period->canChangeStatusTo(EvaluationPeriod::STATUS_CLOSED) && $currentUser?->can('period.close')) {
            $parts[] = $this->statusForm($period, EvaluationPeriod::STATUS_CLOSED, 'Tutup', 'Menutup periode menghentikan pengumpulan dokumen.', 'Ya, tutup');
        }

        if ($period->canChangeStatusTo(EvaluationPeriod::STATUS_ARCHIVED) && $currentUser?->can('period.archive')) {
            $parts[] = $this->statusForm($period, EvaluationPeriod::STATUS_ARCHIVED, 'Arsipkan', 'Mengarsipkan periode mempertahankan seluruh data dan riwayat.', 'Ya, arsipkan');
        }

        if ($currentUser?->can('period.update')) {
            $parts[] = '<a href="'.e(route('admin.periods.edit', $period)).'" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50">Edit</a>';
        }

        if ($currentUser?->can('period.delete') && $period->status === EvaluationPeriod::STATUS_DRAFT) {
            $parts[] = '<form method="POST" action="'.e(route('admin.periods.destroy', $period)).'" data-confirm="Periode ini akan dihapus secara permanen." data-confirm-title="Hapus periode?" data-confirm-button="Ya, hapus">'
                .csrf_field()
                .method_field('DELETE')
                .'<button type="submit" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-50">Hapus</button>'
                .'</form>';
        }

        if (count($parts) === 0) {
            return '<span class="text-xs text-slate-400">—</span>';
        }

        return '<div class="flex flex-wrap items-center justify-end gap-2">'.implode('', $parts).'</div>';
    }

    /**
     * Build a status change form button.
     */
    private function statusForm(EvaluationPeriod $period, string $status, string $label, string $message, string $button): string
    {
        return '<form method="POST" action="'.e(route('admin.periods.status', $period)).'" data-confirm="'.$message.'" data-confirm-title="'.e($label).' periode?" data-confirm-button="'.e($button).'">'
            .csrf_field()
            .method_field('PUT')
            .'<input type="hidden" name="status" value="'.e($status).'">'
            .'<button type="submit" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-100">'.e($label).'</button>'
            .'</form>';
    }
}
