<?php

declare(strict_types=1);

namespace VmEngine\Pm\Livewire\Components\Document;

use Livewire\Attributes\Computed;
use Livewire\Component;
use VmEngine\Pm\Models\Project;

class MultipleDocument extends Component
{
    public Project $project;

    public string $documentType;

    public string $title;

    public string $badge = 'Multiple Files';

    public function mount(Project $project, string $documentType, string $title, string $badge = 'Multiple Files'): void
    {
        $this->project = $project;
        $this->documentType = $documentType;
        $this->title = $title;
        $this->badge = $badge;
    }

    #[Computed()]
    public function documents()
    {
        // TODO: Replace with actual query when ProjectDocument model is implemented
        // return $this->project->documents()
        //     ->where('document_type', $this->documentType)
        //     ->with('uploader')
        //     ->get();

        return collect($this->getMockDocuments());
    }

    /**
     * Get mock documents for UI demonstration
     * TODO: Remove this method when implementing actual document storage
     */
    private function getMockDocuments(): array
    {
        $mockUser = (object) ['name' => auth()->user()->name ?? 'John Doe'];

        $mockData = [
            'deliverable' => [
                (object) [
                    'id' => 4,
                    'title' => 'User Guide v1.0',
                    'description' => 'Complete user manual for the application',
                    'file_extension' => 'pdf',
                    'created_at' => now()->subDays(3),
                    'uploader' => $mockUser,
                    'original_filename' => 'user_guide_v1.pdf',
                    'file_size_formatted' => '3.2 MB',
                ],
                (object) [
                    'id' => 5,
                    'title' => 'UAT Test Results',
                    'description' => 'User acceptance testing report',
                    'file_extension' => 'xlsx',
                    'created_at' => now()->subDays(5),
                    'uploader' => $mockUser,
                    'original_filename' => 'UAT_results.xlsx',
                    'file_size_formatted' => '850 KB',
                ],
                (object) [
                    'id' => 6,
                    'title' => 'Meeting Notes - Week 1',
                    'description' => null,
                    'file_extension' => 'docx',
                    'created_at' => now()->subDays(20),
                    'uploader' => $mockUser,
                    'original_filename' => 'meeting_notes_w1.docx',
                    'file_size_formatted' => '120 KB',
                ],
            ],
            'backup' => [
                (object) [
                    'id' => 7,
                    'title' => 'Source Code Backup - v1.0',
                    'description' => 'Complete source code archive',
                    'file_extension' => 'zip',
                    'created_at' => now()->subDays(2),
                    'uploader' => $mockUser,
                    'original_filename' => 'source_v1.0.zip',
                    'file_size_formatted' => '45.3 MB',
                ],
            ],
        ];

        return $mockData[$this->documentType] ?? [];
    }

    public function uploadDocument(): void
    {
        // TODO: Implement document upload
        $this->dispatch('notify', [
            'variant' => 'info',
            'title' => 'Coming Soon',
            'message' => 'Document upload functionality will be implemented soon',
        ]);
    }

    public function downloadDocument(int $documentId): void
    {
        // TODO: Implement document download
        $this->dispatch('notify', [
            'variant' => 'info',
            'title' => 'Coming Soon',
            'message' => 'Document download functionality will be implemented soon',
        ]);
    }

    public function confirmDeleteDocument(int $documentId): void
    {
        // TODO: Implement document deletion
        $this->dispatch('notify', [
            'variant' => 'info',
            'title' => 'Coming Soon',
            'message' => 'Document deletion functionality will be implemented soon',
        ]);
    }

    public function viewDocument(int $documentId): void
    {
        // TODO: Implement document view/preview
        $this->dispatch('notify', [
            'variant' => 'info',
            'title' => 'Coming Soon',
            'message' => 'Document view functionality will be implemented soon',
        ]);
    }

    public function render()
    {
        return view('pm::livewire.components.document.multiple-document');
    }
}
