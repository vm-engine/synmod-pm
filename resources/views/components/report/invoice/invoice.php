<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Services\InvoiceService;
use VmEngine\Synapse\Enums\ExcelTaskStatus;
use VmEngine\Synapse\Models\ExcelExport;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;

new class extends Component
{
    #[Url()]
    public ?int $year = null;

    public string $pageName = '';

    public function mount(): void
    {
        if ($this->year === null || $this->year === 0) {
            $this->year = now()->year;
        }

        $this->pageName = __('pm::report.invoice.title', ['year' => $this->year]);
        synav()->setActiveMenu('pm.report.invoice');
    }

    public function title(): string
    {
        return __('pm::menu.be.report.invoice');
    }

    #[Computed()]
    public function statistics(): array
    {
        $invoiceService = app(InvoiceService::class);

        return $invoiceService->statistics($this->year);
    }

    #[Computed()]
    public function byCreator()
    {
        $invoiceService = app(InvoiceService::class);

        return $invoiceService->getByCreator($this->year);
    }

    #[Computed()]
    public function byProject()
    {
        $invoiceService = app(InvoiceService::class);

        return $invoiceService->getByProject($this->year);
    }

    #[Computed()]
    public function exportTask(): ?ExcelExport
    {
        $year = $this->year ?? now()->year;
        $reference = InvoiceService::exportReference($year, Auth::id());

        return ExcelExport::findByReference($reference);
    }

    #[Computed()]
    public function years(): array
    {
        return Project::query()
            ->distinct()
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->select(['year as label', 'year as value'])
            ->get()
            ->toArray();
    }

    public function exportToExcel(InvoiceService $service): void
    {
        $year = $this->year ?? now()->year;

        $service->exportToExcel($year);
        unset($this->exportTask);

        $this->dispatch('notify',
            variant: 'info',
            title: 'Info',
            message: __('pm::report.invoice.export.queued'),
        );
    }

    public function checkExportStatus(): void
    {
        unset($this->exportTask);
    }

    public function downloadExport()
    {
        $task = $this->exportTask;

        if (! $task || $task->status !== ExcelTaskStatus::Completed) {
            return;
        }

        if (! file_exists($task->output_path)) {
            $this->dispatch('notify',
                variant: 'error',
                title: 'Error',
                message: __('pm::report.invoice.export.file_not_found'),
            );

            return;
        }

        return response()->download($task->output_path, 'invoices-'.($this->year ?? now()->year).'.xlsx');
    }

    public function dismissExport(): void
    {
        $task = $this->exportTask;

        if ($task && $task->output_path && file_exists($task->output_path)) {
            @unlink($task->output_path);
        }

        $task?->delete();
        unset($this->exportTask);
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
            label: __('pm::menu.be.report.invoice'),
            icon: 'fa-solid fa-file-invoice-dollar',
        );
    }

    public function render()
    {
        return $this->view()->title(page_title($this->title()));
    }
};
