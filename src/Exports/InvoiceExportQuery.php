<?php

declare(strict_types=1);

namespace VmEngine\Pm\Exports;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use VmEngine\Pm\Models\ProjectInvoice;
use VmEngine\Synapse\Services\Excel\Contracts\ExportQuery;

class InvoiceExportQuery implements ExportQuery
{
    public function __construct(
        public readonly ?int $year = null,
    ) {}

    public function __invoke(): EloquentBuilder|QueryBuilder
    {
        $usersTable = app(config('auth.providers.users.model'))->getTable();

        return ProjectInvoice::query()
            ->select([
                'project_invoices.invoice_number',
                'project_invoices.issue_date',
                'project_invoices.due_date',
                'project_invoices.total_amount',
                'project_invoices.receipt_number',
                'project_invoices.is_paid',
                'project_invoices.paid_at',
                'clients.company as client_company',
                'projects.name as project_name',
                'project_categories.name as category_name',
                $usersTable.'.name as creator_name',
            ])
            ->leftJoin('clients', 'project_invoices.client_id', '=', 'clients.id')
            ->leftJoin('projects', 'project_invoices.project_id', '=', 'projects.id')
            ->leftJoin('project_categories', 'project_invoices.project_category_id', '=', 'project_categories.id')
            ->leftJoin($usersTable, 'project_invoices.created_by', '=', $usersTable.'.id')
            ->when($this->year, fn ($q) => $q->where(DB::raw('YEAR(project_invoices.issue_date)'), $this->year))
            ->where('project_invoices.archived', 0)
            ->orderBy('project_invoices.issue_date', 'asc')
            ->orderBy('project_invoices.invoice_number', 'asc');
    }
}
