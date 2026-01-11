<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Factories\ProjectDocumentFactory;
use VmEngine\Synapse\Traits\WithDeleteToken;
use VmEngine\SynAuth\Traits\HasCreator;

class ProjectDocument extends Model
{
    use HasCreator;
    use HasFactory;
    use WithDeleteToken;

    protected $fillable = [
        'user_id',
        'client_id',
        'project_id',
        'type',
        'number',
        'notes',
        'file_path',
        'file_info',
        'version',
        'created_by',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return ProjectDocumentFactory::new();
    }

    protected static function booted(): void
    {
        parent::booted();

        static::deleting(function ($document) {
            if (Storage::exists($document->file_path)) {
                Storage::delete($document->file_path);
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'type' => Documents::class,
            'file_info' => 'array',
            'version' => 'integer',
        ];
    }

    /**
     * Get the user that owns this document.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    /**
     * Get the client for this document.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the project for this document.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the document type label.
     */
    public function getTypeLabel(): string
    {
        return $this->type->label();
    }

    /**
     * Get the document type code.
     */
    public function getTypeCode(bool $lowercase = false): string
    {
        return $this->type->code($lowercase);
    }

    /**
     * Check if document type has NC index.
     */
    public function hasNCIndex(): bool
    {
        return $this->type->hasNCIndex();
    }

    /**
     * Get the document handler.
     */
    public function getHandler()
    {
        return $this->type->handler();
    }

    /**
     * Scope to get documents by type.
     */
    public function scopeByType($query, Documents|int|null $type)
    {
        if ($type === null) {
            return $query;
        }

        $typeValue = $type instanceof Documents ? $type->value : $type;

        return $query->where('type', $typeValue);
    }

    /**
     * Scope to get documents by project.
     */
    public function scopeByProject($query, int $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope to get documents by client.
     */
    public function scopeByClient($query, int $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    /**
     * Scope to get latest version of documents.
     */
    public function scopeLatestVersion($query)
    {
        return $query->orderBy('version', 'desc');
    }
}
