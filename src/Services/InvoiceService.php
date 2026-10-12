<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Exports\InvoiceExportQuery;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectInvoice;
use VmEngine\Pm\Services\Concerns\RecordsActivity;
use VmEngine\Synapse\Models\ExcelExport;
use VmEngine\Synapse\Services\Excel\ExcelExporter;
use VmEngine\Synapse\Services\Excel\Transformers\BooleanTransformer;
use VmEngine\Synapse\Services\Excel\Transformers\DateFormatTransformer;
use VmEngine\Synapse\Services\Excel\Transformers\DefaultValueTransformer;
use VmEngine\SynAuth\Models\User;

class InvoiceService
{
    use RecordsActivity;

    /**
     * Create a new invoice.
     *
     * @param  array<string, mixed>  $invoiceData
     */
    public function create(Project $project, array $invoiceData): ProjectInvoice
    {
        $conversion = app(InvoiceConversionService::class);
        $currency = $invoiceData['currency'] ?? 'IDR';
        $exchangeRate = $conversion->normalizeRate(
            $currency,
            (float) ($invoiceData['exchange_rate'] ?? $conversion->defaultExchangeRate($currency))
        );

        $data = [
            // DbConfig defaults for VAT — caller may still override via $invoiceData.
            // These drive the initial stored row; the invoice-detail editor will
            // respect the persisted value on subsequent opens.
            'vat_percentage' => (float) dbconf('pm.invoice.vat_percentage', 11.0),
            'include_vat' => (bool) dbconf('pm.invoice.include_vat_default', true),
            ...$invoiceData,
            'user_id' => Auth::id(),
            'signature_id' => ($invoiceData['has_signature'] ?? false) ? $invoiceData['signature_id'] : null,
            'amount' => 0,
            'total_amount' => 0,
            'paid_amount' => 0,
            'exchange_rate' => $exchangeRate,
            'amount_idr' => 0,
            'vat_amount_idr' => 0,
            'dpp_amount_idr' => 0,
            'discount_amount_idr' => 0,
            'total_amount_idr' => 0,
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
     *
     * @param  array<string, mixed>  $invoiceData
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
        $this->recordActivity(
            'pm.project.'.$project->id.'.invoice.delete',
            'User '.Auth::user()->name.' deleted an invoice. Data: '.json_encode($invoiceData),
            'project.'.$project->id.'.invoice',
        );

        return $deleted;
    }

    /**
     * Find a non-archived invoice by id.
     */
    public function findInvoice(int $id): ProjectInvoice
    {
        return ProjectInvoice::query()->findOrFail($id);
    }

    /**
     * Distinct years found on issued invoices (non-archived), sorted desc.
     *
     * @return array<int, int>
     */
    public function getAvailableYears(): array
    {
        return ProjectInvoice::query()
            ->where('archived', 0)
            ->selectRaw($this->dateExtract('YEAR', 'issue_date').' as yr')
            ->groupBy('yr')
            ->orderByDesc('yr')
            ->pluck('yr')
            ->map(fn ($y) => (int) $y)
            ->values()
            ->all();
    }

    /**
     * Distinct invoice creators (id + name) for filter dropdowns.
     *
     * @return Collection<int, User> creators with only id and name loaded
     */
    public function getCreators(): Collection
    {
        return ProjectInvoice::query()
            ->where('archived', 0)
            ->whereNotNull('created_by')
            ->with('creator:id,name')
            ->select('created_by')
            ->distinct()
            ->get()
            ->map(fn ($invoice) => $invoice->creator)
            ->filter()
            ->unique('id')
            ->values();
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
     *
     * @param  array<string, mixed>|null  $newData
     * @param  array<string, mixed>|null  $oldData
     */
    private function logActivity(string $action, Project $project, ProjectInvoice $invoice, ?array $newData = null, ?array $oldData = null): void
    {
        $message = match ($action) {
            'create' => 'User '.Auth::user()->name.' created a new invoice: '.json_encode($newData),
            'update' => 'User '.Auth::user()->name.' updated an invoice. Old Data: '.json_encode($oldData).' New Data: '.json_encode($newData),
            'delete' => 'User '.Auth::user()->name.' deleted an invoice.',
            default => 'User '.Auth::user()->name.' performed '.$action.' on invoice.',
        };

        $this->recordActivity(
            'pm.project.'.$project->id.'.invoice.'.$action,
            $message,
            'project.'.$project->id.'.invoice',
        );
    }

    /**
     * Return statistic array
     *
     * Money sums come back from the driver as int, float or numeric string.
     *
     * @return array{invoice_count: int, receipt_count: int, sum_unpaid: int|float|string, sum_paid: int|float|string, total_invoiced: int|float|string, vat_total: int|float|string}
     */
    public function statistics(int $year): array
    {
        $invoiceCount = $this->baseInvoiceQuery($year)->count();
        $receiptCount = $this->baseInvoiceQuery($year)->whereNotNull('receipt_number')->count();
        $sumUnpaid = $this->baseInvoiceQuery($year)->where('is_paid', 0)->sum('total_amount_idr');
        $sumPaid = $this->baseInvoiceQuery($year)->where('is_paid', 1)->sum('total_amount_idr');
        $totalInvoiced = $this->baseInvoiceQuery($year)->sum('total_amount_idr');
        $vatTotal = $this->baseInvoiceQuery($year)->sum('vat_amount_idr');

        return [
            'invoice_count' => $invoiceCount,
            'receipt_count' => $receiptCount,
            'sum_unpaid' => $sumUnpaid,
            'sum_paid' => $sumPaid,
            'total_invoiced' => $totalInvoiced,
            'vat_total' => $vatTotal,
        ];
    }

    /**
     * Get the appropriate SQL expression for extracting year or month from a date column.
     *
     * @param  'YEAR'|'MONTH'  $part
     * @param  literal-string  $column
     * @return literal-string
     */
    private function dateExtract(string $part, string $column): string
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            $format = $part === 'YEAR' ? '%Y' : '%m';

            return "CAST(strftime('{$format}', {$column}) AS INTEGER)";
        }

        return "{$part}({$column})";
    }

    /**
     * @return Builder<ProjectInvoice>
     */
    private function baseInvoiceQuery(?int $year = null, bool $includeArchived = false): Builder
    {
        $query = ProjectInvoice::query();

        if (! $includeArchived) {
            $query->where('archived', 0);
        }

        if ($year !== null) {
            $query->where(DB::raw($this->dateExtract('YEAR', 'issue_date')), $year);
        }

        return $query;
    }

    /**
     * @param  int|null  $year  Filter by year (null = all years)
     * @param  int|null  $id  Filter by creator ID
     * @return EloquentCollection<int, ProjectInvoice>
     */
    public function getByCreator(?int $year = null, ?int $id = null): EloquentCollection
    {
        $query = $this->baseInvoiceQuery($year)
            ->select(
                'created_by',
                DB::raw('COUNT(CASE WHEN is_paid = 0 THEN 1 ELSE NULL END) as unpaid_count'),
                DB::raw('SUM(CASE WHEN is_paid = 0 THEN total_amount_idr ELSE NULL END) as unpaid_amount'),
                DB::raw('COUNT(CASE WHEN is_paid = 1 THEN 1 ELSE NULL END) as paid_count'),
                DB::raw('SUM(CASE WHEN is_paid = 1 THEN total_amount_idr ELSE NULL END) as paid_amount'),
                DB::raw('COUNT(*) as total_count'),
                DB::raw('SUM(total_amount_idr) as total_amount'))
            ->groupBy('created_by')
            ->with('creator:id,name');

        if ($id !== null) {
            $query->where('created_by', $id);
        }

        return $query->get();
    }

    /**
     * @return EloquentCollection<int, ProjectInvoice>
     */
    public function getByProject(?int $year = null): EloquentCollection
    {
        return $this->baseInvoiceQuery($year)
            ->select(
                'project_category_id',
                DB::raw('COUNT(*) as total_count'),
                DB::raw('SUM(amount_idr) as sum_amount'),
                DB::raw('COUNT(CASE WHEN is_paid = 0 THEN 1 ELSE NULL END) as unpaid_count'),
                DB::raw('SUM(CASE WHEN is_paid = 0 THEN total_amount_idr ELSE NULL END) as unpaid_amount'),
                DB::raw('COUNT(CASE WHEN is_paid = 1 THEN 1 ELSE NULL END) as paid_count'),
                DB::raw('SUM(CASE WHEN is_paid = 1 THEN total_amount_idr ELSE NULL END) as paid_amount'),
                DB::raw('SUM(total_amount_idr) as total_amount'),
                DB::raw('SUM(vat_amount_idr) as vat_amount'))
            ->groupBy('project_category_id')
            ->with('projectCategory')
            ->get();
    }

    /**
     * Return API statistics with extended metrics.
     *
     * @return array<string, mixed>
     */
    public function apiStatistics(?int $year = null): array
    {
        $query = $this->baseInvoiceQuery($year);

        $stats = $query->selectRaw('
            COUNT(*) as total_invoice,
            COUNT(DISTINCT project_category_id) as total_invoice_categories,
            COUNT(DISTINCT created_by) as total_invoice_creator,
            SUM(total_amount_idr) as grand_total_amount,
            SUM(CASE WHEN is_paid = 0 THEN total_amount_idr ELSE 0 END) as grand_total_unpaid,
            SUM(CASE WHEN is_paid = 1 THEN total_amount_idr ELSE 0 END) as grand_total_paid,
            SUM(vat_amount_idr) as total_vat,
            SUM(amount_idr) as total_amount,
            SUM(CASE WHEN is_paid = 0 THEN vat_amount_idr ELSE 0 END) as unpaid_vat,
            SUM(CASE WHEN is_paid = 1 THEN vat_amount_idr ELSE 0 END) as paid_vat,
            SUM(CASE WHEN is_paid = 0 THEN amount_idr ELSE 0 END) as unpaid_amount,
            SUM(CASE WHEN is_paid = 1 THEN amount_idr ELSE 0 END) as paid_amount
        ')->toBase()->first();

        return [
            'total_invoice' => (int) $stats->total_invoice,
            'total_invoice_categories' => (int) $stats->total_invoice_categories,
            'total_invoice_creator' => (int) $stats->total_invoice_creator,
            'grand_total_amount' => (float) $stats->grand_total_amount,
            'grand_total_unpaid' => (float) $stats->grand_total_unpaid,
            'grand_total_paid' => (float) $stats->grand_total_paid,
            'total_vat' => (float) $stats->total_vat,
            'total_amount' => (float) $stats->total_amount,
            'unpaid_vat' => (float) $stats->unpaid_vat,
            'paid_vat' => (float) $stats->paid_vat,
            'unpaid_amount' => (float) $stats->unpaid_amount,
            'paid_amount' => (float) $stats->paid_amount,
        ];
    }

    /**
     * Get invoices grouped by month.
     *
     * @return EloquentCollection<int, ProjectInvoice>
     */
    public function getByMonth(?int $year = null): EloquentCollection
    {
        $yearExpr = $this->dateExtract('YEAR', 'issue_date');
        $monthExpr = $this->dateExtract('MONTH', 'issue_date');

        return $this->baseInvoiceQuery($year)
            ->select(
                DB::raw("{$yearExpr} as year"),
                DB::raw("{$monthExpr} as month"),
                DB::raw('COUNT(*) as total_count'),
                DB::raw('SUM(amount_idr) as sum_amount'),
                DB::raw('SUM(vat_amount_idr) as vat_amount'),
                DB::raw('SUM(total_amount_idr) as total_amount'),
                DB::raw('COUNT(CASE WHEN is_paid = 0 THEN 1 ELSE NULL END) as unpaid_count'),
                DB::raw('SUM(CASE WHEN is_paid = 0 THEN total_amount_idr ELSE NULL END) as unpaid_amount'),
                DB::raw('COUNT(CASE WHEN is_paid = 1 THEN 1 ELSE NULL END) as paid_count'),
                DB::raw('SUM(CASE WHEN is_paid = 1 THEN total_amount_idr ELSE NULL END) as paid_amount'),
            )
            ->groupBy(DB::raw($yearExpr), DB::raw($monthExpr))
            ->orderBy(DB::raw($yearExpr))
            ->orderBy(DB::raw($monthExpr))
            ->get();
    }

    /**
     * Get paginated invoice list with filters.
     *
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, ProjectInvoice>
     */
    public function getInvoiceList(array $filters): LengthAwarePaginator
    {
        $limit = min((int) ($filters['limit'] ?? 20), 100);

        return $this->invoiceListQuery($filters)->paginate($limit);
    }

    /**
     * Filtered, sorted, eager-loaded invoice query shared by the paginated list and exports.
     *
     * @param  array<string, mixed>  $filters
     * @return Builder<ProjectInvoice>
     */
    public function invoiceListQuery(array $filters): Builder
    {
        $allowedSortColumns = [
            'issue_date', 'due_date', 'total_amount',
            'invoice_number', 'created_at', 'is_paid',
        ];

        $year = $filters['year'] ?? null;
        $month = $filters['month'] ?? null;
        $creator = $filters['creator'] ?? null;
        $projectCategory = $filters['project_category'] ?? null;
        $isPaid = $filters['is_paid'] ?? null;
        $hasReceipt = isset($filters['has_receipt']) && $filters['has_receipt'] !== '' ? (string) $filters['has_receipt'] : null;
        $hasVat = isset($filters['has_vat']) && $filters['has_vat'] !== '' ? (string) $filters['has_vat'] : null;
        $status = isset($filters['status']) ? (string) $filters['status'] : null;
        $q = isset($filters['q']) ? trim((string) $filters['q']) : null;
        $sortBy = $filters['sort_by'] ?? 'issue_date';
        $sortDesc = $filters['sort_desc'] ?? true;
        $includeArchived = (bool) ($filters['include_archived'] ?? false);

        if (! in_array($sortBy, $allowedSortColumns, true)) {
            $sortBy = 'issue_date';
        }

        $query = $this->baseInvoiceQuery($year ? (int) $year : null, $includeArchived);

        if ($month !== null) {
            $query->where(DB::raw($this->dateExtract('MONTH', 'issue_date')), (int) $month);
        }

        if ($creator !== null) {
            $query->where('created_by', (int) $creator);
        }

        if ($projectCategory !== null) {
            $query->where('project_category_id', (int) $projectCategory);
        }

        if ($isPaid !== null) {
            $query->where('is_paid', (bool) $isPaid);
        }

        if ($hasReceipt === 'yes') {
            $query->whereNotNull('receipt_number');
        } elseif ($hasReceipt === 'no') {
            $query->whereNull('receipt_number');
        }

        if ($hasVat === 'yes') {
            $query->where('include_vat', true);
        } elseif ($hasVat === 'no') {
            $query->where('include_vat', false);
        }

        if ($status !== null && $status !== '') {
            match ($status) {
                'paid' => $query->where('is_paid', true),
                'outstanding' => $query->where('is_paid', false),
                'overdue' => $query->where('is_paid', false)->whereDate('due_date', '<', now()->toDateString()),
                default => null,
            };
        }

        if ($q !== null && $q !== '') {
            $query->where(function ($sub) use ($q): void {
                $sub->where('invoice_number', 'like', "%{$q}%")
                    ->orWhere('recipient', 'like', "%{$q}%")
                    ->orWhere('receipt_number', 'like', "%{$q}%")
                    ->orWhereHas('client', fn ($c) => $c->where('company', 'like', "%{$q}%"))
                    ->orWhereHas('creator', fn ($c) => $c->where('name', 'like', "%{$q}%"));
            });
        }

        $query->with([
            'project:id,name', 'projectCategory:id,name', 'creator:id,name', 'client:id,company',
            'issuer:id,name', 'bankAccount:id,bank_name,number',
        ])
            ->orderBy($sortBy, $sortDesc ? 'desc' : 'asc');

        return $query;
    }

    /**
     * Get the export reference key for a given year and user.
     */
    public static function exportReference(int $year, int $userId): string
    {
        return "pm-invoice-export-{$year}-{$userId}";
    }

    /**
     * Export invoices to Excel (background job).
     *
     * Deletes previous export file for the same reference before dispatching.
     */
    public function exportToExcel(int $year): ExcelExport
    {
        $userId = Auth::id();
        if ($userId === null) {
            throw new AuthenticationException;
        }
        $userId = (int) $userId; // guard ids are int|string; pm users have integer keys
        $reference = self::exportReference($year, $userId);
        $outputPath = storage_path('app/exports/invoices-'.$year.'-'.now()->format('YmdHis').'.xlsx');

        // Delete previous export file for this reference
        $previous = ExcelExport::findByReference($reference);
        if ($previous && $previous->output_path && file_exists($previous->output_path)) {
            @unlink($previous->output_path);
        }

        return ExcelExporter::make(InvoiceExportQuery::class)
            ->columns([
                __('pm::report.invoice.export.client') => 'client.company',
                __('pm::report.invoice.export.project') => 'project.name',
                __('pm::report.invoice.export.category') => [
                    'field' => 'projectCategory.name',
                    'transformer' => DefaultValueTransformer::class,
                    'config' => ['default' => __('pm::report.documents.table.uncategorized')],
                ],
                __('pm::report.invoice.export.invoice_number') => 'invoice_number',
                __('pm::report.invoice.export.issue_date') => [
                    'field' => 'issue_date',
                    'transformer' => DateFormatTransformer::class,
                    'config' => ['format' => 'Y-m-d'],
                ],
                __('pm::report.invoice.export.due_date') => [
                    'field' => 'due_date',
                    'transformer' => DateFormatTransformer::class,
                    'config' => ['format' => 'Y-m-d'],
                ],
                __('pm::report.invoice.export.total_amount') => 'total_amount_idr',
                __('pm::report.invoice.export.receipt_number') => 'receipt_number',
                __('pm::report.invoice.export.paid_status') => [
                    'field' => 'is_paid',
                    'transformer' => BooleanTransformer::class,
                    'config' => [
                        'trueLabel' => __('pm::report.invoice.export.paid'),
                        'falseLabel' => __('pm::report.invoice.export.unpaid'),
                    ],
                ],
                __('pm::report.invoice.export.payment_time') => [
                    'field' => 'paid_at',
                    'transformer' => DateFormatTransformer::class,
                    'config' => ['format' => 'Y-m-d H:i'],
                ],
                __('pm::report.invoice.export.creator') => 'creator.name',
            ])
            ->columnStyles([
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
            ->reference($reference)
            ->withFilters(['year' => $year])
            ->forUser($userId)
            ->dispatch($outputPath);
    }
}
