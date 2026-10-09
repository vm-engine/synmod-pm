@use('VmEngine\Pm\Services\Sph\Docx\DocxXml', 'X')
@use('VmEngine\Pm\Services\Spk\SpkDocumentData', 'D')
@php
    $bold = ['bold' => true];
    $justify = ['align' => 'both', 'after' => 120];
    $imageId = 1;
    // Content width 9746 twips (A4, 0.75in side margins); a label/value table is indented by a spacer column
    $kv = function (array $part): string {
        $indent = $part['indent'] * X::INDENT;
        $widths = [$indent, 2200, 300, 9746 - $indent - 2500];
        $rows = '';
        foreach ($part['rows'] as [$label, $value]) {
            $rows .= X::row(X::cell($widths[0], '').X::cell($widths[1], X::text($label)).X::cell($widths[2], X::text(':')).X::cell($widths[3], X::text($value)));
        }

        return X::table($widths, $rows, borders: false);
    };
    $signerCell = function (array $signer, ?array $image) use ($bold, &$imageId): string {
        $center = ['align' => 'center'];
        $picture = $image !== null
            ? X::paragraph(X::image($image['rid'], $image['cx'], $image['cy'], $imageId++), $center)
            : X::text('', [], ['after' => 1200]);

        return X::cell(4873, X::text($signer['label'].',', [], $center).X::text($signer['company'], $bold, $center).$picture
            .X::text($signer['name'], ['bold' => true, 'underline' => true], $center).X::text($signer['position'], [], $center));
    };
@endphp
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing">
<w:body>
{!! X::text('PERJANJIAN KERJA SAMA', ['bold' => true, 'size' => 28], ['align' => 'center']) !!}
{!! X::text($data->title, ['bold' => true, 'size' => 28], ['align' => 'center']) !!}
{!! X::text('Nomor: '.$data->number.'   Tanggal: '.$data->dateLabel, [], ['align' => 'center', 'after' => 240]) !!}
{!! X::text($data->opening, [], $justify) !!}
{!! X::blocks($data->parties) !!}
{!! X::text('Untuk selanjutnya, PIHAK PERTAMA dan PIHAK KEDUA secara bersama-sama disebut Para Pihak.', [], ['align' => 'both', 'before' => 120, 'after' => 120]) !!}
{!! X::text('Para Pihak dalam kedudukannya sebagaimana tersebut di atas terlebih dahulu menerangkan hal-hal sebagai berikut:', [], $justify) !!}
{!! X::blocks($data->recitals) !!}
{!! X::text('Berdasarkan hal-hal tersebut di atas, dengan ini Para Pihak telah saling setuju dan sepakat untuk membuat, mengikatkan diri dan menandatangani Perjanjian Kerja Sama dengan ketentuan sebagai berikut:', [], ['align' => 'both', 'before' => 120, 'after' => 120]) !!}
@foreach ($data->pasal as $pasal)
{!! X::text('PASAL '.$pasal['number'], $bold, ['align' => 'center', 'before' => 240, 'keepNext' => true]) !!}
{!! X::text($pasal['title'], $bold, ['align' => 'center', 'after' => 120, 'keepNext' => true]) !!}
@foreach ($pasal['parts'] as $part)
{!! $part['type'] === 'blocks' ? X::blocks($part['blocks']) : $kv($part) !!}
@endforeach
@endforeach
{!! X::text(D::CLOSING, [], ['align' => 'both', 'before' => 240, 'after' => 240]) !!}
{!! X::table([4873, 4873], X::row($signerCell($data->secondSigner, $signature).$signerCell($data->firstSigner, null)), borders: false) !!}
{!! X::text('') !!}
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
