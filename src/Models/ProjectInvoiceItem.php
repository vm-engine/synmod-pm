<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use VmEngine\Pm\Factories\ProjectInvoiceItemFactory;
use VmEngine\Synapse\Traits\WithDeleteToken;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

/**
 * @property int $id
 * @property int $project_id
 * @property int $project_invoice_id
 * @property int $client_id
 * @property string $name
 * @property string $amount
 * @property bool $include_discount
 * @property string $discount_percentage
 * @property string $discount_amount
 * @property bool $is_paid
 * @property int $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ProjectInvoiceItem extends Model
{
    use HasCreator;

    /** @use HasFactory<ProjectInvoiceItemFactory> */
    use HasFactory;

    use HasUpdater;
    use WithDeleteToken;

    protected $fillable = [
        'project_id',
        'project_invoice_id',
        'client_id',
        'name',
        'amount',
        'include_discount',
        'discount_percentage',
        'discount_amount',
        'is_paid',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'include_discount' => 'boolean',
            'discount_percentage' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'is_paid' => 'boolean',
        ];
    }

    protected static function newFactory(): ProjectInvoiceItemFactory
    {
        return ProjectInvoiceItemFactory::new();
    }

    /**
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return BelongsTo<ProjectInvoice, $this>
     */
    public function projectInvoice(): BelongsTo
    {
        return $this->belongsTo(ProjectInvoice::class);
    }

    /**
     * @return BelongsTo<Client, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
