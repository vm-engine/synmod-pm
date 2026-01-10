<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use VmEngine\Pm\Factories\ProjectReceiptFactory;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

class ProjectReceipt extends Model
{
    use HasCreator;
    use HasFactory;
    use HasUpdater;

    protected $fillable = [
        'project_id',
        'project_invoice_id',
        'client_id',
        'from',
        'receipt_number',
        'amount',
        'amount_text',
        'notes',
        'issue_date',
        'include_vat',
        'vat_amount',
        'include_discount',
        'discount_amount',
        'include_dpp',
        'dpp_amount',
        'payment_proof',
        'paid_at',
        'payment_notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'vat_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'dpp_amount' => 'decimal:2',
            'issue_date' => 'date',
            'include_vat' => 'boolean',
            'include_discount' => 'boolean',
            'include_dpp' => 'boolean',
            'paid_at' => 'datetime',
        ];
    }

    protected static function newFactory(): ProjectReceiptFactory
    {
        return ProjectReceiptFactory::new();
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function projectInvoice(): BelongsTo
    {
        return $this->belongsTo(ProjectInvoice::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
