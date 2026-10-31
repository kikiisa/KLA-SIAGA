<?php

namespace App\Repositories\Contracts;

use App\Models\EvaluationPeriod;
use Illuminate\Database\Eloquent\Builder;

interface EvaluationPeriodRepositoryInterface
{
    /**
     * Get a query builder for evaluation periods.
     *
     * @return Builder<EvaluationPeriod>
     */
    public function query(): Builder;

    /**
     * Determine whether the given period has clusters.
     */
    public function hasClusters(EvaluationPeriod $period): bool;
}
