<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta
        http-equiv="Content-Type"
        content="text/html; charset=utf-8"
    />
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        @page {
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #333;
        }

        .header-image {
            width: 100%;
            display: block;
        }

        .content {
            padding: 25px 40px;
        }

        .invoice-to-label {
            font-size: 10pt;
            color: #666;
            margin-bottom: 5px;
        }

        .invoice-to-name {
            font-size: 16pt;
            font-weight: bold;
            color: #1e88e5;
            margin-bottom: 25px;
        }

        .details-section {
            width: 100%;
            margin-bottom: 25px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .details-left {
            width: 50%;
            vertical-align: top;
        }

        .details-right {
            width: 50%;
            vertical-align: top;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
        }

        .detail-table td {
            padding: 5px 0;
            vertical-align: top;
        }

        .detail-table .label {
            width: 100px;
            font-weight: bold;
            color: #333;
        }

        .detail-table .sep {
            width: 15px;
            text-align: center;
        }

        .detail-table .value {
            color: #333;
        }

        /* Card container for items and totals */
        .items-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .items-table thead th {
            color: #1e88e5;
            padding: 10px 5px;
            text-align: left;
            font-weight: normal;
            font-size: 10pt;
            border-bottom: 1px solid #e0e0e0;
        }

        .items-table thead th.text-right {
            text-align: right;
        }

        .items-table thead th.text-center {
            text-align: center;
        }

        .items-table tbody td {
            padding: 15px 5px;
            vertical-align: top;
            border-bottom: 1px solid #e0e0e0;
        }

        .items-table tbody td.text-right {
            text-align: right;
        }

        .items-table tbody td.text-center {
            text-align: center;
        }

        .items-table .description-col {
            width: 60%;
        }

        .items-table .discount-col {
            width: 15%;
            text-align: center;
        }

        .items-table .price-col {
            width: 25%;
            text-align: right;
        }

        .notes-section {
            padding: 12px 0;
            border-bottom: 1px dashed #999;
            margin-bottom: 20px;
            font-size: 9pt;
        }

        .notes-label {
            font-weight: bold;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 10px 15px;
        }

        .totals-table .label-col {
            text-align: right;
            width: 60%;
            color: #333;
        }

        .totals-table .value-col {
            text-align: right;
            width: 40%;
            background-color: #f5f5f5;
        }

        .totals-table .total-row .label-col {
            /* background-color: #1e88e5;
            color: white; */
            font-weight: bold;
            border-radius: 4px 0 0 4px;
        }

        .totals-table .total-row .value-col {
            background-color: #0d47a1;
            color: white;
            font-weight: bold;
            font-size: 14pt;
            border-radius: 0 4px 4px 0;
        }

        .footer-section {
            width: 100%;
            margin-top: 30px;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .payment-info {
            width: 55%;
            vertical-align: top;
            font-size: 9pt;
        }

        .payment-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .payment-details {
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .bank-name {
            font-weight: bold;
        }

        .bank-account {
            color: #1e88e5;
            font-weight: bold;
        }

        .npwp-section {
            margin-top: 15px;
        }

        .npwp-label {
            font-weight: bold;
        }

        .company-address {
            font-size: 8pt;
            color: #666;
            margin-top: 5px;
        }

        .signature-section {
            width: 45%;
            vertical-align: top;
            text-align: center;
            padding-left: 30px;
        }

        .signature-image {
            max-width: 150px;
            max-height: 80px;
            margin-bottom: 5px;
        }

        .signer-name {
            font-weight: normal;
            margin-top: 10px;
        }

        .signer-position {
            font-size: 9pt;
            color: #666;
            margin-top: 3px;
        }

        .signer-company {
            font-size: 9pt;
            color: #666;
            margin-top: 3px;
        }

        .text-blue {
            color: #1e88e5;
        }

        .text-red {
            color: #c00;
        }
    </style>
</head>

<body>
    {{-- Header Image (Full Width) --}}
    @if ($invoice->issuer?->header_image)
        <img
            class="header-image"
            src="{{ $forBrowser ?? false ? asset('storage/' . $invoice->issuer->header_image) : storage_path('app/public/' . $invoice->issuer->header_image) }}"
            alt="{{ $invoice->issuer->name }}"
        >
    @endif

    <div class="content">
        {{-- Invoice To Section --}}
        <div class="invoice-to-label">Invoice To</div>
        <div class="invoice-to-name">{{ $invoice->recipient }}</div>

        {{-- Details Section --}}
        <div class="details-section">
            <table class="details-table">
                <tr>
                    <td class="details-left">
                        <table class="detail-table">
                            <tr>
                                <td class="label">Address</td>
                                <td class="sep">:</td>
                                <td class="value">{{ $invoice->address }}</td>
                            </tr>
                            @if ($invoice->attention)
                                <tr>
                                    <td class="label">Attention</td>
                                    <td class="sep">:</td>
                                    <td class="value">{{ $invoice->attention }}</td>
                                </tr>
                            @endif
                        </table>
                    </td>
                    <td class="details-right">
                        <table class="detail-table">
                            <tr>
                                <td class="label">No Invoice</td>
                                <td class="sep">:</td>
                                <td class="value">{{ $invoice->invoice_number }}</td>
                            </tr>
                            <tr>
                                <td class="label">Invoice Date</td>
                                <td class="sep">:</td>
                                <td class="value">{{ $invoice->issue_date?->format('d-m-Y') }}</td>
                            </tr>
                            <tr>
                                <td class="label">Due Date</td>
                                <td class="sep">:</td>
                                <td class="value">{{ $invoice->due_date?->format('d-m-Y') }}</td>
                            </tr>
                            @if ($invoice->spkDocument)
                                <tr>
                                    <td class="label">SPK No</td>
                                    <td class="sep">:</td>
                                    <td class="value">{{ $invoice->spkDocument->number }}</td>
                                </tr>
                            @endif
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Items Card --}}
        <div class="items-card">
            {{-- Items Table --}}
            <table class="items-table">
                <thead>
                    <tr>
                        <th class="description-col">Description</th>
                        <th class="discount-col text-center">Discount</th>
                        <th class="price-col text-right">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $subTotal = 0;
                    @endphp
                    @foreach ($invoice->items as $item)
                        @php
                            $itemTotal = $item->amount;
                            if ($item->include_discount && $item->discount_percentage > 0) {
                                $itemTotal = $item->amount - ($item->amount * $item->discount_percentage) / 100;
                            }
                            $subTotal += $itemTotal;
                        @endphp
                        <tr>
                            <td class="description-col">{!! nl2br(e($item->name)) !!}</td>
                            <td class="discount-col text-center">
                                @if ($item->include_discount && $item->discount_percentage > 0)
                                    <span class="text-red">{{ $item->discount_percentage }}%</span>
                                @endif
                            </td>
                            <td class="price-col text-right">
                                Rp {{ number_format($item->amount, 2, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Notes Section --}}
            @if ($invoice->notes)
                <div class="notes-section">
                    <span class="notes-label">Notes :</span> {{ $invoice->notes }}
                </div>
            @endif

            {{-- Totals Section --}}
            @php
                $dppAmount = $invoice->include_dpp ? ($subTotal * 11) / 12 : $subTotal;
                $discountAmount = $invoice->include_discount ? ($dppAmount * $invoice->discount_percentage) / 100 : 0;
                $afterDiscount = $dppAmount - $discountAmount;
                $vatAmount = $invoice->include_vat ? ($afterDiscount * $invoice->vat_percentage) / 100 : 0;
                $grandTotal = $afterDiscount + $vatAmount;
            @endphp

            <table class="totals-table">
                <tr>
                    <td class="label-col">Sub Total</td>
                    <td class="value-col">Rp {{ number_format($subTotal, 2, ',', '.') }}</td>
                </tr>
                @if ($invoice->include_dpp)
                    <tr>
                        <td class="label-col">DPP</td>
                        <td class="value-col">Rp {{ number_format($dppAmount, 2, ',', '.') }}</td>
                    </tr>
                @endif
                @if ($invoice->include_discount && $invoice->discount_percentage > 0)
                    <tr>
                        <td class="label-col">Discount ({{ $invoice->discount_percentage }}%)</td>
                        <td class="value-col">- {{ number_format($discountAmount, 2, ',', '.') }}</td>
                    </tr>
                @endif
                @if ($invoice->include_vat)
                    <tr>
                        <td class="label-col">PPN</td>
                        <td class="value-col">{{ number_format($vatAmount, 2, ',', '.') }}</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td class="label-col">TOTAL</td>
                    <td class="value-col">RP {{ number_format($grandTotal, 2, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        {{-- Footer Section --}}
        <div class="footer-section">
            <table class="footer-table">
                <tr>
                    <td class="payment-info">
                        <div class="payment-title">Payment Method</div>
                        <div class="payment-details">
                            Pembayaran dapat dilakukan melalui transfer atau setoran<br>
                            tunai ke Bank {{ $invoice->bankAccount?->bank_name ?? 'BRI' }}<br>
                            <span
                                class="bank-name">{{ $invoice->bankAccount?->name ?? $invoice->issuer?->name }}</span><br>
                            <span class="bank-account">{{ $invoice->bankAccount?->number ?? '-' }}</span>
                        </div>

                        @if ($invoice->issuer?->tax_id_number)
                            <div class="npwp-section">
                                <span class="npwp-label">NPWP : {{ $invoice->issuer->tax_id_number }}</span><br>
                                <span class="bank-name">{{ $invoice->issuer->name }}</span>
                                <div class="company-address">
                                    {{ $invoice->issuer->tax_id_address ?? $invoice->issuer->address }}
                                </div>
                            </div>
                        @endif
                    </td>
                    <td class="signature-section">
                        @if ($invoice->has_signature && $invoice->signature)
                            @if ($invoice->signature->file)
                                <img
                                    class="signature-image"
                                    src="{{ $forBrowser ?? false ? asset('storage/' . $invoice->signature->file) : storage_path('app/public/' . $invoice->signature->file) }}"
                                    alt="Signature"
                                >
                            @else
                                <div style="height: 60px;"></div>
                            @endif
                            <div class="signer-name">{{ $invoice->signature->name }}</div>
                            <div class="signer-position">{{ $invoice->signature->position }}</div>
                            <div class="signer-company">{{ $invoice->issuer?->name }}</div>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <script>
        window.print();
    </script>
</body>

</html>
