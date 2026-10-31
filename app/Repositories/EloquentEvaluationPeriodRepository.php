<?php

namespace App\Repositories;

use App\Models\EvaluationPeriod;
use App\Repositories\Contracts\EvaluationPeriodRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class EloquentEvaluationPeriodRepository implements EvaluationPeriodRepositoryInterface
{
    /**
     * Get a query builder for evaluation periods, newest first.
     *
     * @return Builder<EvaluationPeriod>
     */
    public function query(): Builder
    {
        return EvaluationPeriod::query()
            ->select('evaluation_periods.*')
            ->orderByDesc('evaluation_periods.year')
            ->orderByDesc('evaluation_periods.start_date');
    }

    /**
     * Determine whether the given period has clusters.
     */
    public function hasClusters(EvaluationPeriod $period): bool
    {
        return DB::table('clusters')
            ->where('period_id', $period->getKey())
            ->whereNull('deleted_at')
            ->exists();
    }
}
