<?php

namespace App\Models;

use Database\Factories\OpdFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Opd extends Model
{
    /** @use HasFactory<OpdFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'kode_opd',
        'nama_opd',
        'singkatan',
        'deskripsi',
        'alamat',
        'email',
        'telepon',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the users that belong to the OPD.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
