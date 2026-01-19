<?php

declare(strict_types=1);

namespace VmEngine\Pm\Livewire\Components\Document;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectDocument;
use VmEngine\Pm\Services\Documents\Base;
use VmEngine\SynAuth\Facades\SynAuth;

class MultipleDocument extends Component
{
    use WithFileUploads;

    public Project $project;

    public int $documentTypeValue;

    public string $title;

    public string $badge;

    public bool $readonly = false;

    public $file;

    public string $documentCode = '';

    public string $notes = '';

    public function mount(Project $project, Documents $documentType, string $title, bool $readonly = false): void
    {
        $this->project = $project;
        $this->documentTypeValue = $documentType->value;
        $this->title = $title;
        $this->badge = __('pm::project_document.add_new_file');
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
        $this->file = null;
        $this->documentCode = $this->getDocumentHandler()
            ->generateDocumentNumber($this->project, $this->getDocumentType());
        $this->notes = '';
        $this->resetValidation();
    }

    public function saveDocument(): void
    {
        $this->validate([
            'file' => [
                'required',
                File::types(['doc', 'docx', 'pdf', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar'])
                    ->max(10240),
            ],
            'documentCode' => 'required|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $documentType = $this->getDocumentType();

        $data = [
            'client_id' => $this->project->client_id,
            'user_id' => Auth::id(),
            'type' => $documentType,
            'number' => $this->documentCode,
            'notes' => $this->notes,
            'version' => 1, // Multiple documents always version 1
            'file_path' => $this->file->storePublicly('projects/'.$this->project->id.'/documents/'.($documentType->code() ?: $documentType->value)),
            'file_info' => [
                'original_name' => $this->file->getClientOriginalName(),
                'size' => $this->file->getSize(),
                'mime_type' => $this->file->getMimeType(),
            ],
        ];

        $this->project->documents()->create($data);

        // Call uploaded hook
        $this->getDocumentHandler()->uploaded($this->project, $documentType);
        $this->getDocumentHandler()->incrementIndex($this->project, $documentType);

        SynAuth::logActivity(
            Auth::id(),
            'pm.project.documents.uploaded',
            'User '.Auth::user()->name.' uploaded '.$documentType->label().' document. Data: '.json_encode($data),
            'project.documents',
            'pm',
        );

        $this->dispatch('notify', [
            'variant' => 'success',
            'title' => 'File Uploaded',
            'message' => 'File has been uploaded successfully',
        ]);

        // Dispatch event to refresh parent DocumentManager component
        $this->dispatch('document-uploaded');

        // Close modal and reset form
        $this->dispatch('close-modal-upload-'.$documentType->code(true));
        $this->resetUploadForm();
    }

    public function downloadDocument(int $documentId): StreamedResponse
    {
        // Check authorization
        if (! $this->canAccessDocument()) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Unauthorized',
                'message' => 'You do not have permission to download this document',
            ]);

            abort(403, 'Unauthorized to download this document');
        }

        $document = $this->project->documents()->find($documentId);

        if (! $document) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => 'Document not found',
            ]);

            abort(404);
        }

        if (! Storage::exists($document->file_path)) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => 'File not found on storage',
            ]);

            abort(404);
        }

        return Storage::download($document->file_path, $document->file_info['original_name'] ?? 'document');
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
            $this->dispatch('notify', [
                'variant' => 'success',
                'title' => 'File Deleted',
                'message' => 'File has been deleted successfully',
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

        // Get temporary URL for viewing (valid for 5 minutes)
        $url = Storage::temporaryUrl($document->file_path, now()->addMinutes(5));

        // Open in new tab
        $this->dispatch('open-url', url: $url);
    }

    public function render()
    {
        return view('pm::livewire.components.document.multiple-document', [
            'documentType' => $this->getDocumentType(),
        ]);
    }
}
