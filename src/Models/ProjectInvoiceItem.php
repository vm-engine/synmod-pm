<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use VmEngine\Pm\Factories\ProjectInvoiceItemFactory;
use VmEngine\Synapse\Traits\WithDeleteToken;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

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
