<div
    x-data="{ drawerOpen: false }"
    x-on:invoice-refresh.window="$wire.$refresh()"
>
    <!-- Confirmation Dialog -->
    <x-synapse-confirm-dialog />

    <!-- Invoice List -->
    <div class="space-y-5 sm:space-y-6">

        <x-synapse-panel :title="__('pm::invoice.list_title')">
            <x-slot:toolbar>
                @if ($project?->hasSPKDocument())
                    @canAccess('pm.invoice.create')
                    <button
                        class="btn primary"
                        type="button"
                        @click="$dispatch('open-modal-invoice-form')"
                        wire:click="$dispatch('create-invoice', { projectId: {{ $projectId }} })"
                    >
                        <span class="ph ph-plus"></span>
                        {{ __('pm::invoice.add_invoice') }}
                    </button>
                    @endcanAccess
                @endif
                <x-synapse-search-box
                    id="q"
                    name="q"
                    :placeholder="__('pm::invoice.search_placeholder')"
                    wire:model.live.debounce="q"
                >
                    <x-slot:trailing>
                        <button
                            class="btn"
                            type="button"
                            title="{{ __('pm::invoice.advanced_filter') }}"
                            @click="drawerOpen = true"
                        ><span class="ph ph-funnel"></span></button>
                    </x-slot:trailing>
                </x-synapse-search-box>
            </x-slot:toolbar>
            <div class="syn-panel-body">
                <div class="max-w-full overflow-x-auto">
                    <table
                        class="datatable datatable-sm min-w-full"
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
                                    <p>{{ __('pm::invoice.col_dates') }}</p>
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
                                <th class="datatable-col-actions">
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
                                            <p>{{ ($this->invoiceList->currentPage() - 1) * $perPage + $i }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            <p class="font-mono font-medium">{{ $invoice->invoice_number }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $cf = app(\VmEngine\Pm\Services\CurrencyFormatter::class);
                                            $cur = $invoice->currency ?? 'IDR';
                                            $hasBreakdown =
                                                $invoice->include_vat ||
                                                $invoice->include_dpp ||
                                                $invoice->include_discount;
                                        @endphp
                                        <div class="flex flex-col gap-1.5 font-mono">
                                            @if ($hasBreakdown)
                                                <p class="leading-tight">
                                                    <span
                                                        class="syn-field-caption font-sans">{{ __('pm::invoice.sub_total') }}</span>
                                                    <span
                                                        class="block text-gray-600 dark:text-gray-400">{{ $cf->format((float) $invoice->amount, $cur) }}</span>
                                                </p>
                                            @endif
                                            @if ($invoice->include_vat)
                                                <p class="leading-tight">
                                                    <span
                                                        class="syn-field-caption font-sans">{{ __('pm::invoice.vat') }}
                                                        ({{ rtrim(rtrim(number_format((float) $invoice->vat_percentage, 2), '0'), '.') }}%)
                                                    </span>
                                                    <span
                                                        class="block text-gray-600 dark:text-gray-400">{{ $cf->format((float) $invoice->vat_amount, $cur) }}</span>
                                                </p>
                                            @endif
                                            @if ($invoice->include_dpp)
                                                <p class="leading-tight">
                                                    <span
                                                        class="syn-field-caption font-sans">{{ __('pm::invoice.dpp') }}</span>
                                                    <span
                                                        class="block text-gray-600 dark:text-gray-400">{{ $cf->format((float) $invoice->dpp_amount, $cur) }}</span>
                                                </p>
                                            @endif
                                            @if ($invoice->include_discount)
                                                <p class="leading-tight">
                                                    <span
                                                        class="syn-field-caption font-sans">{{ __('pm::invoice.discount') }}</span>
                                                    <span class="block text-gray-600 dark:text-gray-400">-
                                                        {{ $cf->format((float) $invoice->discount_amount, $cur) }}</span>
                                                </p>
                                            @endif
                                            <p class="leading-tight">
                                                <span
                                                    class="syn-field-caption font-sans">{{ __('pm::invoice.grand_total') }}</span>
                                                <span
                                                    class="block text-sm font-semibold text-gray-800 dark:text-white/90"
                                                >{{ $cf->format((float) $invoice->total_amount, $cur) }}</span>
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex flex-col gap-1.5">
                                            <p class="leading-tight">
                                                <span
                                                    class="syn-field-caption">{{ __('pm::invoice.issue_date') }}</span>
                                                <span
                                                    class="block text-gray-600 dark:text-gray-400">{{ $invoice->issue_date?->format('d M Y') ?? '-' }}</span>
                                            </p>
                                            <p class="leading-tight">
                                                <span class="syn-field-caption">{{ __('pm::invoice.due_date') }}</span>
                                                <span
                                                    class="block text-gray-600 dark:text-gray-400">{{ $invoice->due_date?->format('d M Y') ?? '-' }}</span>
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex flex-col items-start gap-1">
                                            <span
                                                class="{{ $status['class'] }} inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium"
                                            >
                                                <span class="fa-solid {{ $status['icon'] }} text-[10px]"></span>
                                                {{ $status['label'] }}
                                            </span>
                                            @if ($invoice->is_paid && $invoice->paid_at)
                                                <p class="leading-tight">
                                                    <span
                                                        class="syn-field-caption">{{ __('pm::invoice.paid_on') }}</span>
                                                    <span
                                                        class="block text-[11px] text-gray-500 dark:text-gray-400">{{ $invoice->paid_at->format('d M Y') }}</span>
                                                </p>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex flex-col gap-1.5">
                                            <p class="leading-tight">{{ $invoice->issuer?->name ?? '-' }}</p>
                                            @if ($invoice->bankAccount)
                                                <p class="leading-tight">
                                                    <span
                                                        class="syn-field-caption">{{ $invoice->bankAccount->bank_name }}</span>
                                                    <span
                                                        class="block font-mono text-gray-600 dark:text-gray-400">{{ $invoice->bankAccount->number }}</span>
                                                </p>
                                            @endif
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
                                            x-data="pmInvoiceDropdown()"
                                        >
                                            @if (!$invoice->archived)
                                                @canAccess('pm.invoice.read')
                                                <button
                                                    class="btn-icon info has-tooltip group"
                                                    type="button"
                                                    @click="$dispatch('open-modal-invoice-detail')"
                                                    wire:click="$dispatch('load-invoice-detail', { id: {{ $invoice->id }} })"
                                                >
                                                    <span class="ph ph-eye"></span>
                                                    <span class="tooltip">{{ __('pm::invoice.tooltip_detail') }}</span>
                                                </button>
                                                @endcanAccess

                                                @canAccess('pm.invoice.update')
                                                <button
                                                    class="btn-icon warning has-tooltip group"
                                                    type="button"
                                                    @click="$dispatch('open-modal-invoice-form')"
                                                    wire:click="$dispatch('load-invoice', { id: {{ $invoice->id }} })"
                                                >
                                                    <span class="ph ph-pencil-simple"></span>
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
                                                <span class="ph ph-trash"></span>
                                                <span class="tooltip">{{ __('pm::invoice.tooltip_delete') }}</span>
                                            </button>
                                            @endcanAccess

                                            <!-- Dropdown Menu -->
                                            <div
                                                class="relative"
                                                @click.outside="dropdownOpen = false"
                                            >
                                                <button
                                                    class="btn-icon secondary has-tooltip group"
                                                    type="button"
                                                    x-ref="dropdownBtn"
                                                    @click="toggleDropdown()"
                                                >
                                                    <span class="ph ph-dots-three-vertical"></span>
                                                    <span class="tooltip">{{ __('pm::invoice.tooltip_more') }}</span>
                                                </button>

                                                <template x-teleport="body">
                                                    <div
                                                        class="syn-icon-dropdown"
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
                                                                class="syn-icon-dropdown-item"
                                                                type="button"
                                                                @click="closeDropdownAndOpenReceipt()"
                                                                wire:click="$dispatch('load-receipt-detail', { id: {{ $invoice->id }} })"
                                                            >
                                                                <span class="ph ph-receipt w-4"></span>
                                                                {{ __('pm::invoice.receipts') }}
                                                            </button>
                                                        @endif
                                                        @canAccess('pm.invoice.read')
                                                        @if ($invoice->invoice_document_id)
                                                            <a
                                                                class="syn-icon-dropdown-item"
                                                                href="{{ route('backend.pm.invoice.download', $invoice->id) }}"
                                                                target="_blank"
                                                            >
                                                                <span class="ph ph-download-simple w-4"></span>
                                                                {{ __('pm::invoice.download') }}
                                                            </a>
                                                        @endif
                                                        <a
                                                            class="syn-icon-dropdown-item"
                                                            href="{{ route('backend.pm.invoice.print', $invoice->id) }}"
                                                            target="_blank"
                                                        >
                                                            <span class="ph ph-printer w-4"></span>
                                                            {{ __('pm::invoice.print') }}
                                                        </a>
                                                        @endcanAccess
                                                        @canAccess('pm.invoice.update')
                                                        @if ($invoice->archived)
                                                            <button
                                                                class="syn-icon-dropdown-item"
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
                                                                <span class="ph ph-package w-4"></span>
                                                                {{ __('pm::invoice.unarchive') }}
                                                            </button>
                                                        @else
                                                            <button
                                                                class="syn-icon-dropdown-item"
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
                                                                <span class="ph ph-archive w-4"></span>
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
                                    <td colspan="10">
                                        <div class="syn-empty-state">
                                            <span class="ph ph-invoice syn-empty-state-icon"></span>
                                            <p class="syn-empty-state-text">{{ __('pm::invoice.no_data') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <x-synapse-per-page-selector :paginator="$this->invoiceList" />
        </x-synapse-panel>
    </div>

    @php
        $advanceTitle = '<span class="fa-solid fa-filter mr-2"></span>' . __('pm::invoice.advanced_filter');
    @endphp
    <x-synapse-drawer :title="$advanceTitle">
        <div class="space-y-6">
            <div>
                <label class="form-label">
                    {{ __('pm::invoice.filter_status') }}
                </label>
                <x-synapse-adv-select
                    wire-model="filterStatus"
                    :live="true"
                    :options="[
                        ['value' => '', 'label' => __('pm::invoice.all')],
                        ['value' => 'paid', 'label' => __('pm::invoice.status_paid')],
                        ['value' => 'unpaid', 'label' => __('pm::invoice.status_unpaid')],
                        ['value' => 'partial', 'label' => __('pm::invoice.status_partial')],
                    ]"
                />
            </div>

            <div>
                <label class="form-label">
                    {{ __('pm::invoice.filter_year') }}
                </label>
                <x-synapse-adv-select
                    wire-model="filterYear"
                    :live="true"
                    :options="array_merge(
                        [['value' => '', 'label' => __('pm::invoice.all')]],
                        collect($this->availableYears)
                            ->map(fn($y) => ['value' => $y, 'label' => (string) $y])
                            ->toArray(),
                    )"
                />
            </div>

            <div>
                <label class="form-label">
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
                <label class="form-label">
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

<script nonce="{{ csp_nonce() }}">
    (() => {
        const register = () => {
            Alpine.data('pmInvoiceDropdown', () => ({
                dropdownOpen: false,
                dropdownPosition: {
                    top: 0,
                    left: 0
                },
                toggleDropdown() {
                    this.dropdownOpen = !this.dropdownOpen;
                    if (this.dropdownOpen) {
                        this.$nextTick(() => this.updatePosition());
                    }
                },
                closeDropdownAndOpenReceipt() {
                    this.dropdownOpen = false;
                    this.$dispatch('open-modal-receipt-detail');
                },
                updatePosition() {
                    const btn = this.$refs.dropdownBtn;
                    const menu = this.$refs.dropdownMenu;
                    const rect = btn.getBoundingClientRect();
                    // Measure the real menu height instead of guessing — item count
                    // varies per invoice (receipts/download are conditional), so a
                    // fixed approximation picks the wrong above/below branch and
                    // overshoots the offset for shorter menus. Call this only after
                    // $nextTick so x-show has already applied to the DOM.
                    const menuHeight = menu.offsetHeight || 300;
                    const menuWidth = 192; // w-48 = 12rem = 192px
                    const viewportHeight = window.innerHeight;
                    const viewportWidth = window.innerWidth;

                    // Check if there's enough space below
                    const spaceBelow = viewportHeight - rect.bottom;
                    const spaceAbove = rect.top;

                    // Position vertically (prefer below, use above if not enough space)
                    // .syn-icon-dropdown is position:fixed, so these are viewport-relative —
                    // do NOT add window.scrollY/scrollX (that's only correct for
                    // position:absolute, document-relative elements). Adding it here
                    // offset the dropdown by the scroll distance, which grew with
                    // page zoom and made the menu appear far from the trigger button.
                    let top;
                    if (spaceBelow >= menuHeight || spaceBelow > spaceAbove) {
                        top = rect.bottom + 8;
                    } else {
                        top = rect.top - menuHeight - 8;
                    }

                    // Position horizontally (align to right of button)
                    let left = rect.right - menuWidth;

                    // Ensure dropdown doesn't go off the left edge
                    if (left < 0) {
                        left = 8;
                    }

                    // Ensure dropdown doesn't go off the right edge
                    if (left + menuWidth > viewportWidth) {
                        left = viewportWidth - menuWidth - 8;
                    }

                    this.dropdownPosition = {
                        top,
                        left
                    };
                }
            }));
        };
        document.addEventListener('alpine:init', register);
        if (window.Alpine) {
            register();
        }
    })();
</script>
