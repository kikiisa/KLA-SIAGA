<?php

namespace App\Repositories\Contracts;

use App\Models\Opd;
use Illuminate\Database\Eloquent\Collection;

interface OpdRepositoryInterface
{
    /**
     * Get all active OPDs ordered by name.
     *
     * @return Collection<int, Opd>
     */
    public function allActive(): Collection;
}
