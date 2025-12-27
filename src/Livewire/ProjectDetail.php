<?php

declare(strict_types=1);

namespace VmEngine\Pm\Livewire;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\Computed;
use Livewire\Component;
use VmEngine\Pm\Models\Project;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;

class ProjectDetail extends Component
{
    public Project $project;

    public string $activeTab = 'overview';

    public string $documentSearch = '';

    public string $filterDocType = '';

    public bool $showUploadModal = false;

    public function mount(int $id)
    {
        try {
            $this->project = Project::with([
                'division',
                'client',
                'user',
                'projectManager',
                'creator',
                'updater',
            ])->findOrFail($id);
        } catch (ModelNotFoundException) {
            session()->flash('danger', __('pm::messages.project_not_found'));
            $this->redirectRoute(backend_route('pm.project.index', nameOnly: true), navigate: true);
        }
    }

    public function setActiveTab(string $tab)
    {
        $this->activeTab = $tab;
    }

    #[Computed()]
    public function contractDocuments()
    {
        // Mock data for contract documents (versioned)
        // Replace with actual ProjectDocument query when implemented
        return collect($this->getMockDocuments('contract'));
    }

    #[Computed()]
    public function technicalDocuments()
    {
        // Mock data for technical documents (versioned)
        // Replace with actual ProjectDocument query when implemented
        return collect($this->getMockDocuments('technical'));
    }

    #[Computed()]
    public function financialDocuments()
    {
        // Mock data for financial documents (versioned)
        // Replace with actual ProjectDocument query when implemented
        return collect($this->getMockDocuments('financial'));
    }

    #[Computed()]
    public function deliverableDocuments()
    {
        // Mock data for deliverable documents (multiple files)
        // Replace with actual ProjectDocument query when implemented
        return collect($this->getMockDocuments('deliverable'));
    }

    #[Computed()]
    public function backupDocuments()
    {
        // Mock data for backup documents (multiple files)
        // Replace with actual ProjectDocument query when implemented
        return collect($this->getMockDocuments('backup'));
    }

    /**
     * Get mock documents for UI demonstration
     * TODO: Remove this method when implementing actual document storage
     */
    private function getMockDocuments(string $type): array
    {
        $UserModel = config('auth.providers.users.model');
        $mockUser = (object) ['name' => auth()->user()->name ?? 'John Doe'];

        $mockDocuments = [
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

        return $mockDocuments[$type] ?? [];
    }

    #[Computed()]
    public function projectTimeline()
    {
        // Placeholder for future activity log
        // Will integrate with spatie/laravel-activitylog when implemented
        return collect([]);
    }

    #[Computed()]
    public function financialSummary()
    {
        $budget = $this->project->budget ?? 0;
        $nominal = $this->project->nominal ?? 0;
        $downPayment = $this->project->down_payment ?? 0;

        return [
            'budget' => $budget,
            'nominal' => $nominal,
            'down_payment' => $downPayment,
            'remaining' => $nominal - $downPayment,
            'budget_variance' => $budget - $nominal,
            'budget_percentage' => $budget > 0 ? round(($nominal / $budget) * 100, 2) : 0,
            'payment_percentage' => $nominal > 0 ? round(($downPayment / $nominal) * 100, 2) : 0,
        ];
    }

    #[Computed()]
    public function teamMembers()
    {
        if (empty($this->project->assigned_team)) {
            return collect([]);
        }

        $UserModel = config('auth.providers.users.model');

        return $UserModel::whereIn('id', $this->project->assigned_team)->get();
    }

    /**
     * Placeholder methods for document operations
     * TODO: Implement these methods when adding document management
     */
    public function uploadDocument(string $type): void
    {
        // TODO: Implement document upload
        // $this->showUploadModal = true;
        session()->flash('info', 'Document upload functionality will be implemented soon');
    }

    public function downloadDocument(int $documentId): void
    {
        // TODO: Implement document download
        session()->flash('info', 'Document download functionality will be implemented soon');
    }

    public function uploadNewVersion(int $documentId): void
    {
        // TODO: Implement new version upload
        session()->flash('info', 'Version upload functionality will be implemented soon');
    }

    public function downloadVersion(int $versionId): void
    {
        // TODO: Implement version download
        session()->flash('info', 'Version download functionality will be implemented soon');
    }

    public function restoreVersion(int $versionId): void
    {
        // TODO: Implement version restore
        session()->flash('info', 'Version restore functionality will be implemented soon');
    }

    public function confirmDeleteDocument(int $documentId): void
    {
        // TODO: Implement document deletion
        session()->flash('info', 'Document deletion functionality will be implemented soon');
    }

    public function viewDocument(int $documentId): void
    {
        // TODO: Implement document view/preview
        session()->flash('info', 'Document view functionality will be implemented soon');
    }

    public function render()
    {
        $breadcrumbs = Breadcrumbs::make(
            label: __('pm::menu.be.parent'),
            icon: 'fa-solid fa-list',
        )->add(
            label: __('pm::project.list_title'),
            url: backend_route('pm.project.index'),
            icon: 'fa-solid fa-diagram-project'
        )->add(
            label: $this->project->name,
            icon: 'fa-solid fa-eye'
        );

        synav()->setActiveMenu('pm.project.index');

        return view('pm::livewire.project-detail', [
            'breadcrumbs' => $breadcrumbs,
        ])->title(__('pm::project.detail_title').': '.$this->project->name);
    }
}
