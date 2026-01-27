<?php

declare(strict_types=1);

namespace VmEngine\Pm\Livewire\Report;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Services\InvoiceService;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;

class Invoice extends Component
{
    #[Url()]
    public ?int $year = null;

    public string $pageName = '';

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
