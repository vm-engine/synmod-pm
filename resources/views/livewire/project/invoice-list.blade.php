<div
    x-data="{ drawerOpen: false }"
    x-on:invoice-refresh.window="$wire.$refresh()"
>
    <!-- Confirmation Dialog -->
    <x-synapse-confirm-dialog />

    <!-- Invoice List -->
    <div class="space-y-5 sm:space-y-6">

        <div class="dark:bg-white/3 rounded-2xl border border-gray-200 bg-white dark:border-gray-800">

            <div class="flex justify-between">
                <div class="px-5 py-4 sm:px-6 sm:py-5">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        {{ __('pm::invoice.list_title') }}
                    </h3>
                </div>
                <div class="my-auto flex gap-3 px-5">
                    @if ($project?->hasSPKDocument())
                        @canAccess('pm.invoice.create')
                        <div>
                            <button
                                class="btn primary"
                                type="button"
                                @click="$dispatch('open-modal-invoice-form'); $wire.dispatch('create-invoice', { projectId: {{ $projectId }} })"
                            >
                                <span class="fa-solid fa-plus"></span>
                                {{ __('pm::invoice.add_invoice') }}
                            </button>
                        </div>
                        @endcanAccess
                    @endif
                    <div class="input-group">
                        <button
                            class="input-group-item right btn"
                            type="button"
                            title="{{ __('pm::invoice.advanced_filter') }}"
                            @click="drawerOpen = true"
                        ><span class="fa-solid fa-filter"></span></button>
                        <input
                            class="form-input p-2"
                            id="q"
                            name="q"
                            type="text"
                            placeholder="{{ __('pm::invoice.search_placeholder') }}"
                            wire:model.live.debounce="q"
                        >
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-100 dark:border-gray-800">
                <div class="max-w-full overflow-x-auto">
                    <table
                        class="datatable min-w-full"
                        id="dataTable"
                        width="100%"
                        cellspacing="0"
                    >
                        <thead>
                            <tr>
                                <th class="w-1">
                                    <p>{{ __('pm::invoice.col_no') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::invoice.col_invoice_code') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::invoice.col_total') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::invoice.col_vat') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::invoice.col_issue_date') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::invoice.col_due_date') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::invoice.col_status') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::invoice.col_issuer') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::invoice.col_creator') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::invoice.col_actions') }}</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @forelse ($this->invoiceList as $invoice)
                                @php
                                    $status = $this->getPaymentStatus($invoice);
                                @endphp
                                <tr
                                    wire:key="invoice-{{ $invoice->id }}"
                                    @class(['bg-gray-200' => $invoice->archived])
                                >
                                    <td>
                                        <div class="flex items-center">
                                            <p>{{ ($this->invoiceList->currentPage() - 1) * $limit + $i }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            <p class="font-mono font-medium">{{ $invoice->invoice_number }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            <p class="font-mono">
                                                {{ number_format($invoice->total_amount, 0, ',', '.') }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            @if ($invoice->include_vat)
                                                <p class="font-mono">
                                                    {{ number_format($invoice->vat_amount, 0, ',', '.') }}
                                                    ({{ $invoice->vat_percentage }}%)
                                                </p>
                                            @else
                                                <p>No VAT</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            <p>{{ $invoice->issue_date?->format('d M Y') ?? '-' }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            <p>{{ $invoice->due_date?->format('d M Y') ?? '-' }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            <span
                                                class="{{ $status['class'] }} inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium"
                                            >
                                                <span class="fa-solid {{ $status['icon'] }} text-xs"></span>
                                                {{ $status['label'] }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            <p>{{ $invoice->issuer?->name ?? '-' }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            <p>{{ $invoice->creator?->name ?? '-' }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div
                                            class="flex items-center gap-1"
                                            x-data="{ dropdownOpen: false }"
                                        >
                                            @if (!$invoice->archived)
                                                @canAccess('pm.invoice.read')
                                                <button
                                                    class="btn-icon info has-tooltip group"
                                                    type="button"
                                                    @click="$dispatch('open-modal-invoice-detail'); $wire.dispatch('load-invoice-detail', { id: {{ $invoice->id }} })"
                                                >
                                                    <span class="fa-solid fa-eye"></span>
                                                    <span class="tooltip">{{ __('pm::invoice.tooltip_detail') }}</span>
                                                </button>
                                                @endcanAccess

                                                @canAccess('pm.invoice.update')
                                                <button
                                                    class="btn-icon warning has-tooltip group"
                                                    type="button"
                                                    @click="$dispatch('open-modal-invoice-form'); $wire.dispatch('load-invoice', { id: {{ $invoice->id }} })"
                                                >
                                                    <span class="fa-solid fa-edit"></span>
                                                    <span class="tooltip">{{ __('pm::invoice.tooltip_edit') }}</span>
                                                </button>
                                                @endcanAccess
                                            @endif

                                            @canAccess('pm.invoice.delete')
                                            <button
                                                class="btn-icon danger has-tooltip group"
                                                type="button"
                                                wire:click="$dispatch('confirm-dialog', {
                                                    title: '{{ __('pm::invoice.delete_title') }}',
                                                    message: '{{ __('pm::invoice.delete_message', ['code' => addslashes($invoice->invoice_number)]) }}',
                                                    confirmText: '{{ __('pm::labels.yes_delete') }}',
                                                    cancelText: '{{ __('pm::labels.cancel') }}',
                                                    confirmColor: 'danger',
                                                    icon: 'fa-solid fa-trash',
                                                    wireMethod: 'delete',
                                                    wireParams: ['{{ $invoice->delete_token }}'],
                                                    wireComponent: '{{ $this->getId() }}'
                                                })"
                                            >
                                                <span class="fa-solid fa-trash"></span>
                                                <span class="tooltip">{{ __('pm::invoice.tooltip_delete') }}</span>
                                            </button>
                                            @endcanAccess

                                            <!-- Dropdown Menu -->
                                            <div
                                                class="relative"
                                                x-data="{
                                                    dropdownPosition: { top: 0, left: 0 },
                                                    updatePosition() {
                                                        const btn = this.$refs.dropdownBtn;
                                                        const menu = this.$refs.dropdownMenu;
                                                        const rect = btn.getBoundingClientRect();
                                                        const menuHeight = 300; // Approximate dropdown height
                                                        const menuWidth = 192; // w-48 = 12rem = 192px
                                                        const viewportHeight = window.innerHeight;
                                                        const viewportWidth = window.innerWidth;

                                                        // Check if there's enough space below
                                                        const spaceBelow = viewportHeight - rect.bottom;
                                                        const spaceAbove = rect.top;

                                                        // Position vertically (prefer below, use above if not enough space)
                                                        let top;
                                                        if (spaceBelow >= menuHeight || spaceBelow > spaceAbove) {
                                                            top = rect.bottom + window.scrollY + 8;
                                                        } else {
                                                            top = rect.top + window.scrollY - menuHeight - 8;
                                                        }

                                                        // Position horizontally (align to right of button)
                                                        let left = rect.right + window.scrollX - menuWidth;

                                                        // Ensure dropdown doesn't go off the left edge
                                                        if (left < 0) {
                                                            left = 8;
                                                        }

                                                        // Ensure dropdown doesn't go off the right edge
                                                        if (left + menuWidth > viewportWidth) {
                                                            left = viewportWidth - menuWidth - 8;
                                                        }

                                                        this.dropdownPosition = { top, left };
                                                    }
                                                }"
                                                @click.outside="dropdownOpen = false"
                                            >
                                                <button
                                                    class="btn-icon secondary has-tooltip group"
                                                    type="button"
                                                    x-ref="dropdownBtn"
                                                    @click="dropdownOpen = !dropdownOpen; if(dropdownOpen) updatePosition()"
                                                >
                                                    <span class="fa-solid fa-ellipsis-v"></span>
                                                    <span class="tooltip">{{ __('pm::invoice.tooltip_more') }}</span>
                                                </button>

                                                <template x-teleport="body">
                                                    <div
                                                        class="icon-dropdown"
                                                        x-ref="dropdownMenu"
                                                        x-show="dropdownOpen"
                                                        :style="'top: ' + dropdownPosition.top + 'px; left: ' + dropdownPosition
                                                            .left + 'px;'"
                                                        x-transition:enter="transition ease-out duration-100"
                                                        x-transition:enter-start="transform opacity-0 scale-95"
                                                        x-transition:enter-end="transform opacity-100 scale-100"
                                                        x-transition:leave="transition ease-in duration-75"
                                                        x-transition:leave-start="transform opacity-100 scale-100"
                                                        x-transition:leave-end="transform opacity-0 scale-95"
                                                        x-cloak
                                                        @click.outside="dropdownOpen = false"
                                                    >
                                                        @if ($invoice->invoice_document_id)
                                                            <button
                                                                class="icon-dropdown-item"
                                                                type="button"
                                                                @click="dropdownOpen = false; $dispatch('open-modal-receipt-detail'); $wire.dispatch('load-receipt-detail', { id: {{ $invoice->id }} })"
                                                            >
                                                                <span class="fa-solid fa-receipt w-4"></span>
                                                                {{ __('pm::invoice.receipts') }}
                                                            </button>
                                                        @endif
                                                        @canAccess('pm.invoice.read')
                                                        @if ($invoice->invoice_document_id)
                                                            <a
                                                                class="icon-dropdown-item"
                                                                href="{{ route('backend.pm.invoice.download', $invoice->id) }}"
                                                                target="_blank"
                                                            >
                                                                <span class="fa-solid fa-download w-4"></span>
                                                                {{ __('pm::invoice.download') }}
                                                            </a>
                                                        @endif
                                                        <a
                                                            class="icon-dropdown-item"
                                                            href="{{ route('backend.pm.invoice.print', $invoice->id) }}"
                                                            target="_blank"
                                                        >
                                                            <span class="fa-solid fa-print w-4"></span>
                                                            {{ __('pm::invoice.print') }}
                                                        </a>
                                                        @endcanAccess
                                                        @canAccess('pm.invoice.update')
                                                        @if ($invoice->archived)
                                                            <button
                                                                class="icon-dropdown-item"
                                                                type="button"
                                                                wire:click="$dispatch('confirm-dialog', {
                                                                    title: '{{ __('pm::invoice.unarchive_title') }}',
                                                                    message: '{{ __('pm::invoice.unarchive_message', ['code' => addslashes($invoice->invoice_number)]) }}',
                                                                    confirmText: '{{ __('pm::labels.yes_unarchive') }}',
                                                                    cancelText: '{{ __('pm::labels.cancel') }}',
                                                                    confirmColor: 'warning',
                                                                    icon: 'fa-solid fa-box-open',
                                                                    wireMethod: 'archive',
                                                                    wireParams: ['{{ $invoice->delete_token }}', true],
                                                                    wireComponent: '{{ $this->getId() }}'
                                                                })"
                                                            >
                                                                <span class="fa-solid fa-box-open"></span>
                                                                {{ __('pm::invoice.unarchive') }}
                                                            </button>
                                                        @else
                                                            <button
                                                                class="icon-dropdown-item"
                                                                type="button"
                                                                wire:click="$dispatch('confirm-dialog', {
                                                                    title: '{{ __('pm::invoice.archive_title') }}',
                                                                    message: '{{ __('pm::invoice.archive_message', ['code' => addslashes($invoice->invoice_number)]) }}',
                                                                    confirmText: '{{ __('pm::labels.yes_archive') }}',
                                                                    cancelText: '{{ __('pm::labels.cancel') }}',
                                                                    confirmColor: 'danger',
                                                                    icon: 'fa-solid fa-trash',
                                                                    wireMethod: 'archive',
                                                                    wireParams: ['{{ $invoice->delete_token }}'],
                                                                    wireComponent: '{{ $this->getId() }}'
                                                                })"
                                                            >
                                                                <span class="fa-solid fa-archive"></span>
                                                                {{ __('pm::invoice.archive') }}
                                                            </button>
                                                        @endif
                                                        @endcanAccess
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @php
                                    $i++;
                                @endphp
                            @empty
                                <tr>
                                    <td
                                        class="py-12 text-center"
                                        colspan="10"
                                    >
                                        <span
                                            class="fa-solid fa-file-invoice text-6xl text-gray-300 dark:text-gray-600"
                                        ></span>
                                        <p class="mt-4 text-gray-500 dark:text-gray-400">
                                            {{ __('pm::invoice.no_data') }}
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="flex gap-2 p-3">
                <div class="flex-none p-1">
                    <select
                        class="shadow-theme-sm rounded-sm p-1"
                        id="limit"
                        name="limit"
                        wire:model.live="limit"
                    >
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="pagination-container flex-1 grow">
                    {{ $this->invoiceList->links() }}
                </div>
            </div>
        </div>
    </div>

    @php
        $advanceTitle = '<span class="fa-solid fa-filter mr-2"></span>' . __('pm::invoice.advanced_filter');
    @endphp
    <x-synapse-drawer :title="$advanceTitle">
        <div class="space-y-6">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('pm::invoice.filter_status') }}
                </label>
                <x-synapse-select
                    id="filterStatus"
                    name="filterStatus"
                    wire:model.live="filterStatus"
                    :options="[
                        '' => __('pm::invoice.all'),
                        'paid' => __('pm::invoice.status_paid'),
                        'unpaid' => __('pm::invoice.status_unpaid'),
                        'partial' => __('pm::invoice.status_partial'),
                    ]"
                />
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('pm::invoice.filter_year') }}
                </label>
                <x-synapse-select
                    id="filterYear"
                    name="filterYear"
                    wire:model.live="filterYear"
                    :options="collect(['' => __('pm::invoice.all')])
                        ->union(collect($this->availableYears)->mapWithKeys(fn($y) => [$y => $y]))
                        ->toArray()"
                />
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('pm::invoice.filter_issuer') }}
                </label>
                <x-synapse-adv-select
                    wire-model="filterIssuer"
                    :live="true"
                    :options="$this->issuers"
                    placeholder="{{ __('pm::invoice.select_issuer') }}"
                />
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('pm::invoice.filter_creator') }}
                </label>
                <x-synapse-adv-select
                    wire-model="filterCreator"
                    :live="true"
                    :options="$this->creators"
                    placeholder="{{ __('pm::invoice.select_creator') }}"
                />
            </div>
        </div>
    </x-synapse-drawer>

    <!-- Invoice Form Modal -->
    <x-synapse-modal
        name="invoice-form"
        maxWidth="2xl"
    >
        <livewire:pm::project.invoice-form />
    </x-synapse-modal>

    <!-- Invoice Detail Modal -->
    <x-synapse-modal
        name="invoice-detail"
        maxWidth="7xl"
    >
        <livewire:pm::project.invoice-detail />
    </x-synapse-modal>

    <!-- Receipt Detail Modal -->
    <x-synapse-modal
        name="receipt-detail"
        maxWidth="4xl"
    >
        <livewire:pm::project.receipt-detail />
    </x-synapse-modal>
</div>
