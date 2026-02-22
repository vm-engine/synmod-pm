<?php

declare(strict_types=1);

use Livewire\Attributes\On;
use Livewire\Component;
use VmEngine\Pm\Models\Project;

new class extends Component
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
};
