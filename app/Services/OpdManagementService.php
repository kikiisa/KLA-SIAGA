<?php

namespace App\Services;

use App\Models\Opd;
use App\Repositories\Contracts\OpdRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class OpdManagementService
{
    public function __construct(private readonly OpdRepositoryInterface $opds) {}

    /**
     * Paginate OPDs for the management table.
     */
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->opds->paginateWithUserCount($perPage);
    }

    /**
     * Get all OPDs for selection.
     *
     * @return Collection<int, Opd>
     */
    public function all(): Collection
    {
        return $this->opds->all();
    }

    /**
     * Create a new OPD.
     *
     * @param  array{kode_opd: string, nama_opd: string, singkatan?: string|null, deskripsi?: string|null, alamat?: string|null, email?: string|null, telepon?: string|null, is_active?: bool}  $data
     */
    public function create(array $data): Opd
    {
        return $this->opds->create([
            'kode_opd' => $data['kode_opd'],
            'nama_opd' => $data['nama_opd'],
            'singkatan' => $data['singkatan'] ?? null,
            'deskripsi' => $data['deskripsi'] ?? null,
            'alamat' => $data['alamat'] ?? null,
            'email' => $data['email'] ?? null,
            'telepon' => $data['telepon'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    /**
     * Update the given OPD.
     *
     * @param  array{kode_opd: string, nama_opd: string, singkatan?: string|null, deskripsi?: string|null, alamat?: string|null, email?: string|null, telepon?: string|null, is_active?: bool}  $data
     */
    public function update(Opd $opd, array $data): Opd
    {
        return $this->opds->update($opd, [
            'kode_opd' => $data['kode_opd'],
            'nama_opd' => $data['nama_opd'],
            'singkatan' => $data['singkatan'] ?? null,
            'deskripsi' => $data['deskripsi'] ?? null,
            'alamat' => $data['alamat'] ?? null,
            'email' => $data['email'] ?? null,
            'telepon' => $data['telepon'] ?? null,
            'is_active' => $data['is_active'] ?? false,
        ]);
    }

    /**
     * Delete the given OPD.
     */
    public function delete(Opd $opd): void
    {
        if ($this->opds->hasUsers($opd)) {
            throw ValidationException::withMessages([
                'opd' => 'OPD tidak dapat dihapus karena masih memiliki pengguna.',
            ]);
        }

        $this->opds->delete($opd);
    }
}
