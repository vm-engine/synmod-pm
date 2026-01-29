<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Exports\InvoiceExportQuery;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectInvoice;
use VmEngine\Synapse\Models\ExcelExport;
use VmEngine\Synapse\Services\Excel\ExcelExporter;
use VmEngine\SynAuth\Facades\SynAuth;

class InvoiceService
{
    /**
     * Create a new invoice.
     */
    public function create(Project $project, array $invoiceData): ProjectInvoice
    {
        $data = [
            ...$invoiceData,
            'user_id' => Auth::id(),
            'signature_id' => ($invoiceData['has_signature'] ?? false) ? $invoiceData['signature_id'] : null,
            'amount' => 0,
            'total_amount' => 0,
            'paid_amount' => 0,
        ];

        $invoice = ProjectInvoice::create($data);

        // Increment the invoice index
        $handler = Documents::INVOICE->handler();
        $handler->incrementIndex($project, Documents::INVOICE);

        // Log activity
        $this->logActivity('create', $project, $invoice, $data);

        return $invoice;
    }

    /**
     * Update an existing invoice.
     */
    public function update(ProjectInvoice $invoice, array $invoiceData): ProjectInvoice
    {
        $oldData = $invoice->toArray();

        $data = [
            ...$invoiceData,
            'signature_id' => ($invoiceData['has_signature'] ?? false) ? $invoiceData['signature_id'] : null,
        ];

        $invoice->update($data);

        // Log activity
        $this->logActivity('update', $invoice->project, $invoice, $data, $oldData);

        return $invoice->fresh();
    }

    /**
     * Delete an invoice.
     */
    public function delete(ProjectInvoice $invoice): bool
    {
        $project = $invoice->project;
        $invoiceData = $invoice->toArray();

        $deleted = $invoice->delete();

        // Log activity
        SynAuth::logActivity(
            Auth::id(),
            'pm.project.'.$project->id.'.invoice.delete',
            'User '.Auth::user()->name.' deleted an invoice. Data: '.json_encode($invoiceData),
            'project.'.$project->id.'.invoice',
            'pm',
        );

        return $deleted;
    }

    /**
     * Generate invoice number for a project.
     */
    public function generateInvoiceNumber(Project $project): string
    {
        $handler = Documents::INVOICE->handler();

        return $handler->generateDocumentNumber($project, Documents::INVOICE);
    }

    /**
     * Log invoice activity.
     */
    private function logActivity(string $action, Project $project, ProjectInvoice $invoice, ?array $newData = null, ?array $oldData = null): void
    {
        $message = match ($action) {
            'create' => 'User '.Auth::user()->name.' created a new invoice: '.json_encode($newData),
            'update' => 'User '.Auth::user()->name.' updated an invoice. Old Data: '.json_encode($oldData).' New Data: '.json_encode($newData),
            'delete' => 'User '.Auth::user()->name.' deleted an invoice.',
            default => 'User '.Auth::user()->name.' performed '.$action.' on invoice.',
        };

        SynAuth::logActivity(
            Auth::id(),
            'pm.project.'.$project->id.'.invoice.'.$action,
            $message,
            'project.'.$project->id.'.invoice',
            'pm',
        );
    }

    /**
     * Return statistic array
     */
    public function statistics(int $year): array
    {
        $invoiceCount = $this->baseInvoiceQuery($year)->count();
        $receiptCount = $this->baseInvoiceQuery($year)->whereNotNull('receipt_number')->count();
        $sumUnpaid = $this->baseInvoiceQuery($year)->where('is_paid', 0)->sum('total_amount');
        $sumPaid = $this->baseInvoiceQuery($year)->where('is_paid', 1)->sum('total_amount');
        $totalInvoiced = $this->baseInvoiceQuery($year)->sum('total_amount');
        $vatTotal = $this->baseInvoiceQuery($year)->sum('vat_amount');

        return [
            'invoice_count' => $invoiceCount,
            'receipt_count' => $receiptCount,
            'sum_unpaid' => $sumUnpaid,
            'sum_paid' => $sumPaid,
            'total_invoiced' => $totalInvoiced,
            'vat_total' => $vatTotal,
        ];
    }

    private function baseInvoiceQuery(int $year): \Illuminate\Database\Eloquent\Builder
    {
        return ProjectInvoice::query()
            ->where(DB::raw('YEAR(issue_date)'), $year)
            ->where('archived', 0);
    }

    public function getByCreator(int $year)
    {
        return $this->baseInvoiceQuery($year)
            ->select(
                'created_by',
                DB::raw('COUNT(CASE WHEN is_paid = 0 THEN 1 ELSE NULL END) as unpaid_count'),
                DB::raw('SUM(CASE WHEN is_paid = 0 THEN total_amount ELSE NULL END) as unpaid_amount'),
                DB::raw('COUNT(CASE WHEN is_paid = 1 THEN 1 ELSE NULL END) as paid_count'),
                DB::raw('SUM(CASE WHEN is_paid = 1 THEN total_amount ELSE NULL END) as paid_amount'),
                DB::raw('COUNT(*) as total_count'),
                DB::raw('SUM(total_amount) as total_amount'))
            ->groupBy('created_by')
            ->with('creator:id,name')
            ->get();
    }

    public function getByProject(int $year)
    {
        return $this->baseInvoiceQuery($year)
            ->select(
                'project_category_id',
                DB::raw('COUNT(*) as total_count'),
                DB::raw('SUM(amount) as sum_amount'),
                DB::raw('COUNT(CASE WHEN is_paid = 0 THEN 1 ELSE NULL END) as unpaid_count'),
                DB::raw('SUM(CASE WHEN is_paid = 0 THEN total_amount ELSE NULL END) as unpaid_amount'),
                DB::raw('COUNT(CASE WHEN is_paid = 1 THEN 1 ELSE NULL END) as paid_count'),
                DB::raw('SUM(CASE WHEN is_paid = 1 THEN total_amount ELSE NULL END) as paid_amount'),
                DB::raw('SUM(total_amount) as total_amount'),
                DB::raw('SUM(vat_amount) as vat_amount'))
            ->groupBy('project_category_id')
            ->with('projectCategory')
            ->get();
    }

    /**
     * Export invoices to Excel (background job).
     */
    public function exportToExcel(int $year): ExcelExport
    {
        $outputPath = storage_path('app/exports/invoices-'.$year.'-'.now()->format('YmdHis').'.xlsx');

        return ExcelExporter::make(InvoiceExportQuery::class)
            ->columns([
                __('pm::report.invoice.export.no') => null,
                __('pm::report.invoice.export.client') => 'client_company',
                __('pm::report.invoice.export.project') => 'project_name',
                __('pm::report.invoice.export.category') => 'category_name',
                __('pm::report.invoice.export.invoice_number') => 'invoice_number',
                __('pm::report.invoice.export.issue_date') => 'issue_date',
                __('pm::report.invoice.export.due_date') => 'due_date',
                __('pm::report.invoice.export.total_amount') => 'total_amount',
                __('pm::report.invoice.export.receipt_number') => 'receipt_number',
                __('pm::report.invoice.export.paid_status') => 'is_paid',
                __('pm::report.invoice.export.payment_time') => 'paid_at',
                __('pm::report.invoice.export.creator') => 'creator_name',
            ])
            ->columnStyles([
                __('pm::report.invoice.export.no') => ['width' => 5, 'alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
                __('pm::report.invoice.export.client') => ['width' => 30, 'alignment' => ['vertical' => 'center']],
                __('pm::report.invoice.export.project') => ['width' => 35, 'alignment' => ['vertical' => 'center']],
                __('pm::report.invoice.export.category') => ['width' => 20, 'alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
                __('pm::report.invoice.export.invoice_number') => ['width' => 25, 'alignment' => ['vertical' => 'center']],
                __('pm::report.invoice.export.issue_date') => ['width' => 12, 'alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
                __('pm::report.invoice.export.due_date') => ['width' => 12, 'alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
                __('pm::report.invoice.export.total_amount') => ['width' => 18, 'alignment' => ['horizontal' => 'right', 'vertical' => 'center'], 'numberFormat' => ['formatCode' => '#,##0.00']],
                __('pm::report.invoice.export.receipt_number') => ['width' => 25, 'alignment' => ['vertical' => 'center']],
                __('pm::report.invoice.export.paid_status') => ['width' => 10, 'alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
                __('pm::report.invoice.export.payment_time') => ['width' => 18, 'alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
                __('pm::report.invoice.export.creator') => ['width' => 20, 'alignment' => ['vertical' => 'center']],
            ])
            ->headerStyle([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4472C4']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ])
            ->sheet(__('pm::report.invoice.export.sheet_name', ['year' => $year]))
            ->withFilters(['year' => $year])
            ->forUser(Auth::id())
            ->dispatch($outputPath);
    }
}
