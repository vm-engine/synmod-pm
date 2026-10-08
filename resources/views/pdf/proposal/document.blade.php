<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta
        http-equiv="Content-Type"
        content="text/html; charset=utf-8"
    />
    <title>{{ $data->title }}</title>
    <style>
        @font-face {
            font-family: 'ProposalBody';
            font-style: normal;
            font-weight: normal;
            src: url('{{ $fonts['body']['regular'] }}') format('truetype');
        }

        @font-face {
            font-family: 'ProposalBody';
            font-style: normal;
            font-weight: bold;
            src: url('{{ $fonts['body']['bold'] }}') format('truetype');
        }

        @font-face {
            font-family: 'ProposalBody';
            font-style: italic;
            font-weight: normal;
            src: url('{{ $fonts['body']['italic'] }}') format('truetype');
        }

        @font-face {
            font-family: 'ProposalBody';
            font-style: italic;
            font-weight: bold;
            src: url('{{ $fonts['body']['bolditalic'] }}') format('truetype');
        }

        /* Arial Rounded MT Bold when licensed fonts are installed, otherwise Varela Round (see PdfProposalRenderer::fonts) */
        @font-face {
            font-family: 'ProposalRounded';
            font-style: normal;
            font-weight: normal;
            src: url('{{ $fonts['rounded'] }}') format('truetype');
        }

        @page {
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'ProposalBody', 'DejaVu Sans', sans-serif;
            font-size: 14pt;
            color: #000;
        }

        .page {
            position: relative;
            width: 960pt;
            height: 540pt;
            overflow: hidden;
            page-break-after: always;
        }

        .page.last {
            page-break-after: auto;
        }

        .full {
            position: absolute;
            left: 0;
            top: 0;
            width: 960pt;
            height: 540pt;
        }

        .title {
            position: absolute;
            /* PowerPoint title box x (66pt) + its 7.2pt inner margin */
            left: 73.2pt;
            top: 26pt;
            font-size: 44pt;
            font-weight: bold;
            font-style: italic;
        }

        .bar {
            position: absolute;
            left: 77pt;
            top: 100pt;
            width: 300pt;
            height: 11pt;
            background: #F0434F;
        }

        .logo {
            position: absolute;
            left: 800pt;
            top: 38pt;
            width: 115pt;
        }

        .body {
            position: absolute;
            left: 29pt;
            top: 134pt;
            width: 902pt;
            height: 382pt;
        }

        .para {
            margin-top: 6pt;
            /* dompdf scales line-height by the font's ascent+descent (~1.22em for Calibri),
               so 1 gives PowerPoint's single spacing (ProposalDocumentData::LINE_HEIGHT) */
            line-height: 1;
        }

        /* The deck's text box (ProposalDocumentData::TEXT_*), inner margin included: text lines up with the title */
        .text-body {
            position: absolute;
            left: 73.2pt;
            top: 147.35pt;
            width: 813.6pt;
            height: 348.8pt;
        }

        .subheading {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 8pt;
        }

        table.grid {
            width: 100%;
            border-collapse: collapse;
            font-size: 12pt;
        }

        table.grid th {
            background: #ED7D31;
            color: #fff;
            padding: 5pt;
            border: 1pt solid #ED7D31;
            text-align: center;
        }

        table.grid td {
            background: #FBE9E1;
            padding: 5pt;
            border: 1pt solid #ED7D31;
            vertical-align: middle;
        }

        table.grid.blue th {
            background: #4472C4;
            border-color: #4472C4;
        }

        table.grid.blue td {
            background: #D9E1F2;
            border-color: #fff;
        }

        table.grid ul {
            margin-left: 14pt;
        }

        .r {
            text-align: right;
        }

        .c {
            text-align: center;
        }

        .note {
            position: absolute;
            left: 29pt;
            bottom: 24pt;
            font-size: 13pt;
        }

        .tile {
            background: #D9E1F2;
            color: #1F3864;
            font-weight: bold;
            font-size: 15pt;
            text-align: center;
            padding: 18pt 10pt;
            border-radius: 10pt;
        }

        .cover-text {
            position: absolute;
            font-family: 'ProposalRounded', 'DejaVu Sans', sans-serif;
            line-height: 1.15;
            white-space: nowrap;
        }
    </style>
</head>

<body>
    {{-- Cover: background image with texts blanked, dynamic texts on top --}}
    <div class="page">
        <img
            class="full"
            src="{{ $asset('static/slide-01-cover.jpg') }}"
        >
        <div
            class="cover-text"
            style="left: 44pt; top: 141pt; font-size: 32pt; color: #0085FE;"
        >PROPOSAL PENAWARAN</div>
        <div
            class="cover-text"
            style="left: 44pt; top: 180pt; font-size: 28pt;"
        >{{ $data->title }}</div>
        <div
            class="cover-text"
            style="left: 52pt; top: 230pt; font-size: 26pt; color: #fff;"
        >{{ $data->subject }}</div>
        <div
            class="cover-text"
            style="left: 62pt; top: 275pt; font-size: 18pt; font-family: 'ProposalBody', 'DejaVu Sans', sans-serif;"
        >No : {{ $data->number }}</div>
    </div>

    @foreach (range(2, 11) as $slide)
        <div class="page"><img
                class="full"
                src="{{ $asset(sprintf('static/slide-%02d.jpg', $slide)) }}"
            ></div>
    @endforeach
    <div class="page"><img
            class="full"
            src="{{ $asset('static/slide-12-' . $variant . '.jpg') }}"
        ></div>
    <div class="page"><img
            class="full"
            src="{{ $asset('static/slide-13-' . $variant . '.jpg') }}"
        ></div>

    @foreach ([...$data->goalPages, ...$data->menuPages] as $paragraphs)
        <div class="page">
            @include('pm::pdf.proposal.chrome', ['title' => 'Background'])
            <div class="text-body">@include('pm::pdf.proposal.paragraphs', ['paragraphs' => $paragraphs])</div>
        </div>
    @endforeach

    @foreach ($data->mockups as $mockup)
        <div class="page">
            @include('pm::pdf.proposal.chrome', [
                'title' => $mockup['caption'] !== '' ? 'Layout - ' . $mockup['caption'] : 'Layout',
            ])
            <div
                class="body"
                style="text-align: center;"
            ><img
                    src="{{ $mockup['path'] }}"
                    style="max-width: 902pt; max-height: 382pt;"
                ></div>
        </div>
    @endforeach

    @foreach ([17, 18, 19] as $slide)
        <div class="page"><img
                class="full"
                src="{{ $asset(sprintf('static/slide-%02d.jpg', $slide)) }}"
            ></div>
    @endforeach

    @foreach ($data->featurePages as $paragraphs)
        <div class="page">
            @include('pm::pdf.proposal.chrome', ['title' => 'Feature & Module'])
            <div class="text-body">@include('pm::pdf.proposal.paragraphs', ['paragraphs' => $paragraphs])</div>
        </div>
    @endforeach

    <div class="page">
        @include('pm::pdf.proposal.chrome', ['title' => 'Project Costing'])
        <div class="body">
            <div class="subheading">{{ $data->developmentLabel }}</div>
            <table class="grid">
                <thead>
                    <tr>
                        <th style="width: 24%;">SECTION</th>
                        <th>MODULE / FEATURE</th>
                        <th style="width: 22%;">PRICE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data->costing as $row)
                        <tr>
                            <td><strong>{{ $row['section'] }}</strong></td>
                            <td>
                                <ul>
                                    @foreach ($row['features'] as $feature)
                                        <li>{{ $feature }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="r">{{ $row['price'] }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td class="r"><strong>GRAND TOTAL</strong></td>
                        <td class="r"><strong>{{ $data->grandTotalLabel }}</strong></td>
                    </tr>
                    <tr>
                        <td
                            class="c"
                            colspan="3"
                        ><strong>“{{ $data->grandTotalWords }}”</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
        @if ($data->taxNote)
            <div class="note"><strong>Keterangan:</strong><br>&bull; Harga belum termasuk Pajak</div>
        @endif
    </div>

    <div class="page">
        @include('pm::pdf.proposal.chrome', ['title' => 'Server Hosting'])
        <div class="body">
            @if ($data->hostingOptional)
                <div class="subheading">Hosting dan Domain (opsional)</div>
            @endif
            <table class="grid">
                <thead>
                    <tr>
                        <th style="width: 6%;">No</th>
                        <th style="width: 30%;">SECTION</th>
                        <th>MODULE / FEATURE</th>
                        <th style="width: 22%;">PRICE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data->hosting as $i => $row)
                        <tr>
                            <td class="c">{{ $i + 1 }}</td>
                            <td>{{ $row['name'] }}</td>
                            <td>{!! implode('<br>', array_map('e', $row['specs'])) !!}</td>
                            <td class="c">{{ $row['price'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($data->taxNote)
            <div class="note"><strong>Keterangan:</strong><br>&bull; Harga belum termasuk Pajak</div>
        @endif
    </div>

    @foreach ($ganttImages as $image)
        <div class="page">
            @include('pm::pdf.proposal.chrome', ['title' => 'Timeplan Project'])
            <div class="body"><img
                    src="{{ $image }}"
                    style="width: 902pt;"
                ></div>
            <div class="note"><strong>Keterangan:</strong><br>&bull; {{ $data->workdayNote }}</div>
        </div>
    @endforeach

    <div class="page">
        @include('pm::pdf.proposal.chrome', ['title' => 'Deliverables'])
        <div class="body">
            <table class="grid blue">
                <thead>
                    <tr>
                        <th style="width: 50%;">Item</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data->deliverables as $row)
                        <tr>
                            <td>{{ $row['item'] }}</td>
                            <td>{{ $row['description'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="page">
        @include('pm::pdf.proposal.chrome', ['title' => 'After Services Maintenance'])
        <div class="body">
            <table style="width: 100%; border-collapse: separate; border-spacing: 18pt;">
                @foreach (array_chunk($data->afterSales, 3) as $tiles)
                    <tr>
                        @foreach ($tiles as $label)
                            <td
                                class="tile"
                                style="width: 33%;"
                            >{{ $label }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

    @foreach ([27, 28, 29, 30] as $slide)
        <div class="page{{ $loop->last ? ' last' : '' }}"><img
                class="full"
                src="{{ $asset(sprintf('static/slide-%02d.jpg', $slide)) }}"
            ></div>
    @endforeach
</body>

</html>
