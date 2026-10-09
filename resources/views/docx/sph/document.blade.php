@use('VmEngine\Pm\Services\Sph\Docx\DocxXml', 'X')
@php
    $bold = ['bold' => true];
    // Content width 9746 twips (A4, 0.75in side margins)
    $widths = $data->showDuration ? [600, 2400, 3146, 1300, 2300] : [600, 2600, 4246, 2300];
    $last = count($widths) - 1;
    $imageId = 1;

    $header = ['No', 'Nama Layanan', 'Keterangan', ...($data->showDuration ? ['Durasi Pekerjaan'] : []), 'Harga (Rp)'];
    $rows = X::row(implode('', array_map(
        fn (string $label, int $i): string => X::cell($widths[$i], X::text($label, $bold, ['align' => 'center']), ['fill' => 'D9D9D9', 'valign' => 'center']),
        $header,
        array_keys($header),
    )), header: true);
    foreach ($data->items as $item) {
        $cells = X::cell($widths[0], X::text((string) $item['number'], [], ['align' => 'center']))
            .X::cell($widths[1], X::text($item['name'], $bold))
            .X::cell($widths[2], X::blocks($item['blocks']));
        if ($data->showDuration) {
            $cells .= X::cell($widths[3], X::text($item['duration'], [], ['align' => 'center']));
        }
        $rows .= X::row($cells.X::cell($widths[$last], X::text($item['price'], [], ['align' => 'right'])));
    }
    $rows .= X::row(
        X::cell(array_sum(array_slice($widths, 0, $last)), X::text('GRAND TOTAL', $bold, ['align' => 'center']), ['span' => $last, 'fill' => 'E7E6E6'])
        .X::cell($widths[$last], X::text($data->grandTotalLabel, $bold, ['align' => 'right']), ['fill' => 'E7E6E6'])
    );
    $rows .= X::row(X::cell(array_sum($widths), X::text('”'.$data->grandTotalWords.'”', ['bold' => true, 'italic' => true], ['align' => 'center']), ['span' => count($widths)]));

    $metaWidths = [1300, 300, 4946, 3200];
    // Closing/signature block sits in the right 40% of the text width, like the PDF's .closing.
    $closing = ['indent' => (int) round(array_sum($metaWidths) * 0.6)];
    $meta = X::row(X::cell(1300, X::text('Nomor')).X::cell(300, X::text(':')).X::cell(4946, X::text($data->number)).X::cell(3200, X::text($data->dateLabel, [], ['align' => 'right'])))
        .X::row(X::cell(1300, X::text('Lampiran')).X::cell(300, X::text(':')).X::cell(4946, X::text($data->attachmentLabel)).X::cell(3200, ''))
        .X::row(X::cell(1300, X::text('Perihal')).X::cell(300, X::text(':')).X::cell(4946, X::text($data->subject, $bold)).X::cell(3200, ''));
@endphp
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing">
<w:body>
{!! X::text('SURAT PENAWARAN HARGA', ['bold' => true, 'underline' => true, 'size' => 28], ['align' => 'center', 'after' => 240]) !!}
{!! X::table($metaWidths, $meta, borders: false) !!}
{!! X::text('', [], ['after' => 240]) !!}
{!! X::paragraph(X::run('Kepada Yth.: ').X::run($data->recipientName, $bold)) !!}
@foreach ($data->recipientLines as $line)
{!! X::text($line) !!}
@endforeach
{!! X::text('', [], ['after' => 240]) !!}
{!! X::text('Dengan Hormat,', [], ['after' => 120]) !!}
{!! X::text('Sehubungan dengan disampaikannya kebutuhan ”'.$data->need.'”, dengan ini kami mengajukan penawaran harga layanan dengan rincian terlampir (lampiran).', [], ['align' => 'both', 'after' => 120]) !!}
{!! X::text('Demikian surat pengajuan penawaran ini kami sampaikan, dan atas perhatian yang diberikan kami ucapkan terima kasih.', [], ['align' => 'both', 'after' => 480]) !!}
{!! X::text('Hormat Kami,', [], $closing) !!}
{!! X::text($data->companyName, $bold, $closing) !!}
@if ($signature)
{!! X::paragraph(X::image($signature['rid'], $signature['cx'], $signature['cy'], $imageId++), $closing) !!}
@else
{!! X::text('', [], [...$closing, 'after' => 1200]) !!}
@endif
{!! X::text('('.$data->signerName.')', ['bold' => true, 'underline' => true], $closing) !!}
{!! X::text($data->signerPosition, [], $closing) !!}
{!! X::text('Lampiran 1', $bold, ['pageBreakBefore' => true, 'after' => 120]) !!}
{!! X::text('LAMPIRAN SURAT PENAWARAN', $bold, ['align' => 'center', 'after' => 240]) !!}
{!! X::text('Berikut kami lampirkan rincian harga untuk layanan tersebut:', [], ['after' => 120]) !!}
{!! X::table($widths, $rows) !!}
@if ($data->taxNote)
{!! X::text('*Harga belum termasuk pajak.', ['italic' => true], ['before' => 60]) !!}
@else
{!! X::text('') !!}
@endif
@if ($data->paymentLines !== [])
{!! X::text('Metode Pembayaran', $bold, ['before' => 240, 'after' => 60, 'keepNext' => true]) !!}
{!! X::bullets($data->paymentLines) !!}
@endif
@if ($data->timelineSummary !== null)
{!! X::text('Timeline dan Waktu Pengerjaan', $bold, ['before' => 240, 'after' => 60, 'keepNext' => true]) !!}
{!! X::text($data->timelineSummary) !!}
@endif
@if ($data->timeplan !== null)
{!! X::text('Lampiran '.$data->timeplan['lampiran'], $bold, ['pageBreakBefore' => true, 'after' => 120]) !!}
{!! X::text('TIMEPLAN', $bold, ['align' => 'center', 'after' => 240]) !!}
@foreach ($ganttImages as $image)
{!! X::paragraph(X::image($image['rid'], $image['cx'], $image['cy'], $imageId++, $image['svgRid']), ['after' => 120]) !!}
@endforeach
{!! X::text('Keterangan:', $bold, ['before' => 120, 'after' => 60]) !!}
{!! X::bullets($data->timeplan['notes']) !!}
@endif
@foreach ($data->attachments as $i => $attachment)
{!! X::text('Lampiran '.$attachment['lampiran'], $bold, ['pageBreakBefore' => true, 'after' => 120]) !!}
@if ($attachment['title'] !== '')
{!! X::text(mb_strtoupper($attachment['title']), $bold, ['align' => 'center', 'after' => 240]) !!}
@endif
{!! X::paragraph(X::image($attachmentImages[$i]['rid'], $attachmentImages[$i]['cx'], $attachmentImages[$i]['cy'], $imageId++), ['align' => 'center']) !!}
@endforeach
<w:sectPr><w:headerReference w:type="default" r:id="rId10"/><w:footerReference w:type="default" r:id="rId11"/><w:pgSz w:w="11906" w:h="16838" w:code="9"/><w:pgMar w:top="1440" w:right="1080" w:bottom="1440" w:left="1080" w:header="720" w:footer="720" w:gutter="0"/><w:cols w:space="720"/></w:sectPr>
</w:body>
</w:document>
