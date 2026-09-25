<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Factories\ProjectDocumentFactory;
use VmEngine\Pm\Models\Concerns\ResolvesUserModel;
use VmEngine\Pm\Services\Documents\Base;
use VmEngine\Synapse\Traits\WithDeleteToken;
use VmEngine\SynAuth\Models\User;
use VmEngine\SynAuth\Traits\HasCreator;

/**
 * @property int $id
 * @property int $user_id
 * @property int $client_id
 * @property int $project_id
 * @property Documents $type
 * @property string $number
 * @property string|null $notes
 * @property string $file_path
 * @property array<array-key, mixed>|null $file_info
 * @property int $version
 * @property int|null $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ProjectDocument extends Model
{
    use HasCreator;

    /** @use HasFactory<ProjectDocumentFactory> */
    use HasFactory;

    use ResolvesUserModel;
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
    protected static function newFactory(): ProjectDocumentFactory
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
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(static::userModel());
    }

    /**
     * Get the client for this document.
     *
     * @return BelongsTo<Client, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the project for this document.
     *
     * @return BelongsTo<Project, $this>
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
    public function getHandler(): Base
    {
        return $this->type->handler();
    }

    /**
     * Scope to get documents by type.
     *
     * @param  Builder<ProjectDocument>  $query
     * @return Builder<ProjectDocument>
     */
    public function scopeByType(Builder $query, Documents|int|null $type): Builder
    {
        if ($type === null) {
            return $query;
        }

        $typeValue = $type instanceof Documents ? $type->value : $type;

        return $query->where('type', $typeValue);
    }

    /**
     * Scope to get documents by project.
     *
     * @param  Builder<ProjectDocument>  $query
     * @return Builder<ProjectDocument>
     */
    public function scopeByProject(Builder $query, int $projectId): Builder
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope to get documents by client.
     *
     * @param  Builder<ProjectDocument>  $query
     * @return Builder<ProjectDocument>
     */
    public function scopeByClient(Builder $query, int $clientId): Builder
    {
        return $query->where('client_id', $clientId);
    }

    /**
     * Scope to get latest version of documents.
     *
     * @param  Builder<ProjectDocument>  $query
     * @return Builder<ProjectDocument>
     */
    public function scopeLatestVersion(Builder $query): Builder
    {
        return $query->orderBy('version', 'desc');
    }
}
