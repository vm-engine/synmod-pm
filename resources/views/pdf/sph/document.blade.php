<!DOCTYPE html>
<html lang="id">

<head>
    <meta
        http-equiv="Content-Type"
        content="text/html; charset=utf-8"
    />
    <title>{{ $data->subject }} {{ $data->number }}</title>
    <style>
        @page {
            margin: 30mm 19mm 22mm 19mm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.3;
            color: #000;
        }

        .letterhead-top {
            position: fixed;
            top: -30mm;
            left: -19mm;
            width: 210mm;
        }

        .letterhead-bottom {
            position: fixed;
            bottom: -22mm;
            left: -19mm;
            width: 210mm;
        }

        .letterhead-top img,
        .letterhead-bottom img {
            display: block;
            width: 210mm;
        }

        p {
            margin: 0 0 6pt 0;
        }

        .title {
            margin-bottom: 14pt;
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
            text-decoration: underline;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .justify {
            text-align: justify;
        }

        .bold {
            font-weight: bold;
        }

        .page-break {
            page-break-before: always;
        }

        table.meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14pt;
        }

        table.meta td {
            padding: 1pt 0;
            vertical-align: top;
        }

        table.price {
            width: 100%;
            border-collapse: collapse;
        }

        table.price th,
        table.price td {
            border: 0.75pt solid #000;
            padding: 3pt 4pt;
            vertical-align: top;
        }

        table.price th {
            background: #D9D9D9;
        }

        table.price tr.total td {
            background: #E7E6E6;
            font-weight: bold;
        }

        .block {
            margin: 0 0 2pt 0;
        }

        .closing {
            margin-left: 60%;
        }

        .signature {
            width: 50mm;
        }

        .signature-space {
            height: 25mm;
        }

        .gantt {
            width: 100%;
            margin-bottom: 6pt;
        }

        .attachment {
            max-width: 100%;
            max-height: 200mm;
        }
    </style>
</head>

<body>
    @if ($data->headerImagePath)
        <div class="letterhead-top"><img
                src="{{ $data->headerImagePath }}"
                alt=""
            ></div>
    @endif
    @if ($data->footerImagePath)
        <div class="letterhead-bottom"><img
                src="{{ $data->footerImagePath }}"
                alt=""
            ></div>
    @endif

    <p class="title">SURAT PENAWARAN HARGA</p>
    <table class="meta">
        <tr>
            <td style="width: 22mm;">Nomor</td>
            <td style="width: 4mm;">:</td>
            <td>{{ $data->number }}</td>
            <td class="right">{{ $data->dateLabel }}</td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>:</td>
            <td colspan="2">{{ $data->attachmentLabel }}</td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td>:</td>
            <td
                class="bold"
                colspan="2"
            >{{ $data->subject }}</td>
        </tr>
    </table>

    <p style="margin: 0;">Kepada Yth.: <span class="bold">{{ $data->recipientName }}</span></p>
    @foreach ($data->recipientLines as $line)
        <p style="margin: 0;">{{ $line }}</p>
    @endforeach

    <p style="margin-top: 14pt;">Dengan Hormat,</p>
    <p class="justify">Sehubungan dengan disampaikannya kebutuhan ”{{ $data->need }}”, dengan ini kami mengajukan
        penawaran harga layanan dengan rincian terlampir (lampiran).</p>
    <p
        class="justify"
        style="margin-bottom: 24pt;"
    >Demikian surat pengajuan penawaran ini kami sampaikan, dan atas perhatian yang diberikan kami ucapkan
        terima kasih.</p>

    <div class="closing">
        <p style="margin: 0;">Hormat Kami,</p>
        <p
            class="bold"
            style="margin: 0;"
        >{{ $data->companyName }}</p>
        @if ($data->signaturePath)
            <img
                class="signature"
                src="{{ $data->signaturePath }}"
                alt=""
            >
        @else
            <div class="signature-space"></div>
        @endif
        <p style="margin: 0;"><span
                class="bold"
                style="text-decoration: underline;"
            >({{ $data->signerName }})</span></p>
        <p>{{ $data->signerPosition }}</p>
    </div>

    <div class="page-break"></div>
    <p class="bold">Lampiran 1</p>
    <p class="bold center">LAMPIRAN SURAT PENAWARAN</p>
    <p>Berikut kami lampirkan rincian harga untuk layanan tersebut:</p>
    <table class="price">
        <thead>
            <tr>
                <th style="width: 8mm;">No</th>
                <th style="width: 42mm;">Nama Layanan</th>
                <th>Keterangan</th>
                @if ($data->showDuration)
                    <th style="width: 24mm;">Durasi Pekerjaan</th>
                @endif
                <th style="width: 38mm;">Harga (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data->items as $item)
                <tr>
                    <td class="center">{{ $item['number'] }}</td>
                    <td class="bold">{{ $item['name'] }}</td>
                    <td>@include('pm::pdf.sph.blocks', ['blocks' => $item['blocks']])</td>
                    @if ($data->showDuration)
                        <td class="center">{{ $item['duration'] }}</td>
                    @endif
                    <td class="right">{{ $item['price'] }}</td>
                </tr>
            @endforeach
            <tr class="total">
                <td
                    class="center"
                    colspan="{{ $data->showDuration ? 4 : 3 }}"
                >GRAND TOTAL</td>
                <td class="right">{{ $data->grandTotalLabel }}</td>
            </tr>
            <tr>
                <td
                    class="center bold"
                    style="font-style: italic;"
                    colspan="{{ $data->showDuration ? 5 : 4 }}"
                >”{{ $data->grandTotalWords }}”</td>
            </tr>
        </tbody>
    </table>
    @if ($data->taxNote)
        <p style="margin-top: 3pt; font-style: italic;">*Harga belum termasuk pajak.</p>
    @endif

    @if ($data->paymentLines !== [])
        <p
            class="bold"
            style="margin-top: 12pt;"
        >Metode Pembayaran</p>
        @include('pm::pdf.sph.blocks', [
            'blocks' => array_map(
                fn(string $line): array => [
                    'level' => 1,
                    'marker' => '•',
                    'runs' => [['text' => $line, 'bold' => false, 'italic' => false]],
                ],
                $data->paymentLines),
        ])
    @endif

    @if ($data->timelineSummary !== null)
        <p
            class="bold"
            style="margin-top: 12pt;"
        >Timeline dan Waktu Pengerjaan</p>
        <p>{{ $data->timelineSummary }}</p>
    @endif

    @if ($data->timeplan !== null)
        <div class="page-break"></div>
        <p class="bold">Lampiran {{ $data->timeplan['lampiran'] }}</p>
        <p class="bold center">TIMEPLAN</p>
        @foreach ($ganttImages as $image)
            <img
                class="gantt"
                src="{{ $image }}"
                alt=""
            >
        @endforeach
        <p class="bold">Keterangan:</p>
        @include('pm::pdf.sph.blocks', [
            'blocks' => array_map(
                fn(string $line): array => [
                    'level' => 1,
                    'marker' => '•',
                    'runs' => [['text' => $line, 'bold' => false, 'italic' => false]],
                ],
                $data->timeplan['notes']),
        ])
    @endif

    @foreach ($data->attachments as $attachment)
        <div class="page-break"></div>
        <p class="bold">Lampiran {{ $attachment['lampiran'] }}</p>
        @if ($attachment['title'] !== '')
            <p class="bold center">{{ mb_strtoupper($attachment['title']) }}</p>
        @endif
        <div class="center"><img
                class="attachment"
                src="{{ $attachment['path'] }}"
                alt=""
            ></div>
    @endforeach
</body>

</html>
