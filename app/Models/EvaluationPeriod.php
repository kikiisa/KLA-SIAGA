<?php

namespace App\Models;

use Database\Factories\EvaluationPeriodFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvaluationPeriod extends Model
{
    /** @use HasFactory<EvaluationPeriodFactory> */
    use HasFactory, SoftDeletes;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_CLOSED = 'closed';

    public const STATUS_ARCHIVED = 'archived';

    /**
     * @var array<string, string>
     */
    public const STATUS_LABELS = [
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_ACTIVE => 'Aktif',
        self::STATUS_CLOSED => 'Ditutup',
        self::STATUS_ARCHIVED => 'Diarsipkan',
    ];

    /**
     * Allowed status transitions to preserve the lifecycle:
     * draft → active → closed → archived.
     *
     * @var array<string, list<string>>
     */
    public const STATUS_TRANSITIONS = [
        self::STATUS_DRAFT => [self::STATUS_ACTIVE],
        self::STATUS_ACTIVE => [self::STATUS_CLOSED],
        self::STATUS_CLOSED => [self::STATUS_ARCHIVED],
        self::STATUS_ARCHIVED => [],
    ];

    /**
     * Map of target statuses to the required permission.
     *
     * @var array<string, string>
     */
    public const STATUS_PERMISSIONS = [
        self::STATUS_ACTIVE => 'period.activate',
        self::STATUS_CLOSED => 'period.close',
        self::STATUS_ARCHIVED => 'period.archive',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'name',
        'year',
        'description',
        'start_date',
        'end_date',
        'status',
        'created_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    /**
     * Get the admin who created the period.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Determine whether the period can transition to the given target status.
     */
    public function canChangeStatusTo(string $status): bool
    {
        return in_array($status, self::STATUS_TRANSITIONS[$this->status] ?? [], true);
    }

    /**
     * Get the human-readable status label.
     */
    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }
}
