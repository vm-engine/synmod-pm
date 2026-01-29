<?php

declare(strict_types=1);

namespace VmEngine\Pm\Livewire\Report;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Services\InvoiceService;
use VmEngine\Synapse\Enums\ExcelTaskStatus;
use VmEngine\Synapse\Models\ExcelExport;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;

class Invoice extends Component
{
    #[Url()]
    public ?int $year = null;

    public string $pageName = '';

    public ?int $exportTaskId = null;

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
        if (! $this->exportTaskId) {
            return null;
        }

        return ExcelExport::find($this->exportTaskId);
    }

    public function exportToExcel(InvoiceService $service): void
    {
        $year = $this->year ?? now()->year;

        $task = $service->exportToExcel($year);
        $this->exportTaskId = $task->id;

        $this->dispatch('notify', [
            'variant' => 'info',
            'title' => 'Info',
            'message' => __('pm::report.invoice.export.queued', ['id' => $task->id]),
        ]);
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
            $this->dispatch('notify', [
                'variant' => 'error',
                'title' => 'Error',
                'message' => __('pm::report.invoice.export.file_not_found'),
            ]);

            return;
        }

        $this->exportTaskId = null;

        return response()->download(
            $task->output_path,
            basename($task->output_path)
        )->deleteFileAfterSend();
    }

    public function dismissExport(): void
    {
        $this->exportTaskId = null;
    }

    public function render()
    {
        if ($this->year === null || $this->year === 0) {
            $this->year = now()->year;
        }

        $this->pageName = __('pm::report.invoice.title', ['year' => $this->year]);
        $breadcrumbs = Breadcrumbs::make(
            label: __('pm::menu.be.parent'),
            icon: 'fa-solid fa-list',
        )->add(
            label: __('pm::menu.be.report.index'),
            icon: 'fa-solid fa-chart-line',
        )->add(
            label: __('pm::menu.be.report.invoice'),
            icon: 'fa-solid fa-file-invoice-dollar',
        );

        synav()->setActiveMenu('pm.report.invoice');

        $years = Project::query()
            ->distinct()
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->select(['year as label', 'year as value'])
            ->get()
            ->toArray();

        return view('pm::livewire.report.invoice', [
            'breadcrumbs' => $breadcrumbs,
            'years' => $years,
        ])->title(page_title(__('pm::menu.be.report.invoice')));
    }
}
