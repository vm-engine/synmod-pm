<div class="flex h-full flex-col">
    <!-- Confirmation Dialog -->
    <x-synapse-confirm-dialog />

    @if ($invoice)
        @php
            $cf = app(\VmEngine\Pm\Services\CurrencyFormatter::class);
            $cur = $currency;
            $curDef = $cf->definition($cur);
        @endphp
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
                                                <div class="input-group w-full">
                                                    <span class="input-group-item left">{{ $curDef['symbol'] }}</span>
                                                    <input
                                                        class="form-input pl-[62px]! w-full"
                                                        type="text"
                                                        wire:model="editItemAmount"
                                                        x-mask:dynamic="$money($input, '{{ $curDef['decimal'] }}', '{{ $curDef['thousands'] }}', {{ (int) $curDef['decimals'] }})"
                                                        x-on:focus="if ($event.target.value == '0') $event.target.select()"
                                                    >
                                                </div>
                                            @else
                                                <p class="font-mono">{{ $cf->format((float) $item->amount, $cur) }}
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
                                            <p class="font-mono">{{ $cf->format((float) $itemTotal, $cur) }}</p>
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
                                        <div class="input-group w-full">
                                            <span class="input-group-item left">{{ $curDef['symbol'] }}</span>
                                            <input
                                                class="form-input pl-[62px]! @error('newItemAmount') border-red-500 @enderror w-full"
                                                type="text"
                                                wire:model="newItemAmount"
                                                x-mask:dynamic="$money($input, '{{ $curDef['decimal'] }}', '{{ $curDef['thousands'] }}', {{ (int) $curDef['decimals'] }})"
                                                x-on:focus="if ($event.target.value == '0') $event.target.select()"
                                            >
                                        </div>
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
                    @php
                        $cf = app(\VmEngine\Pm\Services\CurrencyFormatter::class);
                        $cur = $currency;
                        $taxable = $cf->isTaxable($cur);
                    @endphp
                    <div class="space-y-3">
                        <!-- Currency (fixed on the invoice form; not editable here) -->
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-400">{{ __('pm::invoice.currency') }}</span>
                            <span
                                class="font-mono font-medium text-gray-900 dark:text-white">{{ $cur }}</span>
                        </div>

                        @php
                            $needsFx = strtoupper($cur) !== 'IDR';
                            $rateLocked = (bool) ($invoice->is_paid ?? false);
                            $idrDef = $cf->definition('IDR');
                        @endphp
                        @if ($needsFx)
                            <!-- Exchange Rate -->
                            <div
                                class="flex items-center justify-between border-t border-gray-100 pt-3 dark:border-gray-700">
                                <span
                                    class="text-gray-600 dark:text-gray-400">{{ __('pm::invoice.exchange_rate') }}</span>
                                @if ($rateLocked)
                                    <span class="font-mono font-medium text-gray-900 dark:text-white">
                                        {{ $cf->format($exchangeRate, 'IDR') }}</span>
                                @else
                                    <div class="input-group w-40">
                                        <span class="input-group-item left">{{ $idrDef['symbol'] }}</span>
                                        <input
                                            class="form-input pl-[62px]! w-full text-right"
                                            type="text"
                                            wire:model.live.debounce.500ms="exchangeRateInput"
                                            x-mask:dynamic="$money($input, '{{ $idrDef['decimal'] }}', '{{ $idrDef['thousands'] }}', {{ (int) $idrDef['decimals'] }})"
                                            x-on:focus="if ($event.target.value == '0') $event.target.select()"
                                        >
                                    </div>
                                @endif
                            </div>

                            <!-- Grand Total (IDR) -->
                            <div
                                class="flex items-center justify-between border-t border-gray-100 pt-3 dark:border-gray-700">
                                <span
                                    class="text-gray-600 dark:text-gray-400">{{ __('pm::invoice.grand_total_idr') }}</span>
                                <span class="font-mono font-medium text-gray-900 dark:text-white">
                                    {{ $cf->format($this->grandTotalIdr, 'IDR') }}</span>
                            </div>
                        @endif

                        <!-- Sub Total (Items) -->
                        <div
                            class="flex items-center justify-between border-t border-gray-100 pt-3 dark:border-gray-700">
                            <span
                                class="text-gray-600 dark:text-gray-400">{{ __('pm::invoice.sub_total_items') }}</span>
                            <span class="font-mono font-medium text-gray-900 dark:text-white">
                                {{ $cf->format((float) $this->subTotal, $cur) }}</span>
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
                                <span class="font-mono font-medium text-red-600">-
                                    {{ $cf->format((float) $this->invoiceDiscountAmount, $cur) }}</span>
                            @endif
                        </div>

                        <!-- After Discount -->
                        @if ($includeDiscount && $discountPercentage > 0)
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-gray-600 dark:text-gray-400">{{ __('pm::invoice.after_discount') }}</span>
                                <span class="font-mono font-medium text-gray-900 dark:text-white">
                                    {{ $cf->format((float) $this->afterDiscountAmount, $cur) }}</span>
                            </div>
                        @endif

                        @if ($taxable)
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
                                    <span class="font-mono font-medium text-gray-900 dark:text-white">
                                        {{ $cf->format((float) $this->dppAmount, $cur) }}</span>
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
                                    <span class="font-mono font-medium text-gray-900 dark:text-white">
                                        {{ $cf->format((float) $this->vatAmount, $cur) }}</span>
                                @endif
                            </div>
                        @endif

                        <!-- Grand Total -->
                        <div
                            class="flex items-center justify-between border-t-2 border-gray-300 pt-3 dark:border-gray-600">
                            <span
                                class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('pm::invoice.grand_total') }}</span>
                            <span class="font-mono text-lg font-bold text-gray-900 dark:text-white">
                                {{ $cf->format((float) $this->grandTotal, $cur) }}</span>
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
                    <div
                        class="relative"
                        x-data="{ open: false }"
                        @click.away.capture="open = false"
                    >
                        <button
                            class="btn info"
                            type="button"
                            @click="open = !open"
                        >
                            <span class="fa-solid fa-download mr-1"></span>
                            {{ __('pm::invoice.download') }}
                            <span class="fa-solid fa-caret-down ml-1"></span>
                        </button>
                        <div
                            class="absolute bottom-full z-20 mb-1 w-48 rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800"
                            x-show="open"
                            x-cloak
                        >
                            <a
                                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700"
                                href="{{ route('backend.pm.invoice.download', ['id' => $invoice->id, 'lang' => 'id']) }}"
                                target="_blank"
                            >{{ __('pm::invoice.lang_indonesian') }}</a>
                            <a
                                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700"
                                href="{{ route('backend.pm.invoice.download', ['id' => $invoice->id, 'lang' => 'en']) }}"
                                target="_blank"
                            >{{ __('pm::invoice.lang_english') }}</a>
                        </div>
                    </div>
                    <div
                        class="relative"
                        x-data="{ open: false }"
                        @click.away.capture="open = false"
                    >
                        <button
                            class="btn secondary"
                            type="button"
                            @click="open = !open"
                        >
                            <span class="fa-solid fa-print mr-1"></span>
                            {{ __('pm::invoice.print') }}
                            <span class="fa-solid fa-caret-down ml-1"></span>
                        </button>
                        <div
                            class="absolute bottom-full z-20 mb-1 w-48 rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800"
                            x-show="open"
                            x-cloak
                        >
                            <a
                                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700"
                                href="{{ route('backend.pm.invoice.print', ['id' => $invoice->id, 'lang' => 'id']) }}"
                                target="_blank"
                            >{{ __('pm::invoice.lang_indonesian') }}</a>
                            <a
                                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700"
                                href="{{ route('backend.pm.invoice.print', ['id' => $invoice->id, 'lang' => 'en']) }}"
                                target="_blank"
                            >{{ __('pm::invoice.lang_english') }}</a>
                        </div>
                    </div>
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
