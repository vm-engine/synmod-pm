<?php

declare(strict_types=1);

namespace VmEngine\Pm\Livewire\Components\Document;

use Livewire\Attributes\Computed;
use Livewire\Component;
use VmEngine\Pm\Models\Project;

class VersionedDocument extends Component
{
    public Project $project;

    public string $documentType;

    public string $title;

    public string $badge = 'Versioned';

    public function mount(Project $project, string $documentType, string $title, string $badge = 'Versioned'): void
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
        //     ->with(['versions', 'uploader'])
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
            'contract' => [
                (object) [
                    'id' => 1,
                    'title' => 'Project Agreement 2025',
                    'description' => 'Main contract agreement for the project',
                    'file_extension' => 'pdf',
                    'current_version' => 3,
                    'updated_at' => now()->subDays(5),
                    'uploader' => $mockUser,
                    'file_size_formatted' => '2.5 MB',
                    'versions_count' => 3,
                    'versions' => [
                        (object) [
                            'id' => 3,
                            'version_number' => 3,
                            'original_filename' => 'contract_v3_final.pdf',
                            'uploaded_at' => now()->subDays(5),
                            'uploader' => $mockUser,
                            'file_size_formatted' => '2.5 MB',
                            'is_current' => true,
                            'change_notes' => 'Updated payment terms and timeline',
                        ],
                        (object) [
                            'id' => 2,
                            'version_number' => 2,
                            'original_filename' => 'contract_v2_revised.pdf',
                            'uploaded_at' => now()->subDays(15),
                            'uploader' => $mockUser,
                            'file_size_formatted' => '2.3 MB',
                            'is_current' => false,
                            'change_notes' => 'Revised scope of work',
                        ],
                        (object) [
                            'id' => 1,
                            'version_number' => 1,
                            'original_filename' => 'contract_v1_draft.pdf',
                            'uploaded_at' => now()->subDays(30),
                            'uploader' => $mockUser,
                            'file_size_formatted' => '2.1 MB',
                            'is_current' => false,
                            'change_notes' => null,
                        ],
                    ],
                ],
            ],
            'technical' => [
                (object) [
                    'id' => 2,
                    'title' => 'Scope of Work (SOW)',
                    'description' => 'Detailed project scope and requirements',
                    'file_extension' => 'docx',
                    'current_version' => 2,
                    'updated_at' => now()->subDays(10),
                    'uploader' => $mockUser,
                    'file_size_formatted' => '1.8 MB',
                    'versions_count' => 2,
                    'versions' => [
                        (object) [
                            'id' => 4,
                            'version_number' => 2,
                            'original_filename' => 'SOW_v2.docx',
                            'uploaded_at' => now()->subDays(10),
                            'uploader' => $mockUser,
                            'file_size_formatted' => '1.8 MB',
                            'is_current' => true,
                            'change_notes' => 'Added module breakdown',
                        ],
                        (object) [
                            'id' => 5,
                            'version_number' => 1,
                            'original_filename' => 'SOW_v1.docx',
                            'uploaded_at' => now()->subDays(25),
                            'uploader' => $mockUser,
                            'file_size_formatted' => '1.5 MB',
                            'is_current' => false,
                            'change_notes' => null,
                        ],
                    ],
                ],
            ],
            'financial' => [
                (object) [
                    'id' => 3,
                    'title' => 'Invoice #INV-2025-001',
                    'description' => 'Down payment invoice',
                    'file_extension' => 'pdf',
                    'current_version' => 1,
                    'updated_at' => now()->subDays(7),
                    'uploader' => $mockUser,
                    'file_size_formatted' => '450 KB',
                    'versions_count' => 1,
                    'versions' => [
                        (object) [
                            'id' => 6,
                            'version_number' => 1,
                            'original_filename' => 'invoice_001.pdf',
                            'uploaded_at' => now()->subDays(7),
                            'uploader' => $mockUser,
                            'file_size_formatted' => '450 KB',
                            'is_current' => true,
                            'change_notes' => null,
                        ],
                    ],
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

    public function uploadNewVersion(int $documentId): void
    {
        // TODO: Implement new version upload
        $this->dispatch('notify', [
            'variant' => 'info',
            'title' => 'Coming Soon',
            'message' => 'Version upload functionality will be implemented soon',
        ]);
    }

    public function downloadVersion(int $versionId): void
    {
        // TODO: Implement version download
        $this->dispatch('notify', [
            'variant' => 'info',
            'title' => 'Coming Soon',
            'message' => 'Version download functionality will be implemented soon',
        ]);
    }

    public function restoreVersion(int $versionId): void
    {
        // TODO: Implement version restore
        $this->dispatch('notify', [
            'variant' => 'info',
            'title' => 'Coming Soon',
            'message' => 'Version restore functionality will be implemented soon',
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
        return view('pm::livewire.components.document.versioned-document');
    }
}
