<?php

declare(strict_types=1);

namespace VmEngine\Pm\Livewire\Components\Document;

use Exception;
use Illuminate\Validation\Rules\File;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectDocument;
use VmEngine\Pm\Services\Documents\Base;
use VmEngine\Pm\Services\ProjectDocumentService;

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
        $documentService = app(ProjectDocumentService::class);

        return $documentService->canAccessDocument($this->project);
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
        $this->validate([
            'file' => [
                'required',
                File::types(['doc', 'docx', 'pdf', 'xls', 'xlsx', 'ppt', 'pptx'])
                    ->max(10240),
            ],
            'documentCode' => 'required|string|max:100',
        ]);

        $documentType = $this->getDocumentType();
        $documentService = app(ProjectDocumentService::class);

        $documentService->uploadDocument(
            $this->project,
            $documentType,
            $this->file,
            $this->documentCode,
            $this->notes,
            $this->version
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
        try {
            $documentService = app(ProjectDocumentService::class);
            $document = $documentService->validateDocumentAccess($this->project, $documentId);

            // Redirect to document download route
            $this->redirect(route('backend.pm.document.download', $document->id));
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => $e->getMessage(),
            ]);
        }
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
        try {
            $oldDocument = $this->project->documents()->find($versionId);

            if (! $oldDocument) {
                throw new Exception('Document version not found');
            }

            $documentService = app(ProjectDocumentService::class);
            $newDocument = $documentService->restoreVersion($this->project, $oldDocument);

            $this->dispatch('notify', [
                'variant' => 'success',
                'title' => 'Version Restored',
                'message' => 'Document version '.$oldDocument->version.' has been restored as version '.$newDocument->version,
            ]);

            // Dispatch event to refresh parent DocumentManager component
            $this->dispatch('document-uploaded');
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function confirmDeleteDocument(string $deleteToken): void
    {
        try {
            $id = ProjectDocument::validateDeleteToken($deleteToken);
            if (! $id) {
                throw new Exception('Invalid delete token');
            }

            $document = ProjectDocument::findOrFail($id);

            // Check if user has permission to delete this document
            if ($document->project_id !== $this->project->id) {
                throw new Exception('Unauthorized to delete this document');
            }

            $documentService = app(ProjectDocumentService::class);
            $documentService->deleteDocument($document);

            $this->dispatch('synapse-confirmed');
            $this->dispatch('activity-refresh');

            $this->dispatch('notify', [
                'variant' => 'success',
                'title' => 'Document Deleted',
                'message' => 'Document has been deleted successfully',
            ]);

            // Dispatch event to refresh parent DocumentManager component
            $this->dispatch('document-uploaded');
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function viewDocument(int $documentId): void
    {
        try {
            $documentService = app(ProjectDocumentService::class);
            $document = $documentService->validateDocumentAccess($this->project, $documentId);

            // Redirect to document print route
            $this->redirect(route('backend.pm.document.print', $document->id));
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => $e->getMessage(),
            ]);

            if ($e->getMessage() === 'You do not have permission to access this document') {
                abort(403, $e->getMessage());
            }
        }
    }

    public function render()
    {
        return view('pm::livewire.components.document.versioned-document', [
            'documentType' => $this->getDocumentType(),
        ]);
    }
}
