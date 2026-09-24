<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use VmEngine\Pm\Factories\ProjectInvoiceFactory;
use VmEngine\Pm\Models\Concerns\ResolvesUserModel;
use VmEngine\Synapse\Traits\WithDeleteToken;
use VmEngine\SynAuth\Models\User;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

class ProjectInvoice extends Model
{
    use HasCreator;
    /** @use HasFactory<ProjectInvoiceFactory> */
    use HasFactory;

    use HasUpdater;

    use ResolvesUserModel;
    use WithDeleteToken;

    protected $fillable = [
        'project_id',
        'project_category_id',
        'client_id',
        'user_id',
        'invoice_number',
        'currency',
        'spk_document_id',
        'is_external_spk',
        'spk_number',
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
        'exchange_rate',
        'amount_idr',
        'vat_amount_idr',
        'dpp_amount_idr',
        'discount_amount_idr',
        'total_amount_idr',
        'is_paid',
        'paid_at',
        'payment_notes',
        'payment_proof',
        'issuer_id',
        'bank_account_id',
        'has_signature',
        'signature_id',
        'invoice_document_id',
        'invoice_document_en_id',
        'receipt_number',
        'receipt_amount_text',
        'receipt_document_id',
        'receipt_document_en_id',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'due_date' => 'date',
            'currency' => 'string',
            'is_external_spk' => 'boolean',
            'amount' => 'decimal:2',
            'include_vat' => 'boolean',
            'vat_percentage' => 'decimal:2',
            'vat_amount' => 'decimal:2',
            'include_discount' => 'boolean',
            'discount_percentage' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'include_dpp' => 'boolean',
            'dpp_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'exchange_rate' => 'decimal:6',
            'amount_idr' => 'decimal:2',
            'vat_amount_idr' => 'decimal:2',
            'dpp_amount_idr' => 'decimal:2',
            'discount_amount_idr' => 'decimal:2',
            'total_amount_idr' => 'decimal:2',
            'is_paid' => 'boolean',
            'paid_at' => 'datetime',
            'has_signature' => 'boolean',
        ];
    }

    protected static function newFactory(): ProjectInvoiceFactory
    {
        return ProjectInvoiceFactory::new();
    }

    /**
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return BelongsTo<ProjectCategory, $this>
     */
    public function projectCategory(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class);
    }

    /**
     * @return BelongsTo<Client, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(static::userModel());
    }

    /**
     * @return BelongsTo<ProjectDocument, $this>
     */
    public function spkDocument(): BelongsTo
    {
        return $this->belongsTo(ProjectDocument::class, 'spk_document_id');
    }

    /**
     * @return BelongsTo<DocumentIssuer, $this>
     */
    public function issuer(): BelongsTo
    {
        return $this->belongsTo(DocumentIssuer::class);
    }

    /**
     * @return BelongsTo<BankAccount, $this>
     */
    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    /**
     * @return BelongsTo<Signature, $this>
     */
    public function signature(): BelongsTo
    {
        return $this->belongsTo(Signature::class);
    }

    /**
     * @return HasMany<ProjectInvoiceItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(ProjectInvoiceItem::class);
    }

    /**
     * @return BelongsTo<ProjectDocument, $this>
     */
    public function invoiceDocument(): BelongsTo
    {
        return $this->belongsTo(ProjectDocument::class, 'invoice_document_id');
    }

    /**
     * @return BelongsTo<ProjectDocument, $this>
     */
    public function receiptDocument(): BelongsTo
    {
        return $this->belongsTo(ProjectDocument::class, 'receipt_document_id');
    }

    /**
     * @return BelongsTo<ProjectDocument, $this>
     */
    public function invoiceDocumentEn(): BelongsTo
    {
        return $this->belongsTo(ProjectDocument::class, 'invoice_document_en_id');
    }

    /**
     * @return BelongsTo<ProjectDocument, $this>
     */
    public function receiptDocumentEn(): BelongsTo
    {
        return $this->belongsTo(ProjectDocument::class, 'receipt_document_en_id');
    }
}
