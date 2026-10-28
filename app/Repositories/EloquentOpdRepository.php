<?php

namespace App\Repositories;

use App\Models\Opd;
use App\Repositories\Contracts\OpdRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentOpdRepository implements OpdRepositoryInterface
{
    /**
     * Get all active OPDs ordered by name.
     *
     * @return Collection<int, Opd>
     */
    public function allActive(): Collection
    {
        return Opd::query()
            ->where('is_active', true)
            ->orderBy('nama_opd')
            ->get();
    }
}
