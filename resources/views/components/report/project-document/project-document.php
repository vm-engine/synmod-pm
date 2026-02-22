<?php

declare(strict_types=1);

use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectCategory;
use VmEngine\Pm\Services\ProjectDocumentService;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;

new class extends Component
{
    #[Url()]
    public ?int $year = null;

    #[Url(except: null)]
    public ?int $documentType = null;

    #[Url()]
    public array $projectCategories = [];

    public function mount(): void
    {
        // Cast projectCategories to integers if they come from URL as strings
        $this->projectCategories = array_map('intval', $this->projectCategories);

        synav()->setActiveMenu('pm.report.project_document');
    }

    public function title(): string
    {
        return page_title(__('pm::report.documents.title'));
    }

    public function clearFilters(): void
    {
        $this->year = (int) date('Y');
        $this->documentType = null;
        $this->projectCategories = [];
    }

    #[Computed()]
    public function list()
    {
        if (! $this->year) {
            $this->year = (int) date('Y');
        }

        $projectList = Project::query()->with(['client', 'projectCategory'])->where('year', $this->year);
        if (! is_null($this->documentType) && ($document = Documents::tryFrom($this->documentType))) {
            $closureFilter = function ($query) use ($document) {
                if (! is_null($document)) {
                    $query->where('type', $document->value);
                }
            };
            $projectList = $projectList
                ->with(['documents' => $closureFilter])
                ->whereHas('documents', $closureFilter);
        }

        if (count($this->projectCategories) > 0) {
            $projectList = $projectList->whereIn('project_category_id', $this->projectCategories);
        }

        return $projectList->get();
    }

    public function exportToExcel(ProjectDocumentService $service)
    {
        $year = $this->year ?? (int) date('Y');
        $documentType = ! is_null($this->documentType) ? Documents::tryFrom($this->documentType) : null;

        return $service->exportToExcel($year, $documentType, $this->projectCategories);
    }

    #[Computed()]
    public function documents(): array
    {
        return collect(Documents::cases())->map(function ($document) {
            return [
                'value' => $document->value,
                'label' => $document->label(),
            ];
        })->toArray();
    }

    #[Computed()]
    public function projectCategoryOptions(): array
    {
        return ProjectCategory::all()->map(function ($category) {
            return [
                'value' => $category->id,
                'label' => $category->name,
            ];
        })->toArray();
    }

    #[Computed()]
    public function document(): ?Documents
    {
        return ! is_null($this->documentType) ? Documents::tryFrom($this->documentType) : null;
    }

    #[Computed()]
    public function breadcrumbs(): Breadcrumbs
    {
        return Breadcrumbs::make(
            label: __('pm::menu.be.parent'),
            icon: 'fa-solid fa-list',
        )->add(
            label: __('pm::menu.be.report.index'),
            icon: 'fa-solid fa-chart-line',
        )->add(
            label: __('pm::menu.be.report.project_document'),
            icon: 'fa-solid fa-diagram-project',
        );
    }
};
