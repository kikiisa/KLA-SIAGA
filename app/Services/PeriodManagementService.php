<?php

namespace App\Services;

use App\Models\EvaluationPeriod;
use App\Repositories\Contracts\EvaluationPeriodRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PeriodManagementService
{
    public function __construct(private readonly EvaluationPeriodRepositoryInterface $periods) {}

    /**
     * Get a query builder for evaluation periods.
     *
     * @return Builder<EvaluationPeriod>
     */
    public function query(): Builder
    {
        return $this->periods->query();
    }

    /**
     * Create a new evaluation period.
     *
     * @param  array{code: string, name: string, year: int, description?: string|null, start_date: string, end_date: string}  $data
     */
    public function create(array $data): EvaluationPeriod
    {
        return EvaluationPeriod::query()->create([
            'code' => $data['code'],
            'name' => $data['name'],
            'year' => $data['year'],
            'description' => $data['description'] ?? null,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'status' => EvaluationPeriod::STATUS_DRAFT,
            'created_by' => Auth::id(),
        ]);
    }

    /**
     * Update the given evaluation period.
     *
     * @param  array{code: string, name: string, year: int, description?: string|null, start_date: string, end_date: string}  $data
     */
    public function update(EvaluationPeriod $period, array $data): EvaluationPeriod
    {
        $period->fill([
            'code' => $data['code'],
            'name' => $data['name'],
            'year' => $data['year'],
            'description' => $data['description'] ?? null,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
        ])->save();

        return $period;
    }

    /**
     * Delete the given evaluation period.
     */
    public function delete(EvaluationPeriod $period): void
    {
        if ($this->periods->hasClusters($period)) {
            throw ValidationException::withMessages([
                'period' => 'Periode tidak dapat dihapus karena masih memiliki cluster.',
            ]);
        }

        if ($period->status !== EvaluationPeriod::STATUS_DRAFT) {
            throw ValidationException::withMessages([
                'period' => 'Hanya periode berstatus Draft yang dapat dihapus untuk menjaga data periode lama.',
            ]);
        }

        $period->delete();
    }

    /**
     * Change the status of the given evaluation period through its lifecycle.
     */
    public function changeStatus(EvaluationPeriod $period, string $status): EvaluationPeriod
    {
        if (! $period->canChangeStatusTo($status)) {
            throw ValidationException::withMessages([
                'status' => 'Periode berstatus '.$period->statusLabel().' tidak dapat diubah menjadi '.$this->statusLabel($status),
            ]);
        }

        $period->status = $status;
        $period->save();

        return $period;
    }

    /**
     * Get the human-readable label for a status.
     */
    private function statusLabel(string $status): string
    {
        return EvaluationPeriod::STATUS_LABELS[$status] ?? $status;
    }
}
