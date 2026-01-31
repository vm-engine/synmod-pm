<?php

declare(strict_types=1);

namespace VmEngine\Pm\Exports;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\DB;
use VmEngine\Pm\Models\ProjectInvoice;
use VmEngine\Synapse\Services\Excel\Contracts\ExportQuery;

class InvoiceExportQuery implements ExportQuery
{
    public function __construct(
        public readonly ?int $year = null,
    ) {}

    public function __invoke(): EloquentBuilder
    {
        return ProjectInvoice::query()
            ->with([
                'client:id,company',
                'project:id,name',
                'projectCategory:id,name',
                'creator:id,name',
            ])
            ->when($this->year, fn ($q) => $q->where(DB::raw('YEAR(issue_date)'), $this->year))
            ->where('archived', 0)
            ->orderBy('issue_date', 'asc')
            ->orderBy('invoice_number', 'asc');
    }
}
