<div class="flex h-full flex-col">
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
                        {{ __('pm::receipt.detail_title') }}
                    </h3>
                </div>
            @endif
            <div class="flex items-center justify-between px-5 py-3">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ __('pm::receipt.detail_title') }}
                </h3>
                <button
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                    type="button"
                    @click="$dispatch('close-modal-receipt-detail')"
                >
                    <span class="fa-solid fa-times text-xl"></span>
                </button>
            </div>
        </div>

        <!-- Modal Body (Scrollable) -->
        <div class="max-h-[65vh] flex-1 space-y-6 overflow-y-auto p-6">
            <!-- Receipt Preview Card -->
            <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                <!-- Receipt Title -->
                <div class="mb-6 text-center">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">KUITANSI</h2>
                    <p class="mt-1 font-mono text-blue-600">{{ $this->receiptNumber }}</p>
                </div>

                <!-- Receipt Content Card -->
                <div class="mb-6 rounded-lg border border-gray-200 p-5 dark:border-gray-600">
                    <!-- Sudah Diterima Dari -->
                    <div class="mb-4 grid grid-cols-3 gap-4">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">Sudah Terima Dari</p>
                            <p class="text-sm italic text-gray-500">Received From</p>
                        </div>
                        <div class="col-span-2">
                            <p class="font-semibold text-gray-900 dark:text-white">: {{ $invoice->client?->company }}
                            </p>
                        </div>
                    </div>

                    <!-- Banyak Uang -->
                    <div class="mb-4 grid grid-cols-3 gap-4">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">Banyak Uang</p>
                            <p class="text-sm italic text-gray-500">Amount Received</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-gray-900 dark:text-white">: *{{ $this->amountText }}*</p>
                        </div>
                    </div>

                    <!-- Untuk Pembayaran -->
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">Untuk Pembayaran</p>
                            <p class="text-sm italic text-gray-500">In Payment Of</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-gray-900 dark:text-white">:</p>
                            <ul class="ml-4 list-disc text-gray-900 dark:text-white">
                                @foreach ($this->items as $item)
                                    <li>{{ $item->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Amount Box and Signature -->
                <div class="flex items-end justify-between">
                    <!-- Amount Box -->
                    <div class="rounded border-2 border-gray-900 px-6 py-3 dark:border-gray-300">
                        @php
                            $cf = app(\VmEngine\Pm\Services\CurrencyFormatter::class);
                        @endphp
                        <p class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ $cf->format((float) $invoice->total_amount, $invoice->currency ?? 'IDR') }}
                        </p>
                    </div>

                    <!-- Signature Section -->
                    <div class="text-right">
                        @if ($invoice->signature)
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $invoice->signature->name }}</p>
                            <p class="text-gray-600 dark:text-gray-400">{{ $invoice->signature->position }}</p>
                            <p class="text-gray-600 dark:text-gray-400">{{ $invoice->issuer?->name }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Status Section (only if receipt exists) -->
            @if ($this->hasReceipt)
                <div class="rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                        <h4 class="font-medium text-gray-900 dark:text-white">{{ __('pm::receipt.payment_status') }}
                        </h4>
                    </div>
                    <div class="space-y-4 p-4">
                        <!-- Is Paid Checkbox -->
                        <div class="flex items-center gap-3">
                            <input
                                class="h-5 w-5 rounded border-gray-300"
                                id="isPaid"
                                type="checkbox"
                                wire:model.live="isPaid"
                            >
                            <label
                                class="font-medium text-gray-900 dark:text-white"
                                for="isPaid"
                            >
                                {{ __('pm::receipt.mark_as_paid') }}
                            </label>
                            @if ($invoice->is_paid)
                                <span
                                    class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800"
                                >
                                    <span class="fa-solid fa-check mr-1"></span>
                                    {{ __('pm::receipt.paid') }}
                                </span>
                            @endif
                        </div>

                        <!-- Payment Fields (shown when isPaid is checked) -->
                        @if ($isPaid)
                            <div class="ml-8 space-y-4 border-l-2 border-gray-200 pl-4 dark:border-gray-600">
                                <!-- Paid At DateTime -->
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ __('pm::receipt.paid_at') }}
                                    </label>
                                    @php
                                        $flatpickr = json_encode([
                                            'enableTime' => true,
                                            'dateFormat' => 'Y-m-d H:i',
                                            'time_24hr' => true,
                                        ]);
                                    @endphp
                                    <x-synapse-datepicker
                                        id="paidAt"
                                        name="paidAt"
                                        :flatpickr="$flatpickr"
                                        wire:model="paidAt"
                                    />
                                    @error('paidAt')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Payment Proof Upload -->
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ __('pm::receipt.payment_proof') }}
                                    </label>
                                    @if ($invoice->payment_proof)
                                        <div
                                            class="mb-2 flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                            <span class="fa-solid fa-file"></span>
                                            <span>{{ __('pm::receipt.current_file') }}:
                                                {{ basename($invoice->payment_proof) }}</span>
                                            <a
                                                class="text-blue-600 hover:underline"
                                                href="{{ route('backend.pm.receipt.payment-proof', $invoice->id) }}"
                                                target="_blank"
                                            >
                                                <span class="fa-solid fa-eye"></span>
                                                {{ __('pm::labels.view') }}
                                            </a>
                                        </div>
                                    @endif
                                    <input
                                        class="form-input w-full"
                                        type="file"
                                        wire:model="paymentProof"
                                        accept=".pdf,.jpg,.jpeg,.png"
                                    >
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ __('pm::receipt.payment_proof_help') }}
                                    </p>
                                    @error('paymentProof')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Save Payment Status Button -->
                                <div>
                                    <button
                                        class="btn success"
                                        type="button"
                                        wire:click="savePaymentStatus"
                                        wire:loading.attr="disabled"
                                    >
                                        <span
                                            class="fa-solid fa-save mr-1"
                                            wire:loading.class.remove="fa-save"
                                            wire:loading.class="fa-spinner fa-spin"
                                            wire:target="savePaymentStatus"
                                        ></span>
                                        {{ __('pm::receipt.save_payment_status') }}
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Modal Footer (Sticky) -->
        <div class="flex shrink-0 items-center justify-between gap-3 border-t border-gray-200 p-5 dark:border-gray-700">
            <div class="flex items-center gap-2">
                @if ($this->hasReceipt)
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
                            {{ __('pm::receipt.download') }}
                            <span class="fa-solid fa-caret-down ml-1"></span>
                        </button>
                        <div
                            class="absolute bottom-full z-20 mb-1 w-48 rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800"
                            x-show="open"
                            x-cloak
                        >
                            <a
                                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700"
                                href="{{ route('backend.pm.receipt.download', ['id' => $invoice->id, 'lang' => 'id']) }}"
                                target="_blank"
                            >{{ __('pm::invoice.lang_indonesian') }}</a>
                            <a
                                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700"
                                href="{{ route('backend.pm.receipt.download', ['id' => $invoice->id, 'lang' => 'en']) }}"
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
                            {{ __('pm::receipt.print') }}
                            <span class="fa-solid fa-caret-down ml-1"></span>
                        </button>
                        <div
                            class="absolute bottom-full z-20 mb-1 w-48 rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800"
                            x-show="open"
                            x-cloak
                        >
                            <a
                                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700"
                                href="{{ route('backend.pm.receipt.print', ['id' => $invoice->id, 'lang' => 'id']) }}"
                                target="_blank"
                            >{{ __('pm::invoice.lang_indonesian') }}</a>
                            <a
                                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700"
                                href="{{ route('backend.pm.receipt.print', ['id' => $invoice->id, 'lang' => 'en']) }}"
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
                    @click="$dispatch('close-modal-receipt-detail')"
                >
                    <span class="fa-solid fa-times mr-1"></span>
                    {{ __('pm::labels.close') }}
                </button>
                @if (!$this->hasReceipt)
                    @canAccess('pm.invoice.update')
                    <button
                        class="btn primary"
                        type="button"
                        wire:click="generateReceipt"
                        wire:loading.attr="disabled"
                    >
                        <span
                            class="fa-solid fa-file-pdf mr-1"
                            wire:loading.class.remove="fa-file-pdf"
                            wire:loading.class="fa-spinner fa-spin"
                            wire:target="generateReceipt"
                        ></span>
                        {{ __('pm::receipt.generate') }}
                    </button>
                    @endcanAccess
                @endif
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
