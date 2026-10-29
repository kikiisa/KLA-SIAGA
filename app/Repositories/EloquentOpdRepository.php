<?php

namespace App\Repositories;

use App\Models\Opd;
use App\Repositories\Contracts\OpdRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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

    /**
     * Get all OPDs ordered by name.
     *
     * @return Collection<int, Opd>
     */
    public function all(): Collection
    {
        return Opd::query()
            ->orderBy('nama_opd')
            ->get();
    }

    /**
     * Paginate OPDs with their user count.
     */
    public function paginateWithUserCount(int $perPage = 10): LengthAwarePaginator
    {
        return Opd::query()
            ->withCount('users')
            ->orderBy('nama_opd')
            ->paginate($perPage);
    }

    /**
     * Create a new OPD.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Opd
    {
        return Opd::query()->create($attributes);
    }

    /**
     * Update the given OPD.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function update(Opd $opd, array $attributes): Opd
    {
        $opd->fill($attributes)->save();

        return $opd;
    }

    /**
     * Determine whether the given OPD has users.
     */
    public function hasUsers(Opd $opd): bool
    {
        return $opd->users()->exists();
    }

    /**
     * Delete the given OPD.
     */
    public function delete(Opd $opd): void
    {
        $opd->delete();
    }
}
