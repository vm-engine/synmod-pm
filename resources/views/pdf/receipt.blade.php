<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta
        http-equiv="Content-Type"
        content="text/html; charset=utf-8"
    />
    <title>Kuitansi {{ $receiptNumber }}</title>
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
            padding: 30px 50px;
        }

        .title-section {
            text-align: center;
            margin-bottom: 25px;
        }

        .title {
            font-size: 18pt;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .receipt-number {
            font-size: 10pt;
            color: #1e88e5;
        }

        .receipt-card {
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 25px 30px;
            margin-bottom: 40px;
        }

        .receipt-row {
            margin-bottom: 20px;
        }

        .receipt-row:last-child {
            margin-bottom: 0;
        }

        .receipt-table {
            width: 100%;
            border-collapse: collapse;
        }

        .receipt-table td {
            vertical-align: top;
            padding: 3px 0;
        }

        .label-cell {
            width: 200px;
        }

        .label-main {
            font-weight: bold;
            color: #333;
        }

        .label-sub {
            font-style: italic;
            font-size: 8pt;
            color: #666;
        }

        .sep-cell {
            width: 15px;
        }

        .value-cell {
            color: #333;
        }

        .value-bold {
            font-weight: bold;
        }

        .items-list {
            margin: 0;
            padding-left: 20px;
        }

        .items-list li {
            margin-bottom: 3px;
        }

        .footer-section {
            width: 100%;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .amount-cell {
            width: 50%;
            vertical-align: top;
        }

        .signature-cell {
            width: 50%;
            vertical-align: top;
            text-align: center;
            padding-left: 30px;
            padding-top: 120px;
        }

        .amount-box {
            display: inline-block;
            border: 2px solid #333;
            padding: 12px 25px;
            font-size: 14pt;
            font-weight: bold;
        }

        .signer-name {
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 3px;
        }

        .signer-position {
            font-size: 9pt;
            color: #333;
            margin-bottom: 3px;
        }

        .signer-company {
            font-size: 9pt;
            color: #333;
        }
    </style>
</head>

<body>
    {{-- Header Image (Full Width) --}}
    @if ($invoice->issuer?->header_image)
        <img
            class="header-image"
            src="{{ $forBrowser ?? false ? asset('storage/' . $invoice->issuer->header_image) : storage_path('app/public/' . $invoice->issuer->header_image) }}"
            alt="{{ $invoice->issuer?->name }}"
        >
    @endif

    <div class="content">
        {{-- Title Section --}}
        <div class="title-section">
            <div class="title">KUITANSI</div>
            <div class="receipt-number">{{ $receiptNumber }}</div>
        </div>

        {{-- Receipt Content Card --}}
        <div class="receipt-card">
            {{-- Sudah Terima Dari --}}
            <div class="receipt-row">
                <table class="receipt-table">
                    <tr>
                        <td class="label-cell">
                            <div class="label-main">Sudah Terima Dari</div>
                            <div class="label-sub">Received From</div>
                        </td>
                        <td class="sep-cell">:</td>
                        <td class="value-cell value-bold">{{ $invoice->client?->company }}</td>
                    </tr>
                </table>
            </div>

            {{-- Banyak Uang --}}
            <div class="receipt-row">
                <table class="receipt-table">
                    <tr>
                        <td class="label-cell">
                            <div class="label-main">Banyak Uang</div>
                            <div class="label-sub">Amount Received</div>
                        </td>
                        <td class="sep-cell">:</td>
                        <td class="value-cell">*{{ $amountText }}*</td>
                    </tr>
                </table>
            </div>

            {{-- Untuk Pembayaran --}}
            <div class="receipt-row">
                <table class="receipt-table">
                    <tr>
                        <td class="label-cell">
                            <div class="label-main">Untuk Pembayaran</div>
                            <div class="label-sub">In Payment Of</div>
                        </td>
                        <td class="sep-cell">:</td>
                        <td class="value-cell">
                            @foreach ($invoice->items as $index => $item)
                                {{ $item->name }}@if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Footer Section with Amount Box and Signature --}}
        <div class="footer-section">
            <table class="footer-table">
                <tr>
                    <td class="amount-cell">
                        <div class="amount-box">
                            Rp. {{ number_format($invoice->total_amount, 0, ',', '.') }}, -
                        </div>
                    </td>
                    <td class="signature-cell">
                        <div class="signer-name">{{ $invoice->signature?->name }}</div>
                        <div class="signer-position">{{ $invoice->signature?->position }}</div>
                        <div class="signer-company">{{ $invoice->issuer?->name }}</div>
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
