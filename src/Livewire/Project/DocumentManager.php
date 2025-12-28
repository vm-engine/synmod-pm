<?php

declare(strict_types=1);

namespace VmEngine\Pm\Livewire\Project;

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

    public function render()
    {
        return view('pm::livewire.project.document-manager');
    }
}
