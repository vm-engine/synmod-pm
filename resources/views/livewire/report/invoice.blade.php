<div x-data="{
    pageName: $wire.entangle('pageName'),
    isHome: false,
    filtersExpanded: true
}">
    @include('synapps::components.layouts.partials.breadcrumbs')

    <div class="card mb-6">
        <div class="card-body">
            <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div>
                    <h1 class="text-2xl font-bold">{{ __('pm::report.invoice.statistics') }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('pm::report.invoice.statistics_description') }}
                    </p>
                </div>
                <div class="flex w-full items-center gap-3 sm:w-auto">
                    <div class="min-w-48">
                        <x-synapse-adv-select
                            id="year"
                            name="year"
                            wire-model="year"
                            :options="$years"
                            :live="true"
                            placeholder="{{ __('pm::report.invoice.filters.year') }}"
                        />
                    </div>
                    @php
                        $task = $this->exportTask;
                        $isProcessing = $task && in_array($task->status, [
                            \VmEngine\Synapse\Enums\ExcelTaskStatus::Pending,
                            \VmEngine\Synapse\Enums\ExcelTaskStatus::Processing,
                        ]);
                        $isCompleted = $task
                            && $task->status === \VmEngine\Synapse\Enums\ExcelTaskStatus::Completed
                            && $task->output_path
                            && file_exists($task->output_path);
                        $isFailed = $task && $task->status === \VmEngine\Synapse\Enums\ExcelTaskStatus::Failed;
                    @endphp

                    @if ($isFailed)
                        <span class="text-sm text-red-600 dark:text-red-400">
                            <span class="fa-solid fa-exclamation-triangle mr-1"></span>
                            {{ __('pm::report.invoice.export.failed') }}
                        </span>
                    @endif

                    <button
                        class="btn success"
                        type="button"
                        wire:click="exportToExcel"
                        {{ $isProcessing ? 'disabled' : '' }}
                        wire:loading.attr="disabled"
                        wire:target="exportToExcel"
                    >
                        <span class="fa-solid fa-file-excel mr-1" wire:loading.remove wire:target="exportToExcel"></span>
                        <span class="fa-solid fa-spinner fa-spin mr-1" wire:loading wire:target="exportToExcel"></span>
                        @if ($isProcessing)
                            <span class="fa-solid fa-spinner fa-spin mr-1" wire:poll.2s="checkExportStatus"></span>
                            {{ __('pm::report.invoice.export.processing') }}
                        @else
                            <span wire:loading.remove wire:target="exportToExcel">{{ __('pm::report.invoice.export.button') }}</span>
                            <span wire:loading wire:target="exportToExcel">{{ __('pm::report.invoice.export.exporting') }}</span>
                        @endif
                    </button>

                    @if ($isCompleted)
                        <button
                            class="btn primary"
                            type="button"
                            wire:click="downloadExport"
                        >
                            <span class="fa-solid fa-download mr-1"></span>
                            {{ __('pm::report.invoice.export.download') }}
                        </button>
                    @endif
                </div>
            </div>

            <div
                class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3"
                wire:loading.class="opacity-50"
                wire:target="year"
            >
                {{-- Total Invoices --}}
                <div class="statistic-box group flex items-center transition-all hover:shadow-md">
                    <div
                        class="statistic-box-icon bg-blue-100 p-4 text-blue-600 transition-colors group-hover:bg-blue-200 dark:bg-blue-900/20 dark:text-blue-400 dark:group-hover:bg-blue-900/30">
                        <span class="fa-solid fa-file-invoice fa-3x"></span>
                    </div>
                    <div class="p-4">
                        <div class="statistic-box-label text-sm font-medium text-gray-600 dark:text-gray-400">
                            {{ __('pm::report.invoice.stats.total_invoices') }}
                        </div>
                        <div class="statistic-box-value text-2xl font-bold">
                            {{ number_format($this->statistics['invoice_count'], 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                {{-- Total Receipts --}}
                <div class="statistic-box group flex items-center transition-all hover:shadow-md">
                    <div
                        class="statistic-box-icon bg-purple-100 p-4 text-purple-600 transition-colors group-hover:bg-purple-200 dark:bg-purple-900/20 dark:text-purple-400 dark:group-hover:bg-purple-900/30">
                        <span class="fa-solid fa-receipt fa-3x"></span>
                    </div>
                    <div class="p-4">
                        <div class="statistic-box-label text-sm font-medium text-gray-600 dark:text-gray-400">
                            {{ __('pm::report.invoice.stats.total_receipts') }}
                        </div>
                        <div class="statistic-box-value text-2xl font-bold">
                            {{ number_format($this->statistics['receipt_count'], 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                {{-- Total Amount --}}
                <div class="statistic-box group flex items-center transition-all hover:shadow-md">
                    <div
                        class="statistic-box-icon bg-indigo-100 p-4 text-indigo-600 transition-colors group-hover:bg-indigo-200 dark:bg-indigo-900/20 dark:text-indigo-400 dark:group-hover:bg-indigo-900/30">
                        <span class="fa-solid fa-wallet fa-3x"></span>
                    </div>
                    <div class="p-4">
                        <div class="statistic-box-label text-sm font-medium text-gray-600 dark:text-gray-400">
                            {{ __('pm::report.invoice.stats.total_amount') }}
                        </div>
                        <div class="statistic-box-value text-xl font-bold">
                            Rp {{ number_format($this->statistics['total_invoiced'], 2, ',', '.') }}
                        </div>
                    </div>
                </div>

                {{-- Total Unpaid --}}
                <div class="statistic-box group flex items-center transition-all hover:shadow-md">
                    <div
                        class="statistic-box-icon bg-red-100 p-4 text-red-600 transition-colors group-hover:bg-red-200 dark:bg-red-900/20 dark:text-red-400 dark:group-hover:bg-red-900/30">
                        <span class="fa-solid fa-hourglass-half fa-3x"></span>
                    </div>
                    <div class="p-4">
                        <div class="statistic-box-label text-sm font-medium text-gray-600 dark:text-gray-400">
                            {{ __('pm::report.invoice.stats.total_unpaid') }}
                        </div>
                        <div class="statistic-box-value text-xl font-bold text-red-600 dark:text-red-400">
                            Rp {{ number_format($this->statistics['sum_unpaid'], 2, ',', '.') }}
                        </div>
                    </div>
                </div>

                {{-- Total Paid --}}
                <div class="statistic-box group flex items-center transition-all hover:shadow-md">
                    <div
                        class="statistic-box-icon bg-green-100 p-4 text-green-600 transition-colors group-hover:bg-green-200 dark:bg-green-900/20 dark:text-green-400 dark:group-hover:bg-green-900/30">
                        <span class="fa-solid fa-circle-check fa-3x"></span>
                    </div>
                    <div class="p-4">
                        <div class="statistic-box-label text-sm font-medium text-gray-600 dark:text-gray-400">
                            {{ __('pm::report.invoice.stats.total_paid') }}
                        </div>
                        <div class="statistic-box-value text-xl font-bold text-green-600 dark:text-green-400">
                            Rp {{ number_format($this->statistics['sum_paid'], 2, ',', '.') }}
                        </div>
                    </div>
                </div>

                {{-- VAT Total --}}
                <div class="statistic-box group flex items-center transition-all hover:shadow-md">
                    <div
                        class="statistic-box-icon bg-amber-100 p-4 text-amber-600 transition-colors group-hover:bg-amber-200 dark:bg-amber-900/20 dark:text-amber-400 dark:group-hover:bg-amber-900/30">
                        <span class="fa-solid fa-percent fa-3x"></span>
                    </div>
                    <div class="p-4">
                        <div class="statistic-box-label text-sm font-medium text-gray-600 dark:text-gray-400">
                            {{ __('pm::report.invoice.stats.vat_total') }}
                        </div>
                        <div class="statistic-box-value text-xl font-bold">
                            Rp {{ number_format($this->statistics['vat_total'], 2, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- By Creator Table --}}
    <div class="card mb-6">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <span class="fa-solid fa-user-tie text-blue-600 dark:text-blue-400"></span>
                <h3 class="card-title">{{ __('pm::report.invoice.by_creator') }}</h3>
            </div>
        </div>
        <div
            class="card-body"
            wire:loading.class="opacity-50"
            wire:target="year"
        >
            @if ($this->byCreator->isEmpty())
                <div class="py-12 text-center">
                    <span class="fa-solid fa-inbox fa-3x text-gray-300 dark:text-gray-600"></span>
                    <p class="mt-4 text-gray-500 dark:text-gray-400">
                        {{ __('pm::report.invoice.no_data') }}
                    </p>
                </div>
            @else
                <div class="space-y-5 sm:space-y-6">
                    <div class="dark:bg-white/3 rounded-2xl border border-gray-200 bg-white dark:border-gray-800">
                        <div class="border-t border-gray-100 dark:border-gray-800">
                            <div class="max-w-full overflow-x-auto">
                                <table class="datatable min-w-full">
                                    <thead>
                                        <tr>
                                            <th class="w-1">
                                                <p>{{ __('pm::invoice.col_no') }}</p>
                                            </th>
                                            <th>
                                                <p>{{ __('pm::invoice.col_creator') }}</p>
                                            </th>
                                            <th class="text-right">
                                                <p>{{ __('pm::report.invoice.stats.total_unpaid') }}</p>
                                            </th>
                                            <th class="text-right">
                                                <p>{{ __('pm::report.invoice.stats.total_paid') }}</p>
                                            </th>
                                            <th class="text-right">
                                                <p>{{ __('pm::report.invoice.stats.total_amount') }}</p>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $sumUnpaid = 0;
                                            $sumPaid = 0;
                                            $sumTotal = 0;
                                            $countUnpaid = 0;
                                            $countPaid = 0;
                                            $countTotal = 0;
                                        @endphp
                                        @foreach ($this->byCreator as $index => $invoiceByCreator)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                                <td>
                                                    <div class="text-center font-medium text-gray-500">
                                                        {{ $index + 1 }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="font-medium">
                                                        {{ $invoiceByCreator->creator->name }}
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <div class="text-red-600 dark:text-red-400">
                                                        <div class="font-semibold">
                                                            Rp {{ number_format($invoiceByCreator->unpaid_amount, 2, ',', '.') }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            ({{ $invoiceByCreator->unpaid_count }}
                                                            {{ __('pm::report.invoice.invoices') }})
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <div class="text-green-600 dark:text-green-400">
                                                        <div class="font-semibold">
                                                            Rp {{ number_format($invoiceByCreator->paid_amount, 2, ',', '.') }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            ({{ $invoiceByCreator->paid_count }}
                                                            {{ __('pm::report.invoice.invoices') }})
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <div class="font-bold">
                                                        Rp {{ number_format($invoiceByCreator->total_amount, 2, ',', '.') }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        ({{ $invoiceByCreator->total_count }}
                                                        {{ __('pm::report.invoice.invoices') }})
                                                    </div>
                                                </td>
                                            </tr>
                                            @php
                                                $sumUnpaid += $invoiceByCreator->unpaid_amount;
                                                $sumPaid += $invoiceByCreator->paid_amount;
                                                $sumTotal += $invoiceByCreator->total_amount;
                                                $countUnpaid += $invoiceByCreator->unpaid_count;
                                                $countPaid += $invoiceByCreator->paid_count;
                                                $countTotal += $invoiceByCreator->total_count;
                                            @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-gray-100 font-bold dark:bg-gray-800">
                                            <td
                                                class="text-right"
                                                colspan="2"
                                            >
                                                <p class="text-lg">
                                                    {{ __('pm::report.invoice.total') }}
                                                </p>
                                            </td>
                                            <td class="text-right">
                                                <p class="text-red-600 dark:text-red-400">
                                                    Rp {{ number_format($sumUnpaid, 2, ',', '.') }}
                                                </p>
                                                <p class="text-xs text-gray-500">({{ $countUnpaid }})</p>
                                            </td>
                                            <td class="text-right">
                                                <p class="text-green-600 dark:text-green-400">
                                                    Rp {{ number_format($sumPaid, 2, ',', '.') }}
                                                </p>
                                                <p class="text-xs text-gray-500">({{ $countPaid }})</p>
                                            </td>
                                            <td class="text-right">
                                                <p class="text-lg">
                                                    Rp {{ number_format($sumTotal, 2, ',', '.') }}
                                                </p>
                                                <p class="text-xs text-gray-500">({{ $countTotal }})</p>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    {{-- By Project Category Table --}}
    <div class="card mb-6">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <span class="fa-solid fa-folder-tree text-indigo-600 dark:text-indigo-400"></span>
                <h3 class="card-title">{{ __('pm::report.invoice.by_project') }}</h3>
            </div>
        </div>
        <div
            class="card-body"
            wire:loading.class="opacity-50"
            wire:target="year"
        >
            @if ($this->byProject->isEmpty())
                <div class="py-12 text-center">
                    <span class="fa-solid fa-inbox fa-3x text-gray-300 dark:text-gray-600"></span>
                    <p class="mt-4 text-gray-500 dark:text-gray-400">
                        {{ __('pm::report.invoice.no_data') }}
                    </p>
                </div>
            @else
                <div class="space-y-5 sm:space-y-6">
                    <div class="dark:bg-white/3 rounded-2xl border border-gray-200 bg-white dark:border-gray-800">
                        <div class="border-t border-gray-100 dark:border-gray-800">
                            <div class="max-w-full overflow-x-auto">
                                <table class="datatable min-w-full">
                                    <thead>
                                        <tr>
                                            <th class="w-1">
                                                <p>{{ __('pm::invoice.col_no') }}</p>
                                            </th>
                                            <th>
                                                <p>{{ __('pm::report.invoice.project_categories') }}</p>
                                            </th>
                                            <th class="text-center">
                                                <p>{{ __('pm::report.invoice.stats.total_invoices') }}</p>
                                            </th>
                                            <th class="text-right">
                                                <p>{{ __('pm::report.invoice.invoice_amount') }}</p>
                                            </th>
                                            <th class="text-right">
                                                <p>{{ __('pm::report.invoice.stats.total_unpaid') }}</p>
                                            </th>
                                            <th class="text-right">
                                                <p>{{ __('pm::report.invoice.stats.total_paid') }}</p>
                                            </th>
                                            <th class="text-right">
                                                <p>{{ __('pm::report.invoice.stats.vat_total') }}</p>
                                            </th>
                                            <th class="text-right">
                                                <p>{{ __('pm::report.invoice.stats.total_amount') }}</p>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $sumAmount = 0;
                                            $sumUnpaid = 0;
                                            $sumPaid = 0;
                                            $sumVat = 0;
                                            $sumTotal = 0;
                                            $countUnpaid = 0;
                                            $countPaid = 0;
                                            $countTotal = 0;
                                        @endphp
                                        @foreach ($this->byProject as $index => $invoiceByProject)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                                <td>
                                                    <div class="text-center font-medium text-gray-500">
                                                        {{ $index + 1 }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="font-medium">
                                                        {{ $invoiceByProject->projectCategory->name }}
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                                        {{ $invoiceByProject->total_count }}
                                                    </span>
                                                </td>
                                                <td class="text-right">
                                                    <div class="font-semibold">
                                                        Rp {{ number_format($invoiceByProject->sum_amount, 2, ',', '.') }}
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <div class="text-red-600 dark:text-red-400">
                                                        <div class="font-semibold">
                                                            Rp {{ number_format($invoiceByProject->unpaid_amount, 2, ',', '.') }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            ({{ $invoiceByProject->unpaid_count }}
                                                            {{ __('pm::report.invoice.invoices') }})
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <div class="text-green-600 dark:text-green-400">
                                                        <div class="font-semibold">
                                                            Rp {{ number_format($invoiceByProject->paid_amount, 2, ',', '.') }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            ({{ $invoiceByProject->paid_count }}
                                                            {{ __('pm::report.invoice.invoices') }})
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <div class="font-medium text-amber-600 dark:text-amber-400">
                                                        Rp {{ number_format($invoiceByProject->vat_amount, 2, ',', '.') }}
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <div class="font-bold">
                                                        Rp {{ number_format($invoiceByProject->total_amount, 2, ',', '.') }}
                                                    </div>
                                                </td>
                                            </tr>
                                            @php
                                                $sumAmount += $invoiceByProject->sum_amount;
                                                $sumUnpaid += $invoiceByProject->unpaid_amount;
                                                $sumPaid += $invoiceByProject->paid_amount;
                                                $sumTotal += $invoiceByProject->total_amount;
                                                $sumVat += $invoiceByProject->vat_amount;
                                                $countUnpaid += $invoiceByProject->unpaid_count;
                                                $countPaid += $invoiceByProject->paid_count;
                                                $countTotal += $invoiceByProject->total_count;
                                            @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-gray-100 font-bold dark:bg-gray-800">
                                            <td
                                                class="text-right"
                                                colspan="2"
                                            >
                                                <p class="text-lg">
                                                    {{ __('pm::report.invoice.total') }}
                                                </p>
                                            </td>
                                            <td class="text-center">
                                                <p class="text-lg">{{ $countTotal }}</p>
                                            </td>
                                            <td class="text-right">
                                                <p class="text-lg">
                                                    Rp {{ number_format($sumAmount, 2, ',', '.') }}
                                                </p>
                                            </td>
                                            <td class="text-right">
                                                <p class="text-red-600 dark:text-red-400">
                                                    Rp {{ number_format($sumUnpaid, 2, ',', '.') }}
                                                </p>
                                                <p class="text-xs text-gray-500">({{ $countUnpaid }})</p>
                                            </td>
                                            <td class="text-right">
                                                <p class="text-green-600 dark:text-green-400">
                                                    Rp {{ number_format($sumPaid, 2, ',', '.') }}
                                                </p>
                                                <p class="text-xs text-gray-500">({{ $countPaid }})</p>
                                            </td>
                                            <td class="text-right">
                                                <p class="text-amber-600 dark:text-amber-400">
                                                    Rp {{ number_format($sumVat, 2, ',', '.') }}
                                                </p>
                                            </td>
                                            <td class="text-right">
                                                <p class="text-lg">
                                                    Rp {{ number_format($sumTotal, 2, ',', '.') }}
                                                </p>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
