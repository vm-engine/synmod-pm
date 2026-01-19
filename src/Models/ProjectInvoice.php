<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use VmEngine\Pm\Factories\ProjectInvoiceFactory;
use VmEngine\Synapse\Traits\WithDeleteToken;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

class ProjectInvoice extends Model
{
    use HasCreator;
    use HasFactory;
    use HasUpdater;
    use WithDeleteToken;

    protected $fillable = [
        'project_id',
        'project_category_id',
        'client_id',
        'user_id',
        'invoice_number',
        'spk_document_id',
        'issue_date',
        'due_date',
        'recipient',
        'address',
        'attention',
        'notes',
        'amount',
        'include_vat',
        'vat_percentage',
        'vat_amount',
        'include_discount',
        'discount_percentage',
        'discount_amount',
        'include_dpp',
        'dpp_amount',
        'total_amount',
        'is_paid',
        'paid_at',
        'payment_notes',
        'payment_proof',
        'issuer_id',
        'bank_account_id',
        'has_signature',
        'signature_id',
        'invoice_document_id',
        'receipt_number',
        'receipt_amount_text',
        'receipt_document_id',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'due_date' => 'date',
            'include_vat' => 'boolean',
            'vat_percentage' => 'decimal:2',
            'vat_amount' => 'decimal:2',
            'include_discount' => 'boolean',
            'discount_percentage' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'include_dpp' => 'boolean',
            'dpp_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'is_paid' => 'boolean',
            'paid_at' => 'datetime',
            'has_signature' => 'boolean',
        ];
    }

    protected static function newFactory(): ProjectInvoiceFactory
    {
        return ProjectInvoiceFactory::new();
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function projectCategory(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    public function spkDocument(): BelongsTo
    {
        return $this->belongsTo(ProjectDocument::class, 'spk_document_id');
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(DocumentIssuer::class);
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function signature(): BelongsTo
    {
        return $this->belongsTo(Signature::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProjectInvoiceItem::class);
    }

    public function invoiceDocument(): BelongsTo
    {
        return $this->belongsTo(ProjectDocument::class, 'invoice_document_id');
    }

    public function receiptDocument(): BelongsTo
    {
        return $this->belongsTo(ProjectDocument::class, 'receipt_document_id');
    }
}
