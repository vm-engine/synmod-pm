<!DOCTYPE html>
<html lang="id">

<head>
    <meta
        http-equiv="Content-Type"
        content="text/html; charset=utf-8"
    />
    <title>SPK {{ $data->number }}</title>
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

        .center {
            text-align: center;
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

        .block {
            margin: 0 0 2pt 0;
            text-align: justify;
        }

        .pasal-head {
            margin: 12pt 0 4pt 0;
            font-weight: bold;
            text-align: center;
            page-break-after: avoid;
        }

        table.kv {
            border-collapse: collapse;
            margin: 2pt 0 6pt 0;
        }

        table.kv td {
            padding: 0 4pt 0 0;
            vertical-align: top;
        }

        table.sign {
            width: 100%;
            margin-top: 18pt;
            page-break-inside: avoid;
        }

        table.sign td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .signature {
            max-width: 50mm;
            max-height: 25mm;
        }

        .signature-space {
            height: 25mm;
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

    <p
        class="bold center"
        style="margin: 0; font-size: 14pt;"
    >PERJANJIAN KERJA SAMA</p>
    <p
        class="bold center"
        style="margin: 0; font-size: 14pt;"
    >{{ $data->title }}</p>
    <p
        class="center"
        style="margin-bottom: 14pt;"
    >Nomor: {{ $data->number }} &nbsp; Tanggal: {{ $data->dateLabel }}</p>

    <p class="justify">{{ $data->opening }}</p>
    @include('pm::pdf.sph.blocks', ['blocks' => $data->parties])
    <p class="justify">Untuk selanjutnya, PIHAK PERTAMA dan PIHAK KEDUA secara bersama-sama disebut Para Pihak.</p>
    <p class="justify">Para Pihak dalam kedudukannya sebagaimana tersebut di atas terlebih dahulu menerangkan hal-hal
        sebagai berikut:</p>
    @include('pm::pdf.sph.blocks', ['blocks' => $data->recitals])
    <p class="justify">Berdasarkan hal-hal tersebut di atas, dengan ini Para Pihak telah saling setuju dan sepakat untuk
        membuat, mengikatkan diri dan menandatangani Perjanjian Kerja Sama dengan ketentuan sebagai berikut:</p>

    @foreach ($data->pasal as $pasal)
        <p class="pasal-head">PASAL {{ $pasal['number'] }}<br>{{ $pasal['title'] }}</p>
        @foreach ($pasal['parts'] as $part)
            @if ($part['type'] === 'blocks')
                @include('pm::pdf.sph.blocks', ['blocks' => $part['blocks']])
            @else
                <table
                    class="kv"
                    style="margin-left: {{ $part['indent'] * 14 }}pt;"
                >
                    @foreach ($part['rows'] as [$label, $value])
                        <tr>
                            <td style="width: 32mm;">{{ $label }}</td>
                            <td style="width: 3mm;">:</td>
                            <td>{{ $value }}</td>
                        </tr>
                    @endforeach
                </table>
            @endif
        @endforeach
    @endforeach

    <p
        class="justify"
        style="margin-top: 12pt;"
    >{{ \VmEngine\Pm\Services\Spk\SpkDocumentData::CLOSING }}</p>

    <table class="sign">
        <tr>
            @foreach ([$data->secondSigner, $data->firstSigner] as $signer)
                <td>
                    <p style="margin: 0;">{{ $signer['label'] }},</p>
                    <p
                        class="bold"
                        style="margin: 0;"
                    >{{ $signer['company'] }}</p>
                    <div class="signature-space">
                        @if ($loop->first && $data->signaturePath)
                            <img
                                class="signature"
                                src="{{ $data->signaturePath }}"
                                alt=""
                            >
                        @endif
                    </div>
                    <p style="margin: 0;"><span
                            class="bold"
                            style="text-decoration: underline;"
                        >{{ $signer['name'] }}</span></p>
                    <p>{{ $signer['position'] }}</p>
                </td>
            @endforeach
        </tr>
    </table>

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
