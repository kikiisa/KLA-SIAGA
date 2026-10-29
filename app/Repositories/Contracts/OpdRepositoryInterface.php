<?php

namespace App\Repositories\Contracts;

use App\Models\Opd;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface OpdRepositoryInterface
{
    /**
     * Get all active OPDs ordered by name.
     *
     * @return Collection<int, Opd>
     */
    public function allActive(): Collection;

    /**
     * Get all OPDs ordered by name.
     *
     * @return Collection<int, Opd>
     */
    public function all(): Collection;

    /**
     * Paginate OPDs with their user count.
     */
    public function paginateWithUserCount(int $perPage = 10): LengthAwarePaginator;

    /**
     * Get a query builder for OPDs with their user count.
     *
     * @return Builder<Opd>
     */
    public function queryWithUserCount(): Builder;

    /**
     * Create a new OPD.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Opd;

    /**
     * Update the given OPD.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function update(Opd $opd, array $attributes): Opd;

    /**
     * Determine whether the given OPD has users.
     */
    public function hasUsers(Opd $opd): bool;

    /**
     * Delete the given OPD.
     */
    public function delete(Opd $opd): void;
}
