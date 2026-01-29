<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectDocument;
use VmEngine\Synapse\Services\Excel\ExcelExporter;
use VmEngine\SynAuth\Facades\SynAuth;

class ProjectDocumentService
{
    /**
     * Upload a new document.
     */
    public function uploadDocument(
        Project $project,
        Documents $documentType,
        TemporaryUploadedFile $file,
        string $documentCode,
        ?string $notes = null,
        int $version = 1
    ): ProjectDocument {
        $data = [
            'client_id' => $project->client_id,
            'user_id' => Auth::id(),
            'type' => $documentType,
            'number' => $documentCode,
            'notes' => $notes,
            'version' => $version,
            'file_path' => $file->store(
                'projects/'.$project->id.'/documents/'.($documentType->code() ?: $documentType->value),
                'local'
            ),
            'file_info' => [
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ],
        ];

        $document = $project->documents()->create($data);

        // Call uploaded hook and increment index
        $handler = $documentType->handler();
        $handler->uploaded($project, $documentType);
        $handler->incrementIndex($project, $documentType);

        // Log activity
        $this->logActivity('uploaded', $project, $documentType, $data);

        return $document;
    }

    /**
     * Delete a document and its file.
     */
    public function deleteDocument(ProjectDocument $document): bool
    {
        $documentData = $document->toArray();

        // Delete file from storage
        if (Storage::exists($document->file_path)) {
            Storage::delete($document->file_path);
        }

        $deleted = $document->delete();

        // Log activity
        SynAuth::logActivity(
            Auth::id(),
            'pm.project.'.$document->project_id.'.documents.deleted',
            'User '.Auth::user()->name.' deleted document. Data: '.json_encode($documentData),
            'project.'.$document->project_id.'.documents',
            'pm',
        );

        return $deleted;
    }

    /**
     * Restore an old document version as a new version.
     */
    public function restoreVersion(Project $project, ProjectDocument $oldDocument): ProjectDocument
    {
        if (! Storage::exists($oldDocument->file_path)) {
            throw new Exception('Original file not found on storage');
        }

        // Calculate next version number
        $latestVersion = $project->documents()
            ->byType($oldDocument->type)
            ->where('number', $oldDocument->number)
            ->max('version');

        // Copy the old file to a new location
        $newFileName = 'projects/'.$project->id.'/documents/'.$oldDocument->type->code().'/'.uniqid().'-restored.'.pathinfo($oldDocument->file_path, PATHINFO_EXTENSION);
        Storage::copy($oldDocument->file_path, $newFileName);

        // Create new document version
        $newDocument = $project->documents()->create([
            'client_id' => $project->client_id,
            'user_id' => Auth::id(),
            'type' => $oldDocument->type,
            'number' => $oldDocument->number,
            'notes' => 'Restored from version '.$oldDocument->version.'. '.$oldDocument->notes,
            'version' => $latestVersion + 1,
            'file_path' => $newFileName,
            'file_info' => $oldDocument->file_info,
        ]);

        // Log activity
        SynAuth::logActivity(
            Auth::id(),
            'pm.project.'.$project->id.'.documents.restored',
            'User '.Auth::user()->name.' restored document version '.$oldDocument->version.' as version '.($latestVersion + 1),
            'project.'.$project->id.'.documents',
            'pm',
        );

        return $newDocument;
    }

    /**
     * Check if the current user is authorized to access project documents.
     */
    public function canAccessDocument(Project $project): bool
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
            $client = Client::where('user_id', $user->id)->first();

            return $client && $client->id === $project->client_id;
        }

        return false;
    }

    /**
     * Validate document exists and user has access.
     */
    public function validateDocumentAccess(Project $project, int $documentId): ProjectDocument
    {
        if (! $this->canAccessDocument($project)) {
            throw new Exception('You do not have permission to access this document');
        }

        $document = $project->documents()->find($documentId);

        if (! $document) {
            throw new Exception('Document not found');
        }

        if (! Storage::exists($document->file_path)) {
            throw new Exception('File not found on storage');
        }

        return $document;
    }

    /**
     * Export project documents report to Excel.
     */
    public function exportToExcel(int $year, ?Documents $documentType = null, array $projectCategories = []): BinaryFileResponse
    {
        $query = Project::query()
            ->with(['client', 'projectCategory', 'documents.creator'])
            ->where('year', $year);

        if ($documentType) {
            $query->whereHas('documents', fn ($q) => $q->where('type', $documentType->value));
        }

        if (count($projectCategories) > 0) {
            $query->whereIn('project_category_id', $projectCategories);
        }

        $documentCol = __('pm::report.documents.table.document');
        $clientCol = __('pm::report.documents.table.client');
        $projectCol = __('pm::report.documents.table.project');
        $categoryCol = __('pm::report.documents.table.category');
        $statusCol = __('pm::report.documents.table.status');

        return ExcelExporter::make($query)
            ->columns([
                $documentCol => function ($project) use ($documentType) {
                    $docs = $project->documents;
                    if ($documentType) {
                        $docs = $docs->where('type', $documentType);
                    }

                    return $docs->map(fn ($doc) => ucwords($doc->type->name).': '.$doc->number.' ('.$doc->created_at->format('j M Y').' - '.$doc->creator->name.')')
                        ->implode("\n");
                },
                $clientCol => fn ($project) => $project->client?->company,
                $projectCol => 'name',
                $categoryCol => fn ($project) => $project->projectCategory?->name ?? __('pm::report.documents.table.uncategorized'),
                $statusCol => fn ($project) => $project->documents->contains(fn ($doc) => $doc->type === Documents::SPK)
                    ? __('pm::report.documents.status.deal')
                    : __('pm::report.documents.status.pending'),
            ])
            ->columnStyles([
                $documentCol => ['width' => 60, 'alignment' => ['wrapText' => true, 'vertical' => 'top']],
                $clientCol => ['width' => 30, 'alignment' => ['vertical' => 'top']],
                $projectCol => ['width' => 40, 'alignment' => ['vertical' => 'top']],
                $categoryCol => ['width' => 20, 'alignment' => ['horizontal' => 'center', 'vertical' => 'top']],
                $statusCol => ['width' => 12, 'alignment' => ['horizontal' => 'center', 'vertical' => 'top']],
            ])
            ->headerStyle([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4472C4']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ])
            ->sheet(__('pm::report.documents.title'))
            ->download('project-documents-'.$year.'.xlsx');
    }

    /**
     * Log document activity.
     */
    private function logActivity(string $action, Project $project, Documents $documentType, array $data): void
    {
        SynAuth::logActivity(
            Auth::id(),
            'pm.project.'.$project->id.'.documents.'.$action,
            'User '.Auth::user()->name.' '.$action.' '.$documentType->label().' document. Data: '.json_encode($data),
            'project.'.$project->id.'.documents',
            'pm',
        );
    }
}
