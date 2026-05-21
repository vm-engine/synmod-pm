<div class="flex h-full flex-col">
    <!-- Confirmation Dialog -->
    <x-synapse-confirm-dialog />

    @if ($invoice)
        <!-- Modal Header with Issuer Header Image -->
        <div class="shrink-0 border-b border-gray-200 dark:border-gray-700">
            @if ($invoice->issuer?->header_image_url)
                <div class="w-full">
                    <img
                        class="h-auto w-full object-contain"
                        src="{{ $invoice->issuer->header_image_url }}"
                        alt="{{ $invoice->issuer->name }}"
                    >
                </div>
            @else
                <div class="flex items-center justify-between p-5">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ __('pm::invoice.detail_title') }}
                    </h3>
                </div>
            @endif
            <div class="flex items-center justify-between px-5 py-3">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ __('pm::invoice.detail_title') }}: {{ $invoice->invoice_number }}
                </h3>
                <button
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                    type="button"
                    @click="$dispatch('close-modal-invoice-detail')"
                >
                    <span class="fa-solid fa-times text-xl"></span>
                </button>
            </div>
        </div>

        <!-- Modal Body (Scrollable) -->
        <div class="max-h-[65vh] flex-1 space-y-6 overflow-y-auto p-6">
            <!-- Invoice Data Card -->
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <span
                            class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('pm::invoice.spk_number') }}</span>
                        <p class="font-mono text-gray-900 dark:text-white">{{ $invoice->spkDocument?->number ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <span
                            class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('pm::invoice.invoice_number') }}</span>
                        <p class="font-mono text-gray-900 dark:text-white">{{ $invoice->invoice_number }}</p>
                    </div>
                    <div>
                        <span
                            class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('pm::invoice.issue_date') }}</span>
                        <p class="text-gray-900 dark:text-white">{{ $invoice->issue_date?->format('d M Y') ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <span
                            class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('pm::invoice.due_date') }}</span>
                        <p class="text-gray-900 dark:text-white">{{ $invoice->due_date?->format('d M Y') ?? '-' }}</p>
                    </div>
                    <div>
                        <span
                            class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('pm::invoice.recipient') }}</span>
                        <p class="text-gray-900 dark:text-white">{{ $invoice->recipient }}</p>
                    </div>
                    <div>
                        <span
                            class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('pm::invoice.attention') }}</span>
                        <p class="text-gray-900 dark:text-white">{{ $invoice->attention ?: '-' }}</p>
                    </div>
                    <div class="md:col-span-2 lg:col-span-3">
                        <span
                            class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('pm::invoice.address') }}</span>
                        <p class="text-gray-900 dark:text-white">{{ $invoice->address }}</p>
                    </div>
                    <div>
                        <span
                            class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('pm::invoice.issuer') }}</span>
                        <p class="text-gray-900 dark:text-white">{{ $invoice->issuer?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <span
                            class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('pm::invoice.bank_account') }}</span>
                        <p class="text-gray-900 dark:text-white">{{ $invoice->bankAccount?->name ?? '-' }} -
                            {{ $invoice->bankAccount?->number ?? '' }}</p>
                    </div>
                    @if ($invoice->has_signature && $invoice->signature)
                        <div>
                            <span
                                class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('pm::invoice.signature') }}</span>
                            <p class="text-gray-900 dark:text-white">{{ $invoice->signature->name }} -
                                {{ $invoice->signature->position }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Invoice Items Table -->
            <div class="rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                    <h4 class="font-medium text-gray-900 dark:text-white">{{ __('pm::invoice.items_title') }}</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="datatable min-w-full">
                        <thead>
                            <tr>
                                <th class="w-1">
                                    <p>No.</p>
                                </th>
                                <th class="min-w-62.5">
                                    <p>{{ __('pm::invoice.item_name') }}</p>
                                </th>
                                <th class="w-[220px]">
                                    <p>{{ __('pm::invoice.item_amount') }}</p>
                                </th>
                                <th class="w-[150px]">
                                    <p>{{ __('pm::invoice.item_discount') }}</p>
                                </th>
                                <th class="w-[220px]">
                                    <p>{{ __('pm::invoice.item_total') }}</p>
                                </th>
                                <th class="w-[120px]">
                                    <p>{{ __('pm::labels.actions') }}</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach ($this->items as $item)
                                @php
                                    $itemTotal = $item->amount;
                                    if ($item->include_discount && $item->discount_percentage > 0) {
                                        $itemTotal = $item->amount - ($item->amount * $item->discount_percentage) / 100;
                                    }
                                @endphp
                                <tr wire:key="item-{{ $item->id }}">
                                    <td>
                                        <div class="flex items-center">
                                            <p>{{ $i }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            @if ($editItemId == $item->id)
                                                <textarea
                                                    class="form-input min-h-[60px] w-full"
                                                    wire:model="editItemName"
                                                    placeholder="{{ __('pm::invoice.item_name_placeholder') }}"
                                                    wire:loading.attr="disabled"
                                                    rows="2"
                                                ></textarea>
                                            @else
                                                <p class="whitespace-pre-wrap">{{ $item->name }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            @if ($editItemId == $item->id)
                                                <x-synapse-currency
                                                    class="w-full"
                                                    wire:model="editItemAmount"
                                                    currency="Rp"
                                                />
                                            @else
                                                <p class="font-mono">Rp {{ number_format($item->amount, 0, ',', '.') }}
                                                </p>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            @if ($editItemId == $item->id)
                                                <input
                                                    class="h-4 w-4 rounded border-gray-300"
                                                    type="checkbox"
                                                    wire:model.live="editItemIncludeDiscount"
                                                >
                                                @if ($editItemIncludeDiscount)
                                                    <input
                                                        class="form-input no-spin w-16 text-center"
                                                        type="number"
                                                        step="0.05"
                                                        wire:model="editItemDiscountPercentage"
                                                        min="0"
                                                        max="100"
                                                        x-on:focus="if (parseFloat($event.target.value) === 0) $event.target.select()"
                                                    >
                                                    <span>%</span>
                                                @endif
                                            @else
                                                @if ($item->include_discount && $item->discount_percentage > 0)
                                                    <span class="text-red-600">{{ $item->discount_percentage }}%</span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            <p class="font-mono">Rp {{ number_format($itemTotal, 0, ',', '.') }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-1">
                                            @if ($editItemId == $item->id)
                                                <button
                                                    class="btn-icon success has-tooltip group"
                                                    type="button"
                                                    wire:click="updateItem({{ $item->id }})"
                                                    wire:loading.attr="disabled"
                                                >
                                                    <span
                                                        class="fa-solid fa-check"
                                                        wire:loading.class.remove="fa-check"
                                                        wire:loading.class="fa-spinner fa-spin"
                                                        wire:target="updateItem({{ $item->id }})"
                                                    ></span>
                                                    <span class="tooltip">{{ __('pm::labels.save') }}</span>
                                                </button>
                                                <button
                                                    class="btn-icon danger has-tooltip group"
                                                    type="button"
                                                    wire:click="cancelEditItem"
                                                >
                                                    <span class="fa-solid fa-xmark"></span>
                                                    <span class="tooltip">{{ __('pm::labels.cancel') }}</span>
                                                </button>
                                            @else
                                                @canAccess('pm.invoice.update')
                                                <button
                                                    class="btn-icon warning has-tooltip group"
                                                    type="button"
                                                    wire:click="editItem({{ $item->id }})"
                                                >
                                                    <span class="fa-solid fa-edit"></span>
                                                    <span class="tooltip">{{ __('pm::labels.edit') }}</span>
                                                </button>
                                                @endcanAccess
                                                @canAccess('pm.invoice.delete')
                                                <button
                                                    class="btn-icon danger has-tooltip group"
                                                    type="button"
                                                    wire:click="$dispatch('confirm-dialog', {
                                                            title: '{{ __('pm::invoice.delete_item_title') }}',
                                                            message: '{{ __('pm::invoice.delete_item_message') }}',
                                                            confirmText: '{{ __('pm::labels.yes_delete') }}',
                                                            cancelText: '{{ __('pm::labels.cancel') }}',
                                                            confirmColor: 'danger',
                                                            icon: 'fa-solid fa-trash',
                                                            wireMethod: 'deleteItem',
                                                            wireParams: ['{{ $item->delete_token }}'],
                                                            wireComponent: '{{ $this->getId() }}'
                                                        })"
                                                >
                                                    <span class="fa-solid fa-trash"></span>
                                                    <span class="tooltip">{{ __('pm::labels.delete') }}</span>
                                                </button>
                                                @endcanAccess
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @php $i++; @endphp
                            @endforeach
                            <!-- New Item Row -->
                            @canAccess('pm.invoice.create')
                            <tr class="bg-gray-50 dark:bg-gray-800">
                                <td>
                                    <p>{{ $i }}</p>
                                </td>
                                <td>
                                    <div>
                                        <textarea
                                            class="form-input @error('newItemName') border-red-500 @enderror min-h-[60px] w-full"
                                            wire:model="newItemName"
                                            placeholder="{{ __('pm::invoice.item_name_placeholder') }}"
                                            wire:loading.attr="disabled"
                                            rows="2"
                                        ></textarea>
                                        @error('newItemName')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <x-synapse-currency
                                            class="@error('newItemAmount') border-red-500 @enderror w-full"
                                            wire:model="newItemAmount"
                                            currency="Rp"
                                        />
                                        @error('newItemAmount')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <input
                                            class="h-4 w-4 rounded border-gray-300"
                                            type="checkbox"
                                            wire:model.live="newItemIncludeDiscount"
                                        >
                                        @if ($newItemIncludeDiscount)
                                            <input
                                                class="form-input no-spin w-16 text-center"
                                                type="number"
                                                step="0.05"
                                                wire:model="newItemDiscountPercentage"
                                                min="0"
                                                max="100"
                                                x-on:focus="if (parseFloat($event.target.value) === 0) $event.target.select()"
                                            >
                                            <span>%</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <p class="text-gray-400">-</p>
                                </td>
                                <td>
                                    <button
                                        class="btn-icon success has-tooltip group"
                                        type="button"
                                        wire:click="storeItem"
                                        wire:loading.attr="disabled"
                                    >
                                        <span
                                            class="fa-solid fa-plus"
                                            wire:loading.class.remove="fa-plus"
                                            wire:loading.class="fa-spinner fa-spin"
                                            wire:target="storeItem"
                                        ></span>
                                        <span class="tooltip">{{ __('pm::invoice.add_item') }}</span>
                                    </button>
                                </td>
                            </tr>
                            @endcanAccess
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Invoice Totals -->
            <div class="rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                    <h4 class="font-medium text-gray-900 dark:text-white">{{ __('pm::invoice.totals_title') }}</h4>
                </div>
                <div class="p-4">
                    <div class="space-y-3">
                        <!-- Sub Total (Items) -->
                        <div class="flex items-center justify-between">
                            <span
                                class="text-gray-600 dark:text-gray-400">{{ __('pm::invoice.sub_total_items') }}</span>
                            <span class="font-mono font-medium text-gray-900 dark:text-white">Rp
                                {{ number_format($this->subTotal, 0, ',', '.') }}</span>
                        </div>

                        <!-- Invoice Discount -->
                        <div
                            class="flex items-center justify-between border-t border-gray-100 pt-3 dark:border-gray-700">
                            <div class="flex items-center gap-3">
                                <input
                                    class="h-4 w-4 rounded border-gray-300"
                                    id="includeDiscount"
                                    type="checkbox"
                                    wire:model.live.debounce.500ms="includeDiscount"
                                >
                                <label
                                    class="text-gray-600 dark:text-gray-400"
                                    for="includeDiscount"
                                >{{ __('pm::invoice.include_discount') }}</label>
                                @if ($includeDiscount)
                                    <input
                                        class="form-input no-spin w-20 text-center"
                                        type="number"
                                        step="0.05"
                                        wire:model.live.debounce.500ms="discountPercentage"
                                        min="0"
                                        max="100"
                                        x-on:focus="if (parseFloat($event.target.value) === 0) $event.target.select()"
                                    >
                                    <span>%</span>
                                @endif
                            </div>
                            @if ($includeDiscount && $discountPercentage > 0)
                                <span class="font-mono font-medium text-red-600">- Rp
                                    {{ number_format($this->invoiceDiscountAmount, 0, ',', '.') }}</span>
                            @endif
                        </div>

                        <!-- After Discount -->
                        @if ($includeDiscount && $discountPercentage > 0)
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-gray-600 dark:text-gray-400">{{ __('pm::invoice.after_discount') }}</span>
                                <span class="font-mono font-medium text-gray-900 dark:text-white">Rp
                                    {{ number_format($this->afterDiscountAmount, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <!-- DPP Option (VAT base — calculated from after-discount sub total) -->
                        <div
                            class="flex items-center justify-between border-t border-gray-100 pt-3 dark:border-gray-700">
                            <div class="flex items-center gap-3">
                                <input
                                    class="h-4 w-4 rounded border-gray-300"
                                    id="includeDpp"
                                    type="checkbox"
                                    wire:model.live.debounce.500ms="includeDpp"
                                >
                                <label
                                    class="text-gray-600 dark:text-gray-400"
                                    for="includeDpp"
                                >{{ __('pm::invoice.include_dpp') }} {{ $this->dppLabel }}</label>
                            </div>
                            @if ($includeDpp)
                                <span class="font-mono font-medium text-gray-900 dark:text-white">Rp
                                    {{ number_format($this->dppAmount, 0, ',', '.') }}</span>
                            @endif
                        </div>

                        <!-- VAT -->
                        <div
                            class="flex items-center justify-between border-t border-gray-100 pt-3 dark:border-gray-700">
                            <div class="flex items-center gap-3">
                                <input
                                    class="h-4 w-4 rounded border-gray-300"
                                    id="includeVat"
                                    type="checkbox"
                                    wire:model.live.debounce.500ms="includeVat"
                                >
                                <label
                                    class="text-gray-600 dark:text-gray-400"
                                    for="includeVat"
                                >{{ __('pm::invoice.include_vat') }} (PPN)</label>
                                @if ($includeVat)
                                    <input
                                        class="form-input no-spin w-20 text-center"
                                        type="number"
                                        step="0.05"
                                        wire:model.live.debounce.500ms="vatPercentage"
                                        min="0"
                                        max="100"
                                        x-on:focus="if (parseFloat($event.target.value) === 0) $event.target.select()"
                                    >
                                    <span>%</span>
                                @endif
                            </div>
                            @if ($includeVat)
                                <span class="font-mono font-medium text-gray-900 dark:text-white">Rp
                                    {{ number_format($this->vatAmount, 0, ',', '.') }}</span>
                            @endif
                        </div>

                        <!-- Grand Total -->
                        <div
                            class="flex items-center justify-between border-t-2 border-gray-300 pt-3 dark:border-gray-600">
                            <span
                                class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('pm::invoice.grand_total') }}</span>
                            <span class="font-mono text-lg font-bold text-gray-900 dark:text-white">Rp
                                {{ number_format($this->grandTotal, 0, ',', '.') }}</span>
                        </div>

                        <!-- Notes -->
                        <div class="border-t border-gray-100 pt-3 dark:border-gray-700">
                            <label
                                class="mb-2 block text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('pm::invoice.notes') }}</label>
                            <textarea
                                class="form-input w-full"
                                wire:model="invoiceNotes"
                                rows="3"
                                placeholder="{{ __('pm::invoice.notes_placeholder') }}"
                            ></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer (Sticky) -->
        <div
            class="flex shrink-0 items-center justify-between gap-3 border-t border-gray-200 p-5 dark:border-gray-700">
            <div class="flex items-center gap-2">
                @if ($invoice->invoice_document_id)
                    <a
                        class="btn info"
                        href="{{ route('backend.pm.invoice.download', $invoice->id) }}"
                        target="_blank"
                    >
                        <span class="fa-solid fa-download mr-1"></span>
                        {{ __('pm::invoice.download') }}
                    </a>
                    <a
                        class="btn secondary"
                        href="{{ route('backend.pm.invoice.print', $invoice->id) }}"
                        target="_blank"
                    >
                        <span class="fa-solid fa-print mr-1"></span>
                        {{ __('pm::invoice.print') }}
                    </a>
                @endif
            </div>
            <div class="flex items-center gap-3">
                <button
                    class="btn secondary"
                    type="button"
                    @click="$dispatch('close-modal-invoice-detail')"
                >
                    <span class="fa-solid fa-times mr-1"></span>
                    {{ __('pm::labels.close') }}
                </button>
                @canAccess('pm.invoice.update')
                <button
                    class="btn primary"
                    type="button"
                    wire:click="saveAndGeneratePdf"
                >
                    <span
                        class="fa-solid fa-file-pdf mr-1"
                        wire:loading.class.remove="fa-file-pdf"
                        wire:loading.class="fa-spinner fa-spin"
                        wire:target="saveAndGeneratePdf"
                    ></span>
                    {{ __('pm::invoice.save_and_generate_pdf') }}
                </button>
                @endcanAccess
            </div>
        </div>
    @else
        <div class="flex items-center justify-center p-12">
            <div class="text-center">
                <span class="fa-solid fa-spinner fa-spin text-4xl text-gray-400"></span>
                <p class="mt-2 text-gray-500">{{ __('pm::labels.loading') }}</p>
            </div>
        </div>
    @endif
</div>
