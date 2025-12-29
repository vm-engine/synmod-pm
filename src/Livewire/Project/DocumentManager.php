<?php

declare(strict_types=1);

namespace VmEngine\Pm\Livewire\Project;

use Livewire\Attributes\On;
use Livewire\Component;
use VmEngine\Pm\Models\Project;

class DocumentManager extends Component
{
    public Project $project;

    public string $documentSearch = '';

    public string $filterDocType = '';

    public function mount(Project $project): void
    {
        $this->project = $project;
    }

    #[On('document-uploaded')]
    public function refreshDocuments(): void
    {
        // Refresh the component when a document is uploaded
        $this->project->refresh();
    }

    public function render()
    {
        return view('pm::livewire.project.document-manager');
    }
}
