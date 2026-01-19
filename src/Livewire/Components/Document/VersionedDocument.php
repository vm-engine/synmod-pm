<?php

declare(strict_types=1);

namespace VmEngine\Pm\Livewire\Components\Document;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectDocument;
use VmEngine\Pm\Services\Documents\Base;
use VmEngine\SynAuth\Facades\SynAuth;

class VersionedDocument extends Component
{
    use WithFileUploads;

    public Project $project;

    public int $documentTypeValue;

    public string $title;

    public string $badge = 'Versioned';

    public bool $readonly = false;

    public bool $revisionMode = false;

    public $file;

    public string $documentCode = '';

    public string $notes = '';

    public int $version = 1;

    public function mount(Project $project, Documents $documentType, string $title, bool $readonly = false): void
    {
        $this->project = $project;
        $this->documentTypeValue = $documentType->value;
        $this->title = $title;
        $this->readonly = $readonly;

        $this->resetUploadForm();
    }

    /**
     * Get the document type enum instance.
     */
    protected function getDocumentType(): ?Documents
    {
        return Documents::tryFrom($this->documentTypeValue);
    }

    /**
     * Get the document handler singleton instance.
     */
    protected function getDocumentHandler(): Base
    {
        return $this->getDocumentType()->handler();
    }

    /**
     * Check if the current user is authorized to access documents.
     */
    protected function canAccessDocument(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        // Dev role has full access
        if ($user->isDev()) {
            return true;
        }

        // Get user's highest role level
        $userRoles = $user->roles()->get();
        $highestLevel = $userRoles->min('level'); // Lower level = higher privilege

        // Admin or higher (level < 10)
        if ($highestLevel < 10) {
            return true;
        }

        // Client role with matching client_id
        if ($user->hasRole('client')) {
            // Check if user's client matches the project's client
            $client = \VmEngine\Pm\Models\Client::where('user_id', $user->id)->first();

            return $client && $client->id === $this->project->client_id;
        }

        return false;
    }

    #[Computed()]
    public function documents()
    {
        $documentType = $this->getDocumentType();
        if (! $documentType) {
            return collect();
        }

        return $this->project->documents()
            ->byType($documentType)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function resetUploadForm(): void
    {
        $this->revisionMode = false;
        $this->file = null;
        $this->documentCode = $this->getDocumentHandler()
            ->generateDocumentNumber($this->project, $this->getDocumentType());
        $this->notes = '';
        if (count($this->documents()) > 0) {
            $this->version = $this->documents()[0]->version + 1;
        }
        $this->resetValidation();
    }

    public function saveDocument(): void
    {
        // Validation rules will be added here
        $this->validate([
            'file' => [
                'required',
                File::types(['doc', 'docx', 'pdf', 'xls', 'xlsx', 'ppt', 'pptx'])
                    ->max(10240),
            ],
            'documentCode' => 'required|string|max:100',
        ]);

        $documentType = $this->getDocumentType();

        $data = [
            'client_id' => $this->project->client_id,
            'user_id' => Auth::id(),
            'type' => $documentType,
            'number' => $this->documentCode,
            'notes' => $this->notes,
            'version' => $this->version,
            'file_path' => $this->file->store('projects/'.$this->project->id.'/documents/'.($documentType->code() ?: $documentType->value), 'local'),
            'file_info' => [
                'original_name' => $this->file->getClientOriginalName(),
                'size' => $this->file->getSize(),
                'mime_type' => $this->file->getMimeType(),
            ],
            // Additional fields as necessary
        ];

        $this->project->documents()->create($data);

        // Call uploaded hook and increment index
        $this->getDocumentHandler()->uploaded($this->project, $documentType);
        $this->getDocumentHandler()->incrementIndex($this->project, $documentType);

        SynAuth::logActivity(
            Auth::id(),
            'pm.project.documents.uploaded',
            'User '.Auth::user()->name.' uploaded '.$documentType->label().' document. Data: '.json_encode($data),
            'project.'.$this->project->id.'.documents',
            'pm',
        );

        $this->dispatch('notify', [
            'variant' => 'success',
            'title' => 'Document Uploaded',
            'message' => 'Document has been uploaded successfully',
        ]);

        // Dispatch event to refresh parent DocumentManager component
        $this->dispatch('document-uploaded');
        $this->dispatch('activity-refresh');

        // Close modal and reset form
        $this->dispatch('close-modal-upload-'.$documentType->code(true));
        $this->resetUploadForm();
    }

    public function downloadDocument(int $documentId): void
    {
        // Check authorization
        if (! $this->canAccessDocument()) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Unauthorized',
                'message' => 'You do not have permission to download this document',
            ]);

            return;
        }

        $document = $this->project->documents()->find($documentId);

        if (! $document) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => 'Document not found',
            ]);

            return;
        }

        if (! Storage::exists($document->file_path)) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => 'File not found on storage',
            ]);

            return;
        }

        // Redirect to document download route
        $this->redirect(route('backend.pm.document.download', $document->id));
    }

    public function uploadNewVersion(int $documentId): void
    {
        $documentData = $this->project->documents()->find($documentId);
        if (! $documentData) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => 'Document not found',
            ]);

            return;
        }

        // Set revision mode and pre-fill form with existing document data
        $this->revisionMode = true;
        $this->documentCode = $documentData->number;
        $this->notes = $documentData->notes ?? '';

        // Calculate next version number
        $latestVersion = $this->project->documents()
            ->byType($documentData->type)
            ->where('number', $documentData->number)
            ->max('version');
        $this->version = $latestVersion + 1;

        $this->dispatch('open-modal-upload-'.$documentData->type->code(true));
    }

    public function downloadVersion(int $versionId): void
    {
        $this->downloadDocument($versionId);
    }

    public function restoreVersion(int $versionId): void
    {
        $oldDocument = $this->project->documents()->find($versionId);

        if (! $oldDocument) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => 'Document version not found',
            ]);

            return;
        }

        if (! Storage::exists($oldDocument->file_path)) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => 'Original file not found on storage',
            ]);

            return;
        }

        // Calculate next version number
        $latestVersion = $this->project->documents()
            ->byType($oldDocument->type)
            ->where('number', $oldDocument->number)
            ->max('version');

        // Copy the old file to a new location
        $newFileName = 'projects/'.$this->project->id.'/documents/'.$oldDocument->type->code().'/'.uniqid().'-restored.'.pathinfo($oldDocument->file_path, PATHINFO_EXTENSION);
        Storage::copy($oldDocument->file_path, $newFileName);

        // Create new document version
        $this->project->documents()->create([
            'client_id' => $this->project->client_id,
            'user_id' => Auth::id(),
            'type' => $oldDocument->type,
            'number' => $oldDocument->number,
            'notes' => 'Restored from version '.$oldDocument->version.'. '.$oldDocument->notes,
            'version' => $latestVersion + 1,
            'file_path' => $newFileName,
            'file_info' => $oldDocument->file_info,
        ]);

        $this->dispatch('notify', [
            'variant' => 'success',
            'title' => 'Version Restored',
            'message' => 'Document version '.$oldDocument->version.' has been restored as version '.($latestVersion + 1),
        ]);

        // Dispatch event to refresh parent DocumentManager component
        $this->dispatch('document-uploaded');
    }

    public function confirmDeleteDocument(string $deleteToken): void
    {
        try {
            $id = ProjectDocument::validateDeleteToken($deleteToken);
            if (! $id) {
                throw new \Exception('Invalid delete token');
            }

            $document = ProjectDocument::findOrFail($id);

            // Check if user has permission to delete this document
            if ($document->project_id !== $this->project->id) {
                throw new \Exception('Unauthorized to delete this document');
            }

            // Delete file from storage
            if (Storage::exists($document->file_path)) {
                Storage::delete($document->file_path);
            }

            // Delete document record
            $document->delete();

            $this->dispatch('synapse-confirmed');
            $this->dispatch('activity-refresh');

            $this->dispatch('notify', [
                'variant' => 'success',
                'title' => 'Document Deleted',
                'message' => 'Document has been deleted successfully',
            ]);

            // Dispatch event to refresh parent DocumentManager component
            $this->dispatch('document-uploaded');
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function viewDocument(int $documentId): void
    {
        // Check authorization
        if (! $this->canAccessDocument()) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Unauthorized',
                'message' => 'You do not have permission to view this document',
            ]);

            abort(403, 'Unauthorized to view this document');
        }

        $document = $this->project->documents()->find($documentId);

        if (! $document) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => 'Document not found',
            ]);

            return;
        }

        if (! Storage::exists($document->file_path)) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => 'File not found on storage',
            ]);

            return;
        }

        // Redirect to document print route
        $this->redirect(route('backend.pm.document.print', $document->id));
    }

    public function render()
    {
        return view('pm::livewire.components.document.versioned-document', [
            'documentType' => $this->getDocumentType(),
        ]);
    }
}
