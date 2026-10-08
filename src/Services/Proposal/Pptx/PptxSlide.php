<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Proposal\Pptx;

use DOMDocument;
use DOMElement;
use RuntimeException;

/**
 * DOM helpers for a single slide part.
 */
final class PptxSlide
{
    public const NS_P = 'http://schemas.openxmlformats.org/presentationml/2006/main';

    public const NS_A = 'http://schemas.openxmlformats.org/drawingml/2006/main';

    public const NS_R = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships';

    private const SHAPE_TAGS = ['sp', 'pic', 'graphicFrame', 'grpSp', 'cxnSp'];

    /** Remove every non-chrome shape (see TemplateMap::isChrome) and append $shapesXml. */
    public static function replaceBody(DOMDocument $slide, string $shapesXml): void
    {
        $tree = self::spTree($slide);
        foreach (iterator_to_array($tree->childNodes) as $node) {
            if ($node instanceof DOMElement && in_array($node->localName, self::SHAPE_TAGS, true) && ! TemplateMap::isChrome($node)) {
                $tree->removeChild($node);
            }
        }
        self::appendShapes($slide, $shapesXml);
    }

    public static function appendShapes(DOMDocument $slide, string $shapesXml): void
    {
        if (trim($shapesXml) === '') {
            return;
        }
        $wrapper = new DOMDocument;
        $xml = '<p:spTree xmlns:p="'.self::NS_P.'" xmlns:a="'.self::NS_A.'" xmlns:r="'.self::NS_R.'">'.$shapesXml.'</p:spTree>';
        if (! $wrapper->loadXML($xml) || $wrapper->documentElement === null) {
            throw new RuntimeException('Generated shape XML is not well-formed');
        }
        $tree = self::spTree($slide);
        foreach (iterator_to_array($wrapper->documentElement->childNodes) as $child) {
            $tree->appendChild($slide->importNode($child, true));
        }
    }

    public static function setParagraphText(DOMDocument $slide, string $shapeName, int $paragraph, string $text): void
    {
        foreach (self::spTree($slide)->childNodes as $shape) {
            if (! $shape instanceof DOMElement || self::shapeName($shape) !== $shapeName) {
                continue;
            }
            $p = $shape->getElementsByTagNameNS(self::NS_A, 'p')->item($paragraph);
            if (! $p instanceof DOMElement) {
                throw new RuntimeException("Shape {$shapeName} has no paragraph {$paragraph}");
            }
            $runs = array_values(iterator_to_array($p->getElementsByTagNameNS(self::NS_A, 'r')));
            if ($runs === []) {
                throw new RuntimeException("Paragraph {$paragraph} of {$shapeName} has no text run");
            }
            $first = array_shift($runs);
            foreach ($runs as $run) {
                $run->parentNode?->removeChild($run);
            }
            $t = $first->getElementsByTagNameNS(self::NS_A, 't')->item(0);
            if ($t !== null) {
                $t->textContent = $text;
            }

            return;
        }

        throw new RuntimeException("Shape not found: {$shapeName}");
    }

    /**
     * Replace text inside paragraphs. When the phrase spans several runs, only the runs it covers
     * are merged into the first of them (other runs keep their formatting).
     *
     * @return int number of replacements
     */
    public static function replaceText(DOMDocument $slide, string $search, string $replace): int
    {
        $count = 0;
        foreach (iterator_to_array($slide->getElementsByTagNameNS(self::NS_A, 'p')) as $p) {
            $texts = array_values(iterator_to_array($p->getElementsByTagNameNS(self::NS_A, 't')));
            $full = implode('', array_map(fn (DOMElement $t): string => $t->textContent, $texts));
            $position = mb_strpos($full, $search);
            if ($position === false) {
                continue;
            }
            $count++;
            $matchEnd = $position + mb_strlen($search);

            // Locate the first and last run touched by the match
            $offset = 0;
            $first = $last = null;
            $firstStart = $lastStart = 0;
            foreach ($texts as $i => $t) {
                $length = mb_strlen($t->textContent);
                if ($first === null && $position < $offset + $length) {
                    $first = $i;
                    $firstStart = $offset;
                }
                if ($first !== null && $matchEnd <= $offset + $length) {
                    $last = $i;
                    $lastStart = $offset;
                    break;
                }
                $offset += $length;
            }
            if ($first === null || $last === null) {
                continue;
            }

            $prefix = mb_substr($texts[$first]->textContent, 0, $position - $firstStart);
            $suffix = mb_substr($texts[$last]->textContent, $matchEnd - $lastStart);
            $texts[$first]->textContent = $prefix.$replace.$suffix;
            for ($i = $first + 1; $i <= $last; $i++) {
                $run = $texts[$i]->parentNode;
                $run?->parentNode?->removeChild($run);
            }
        }

        return $count;
    }

    public static function text(DOMDocument $slide): string
    {
        $parts = [];
        foreach ($slide->getElementsByTagNameNS(self::NS_A, 't') as $t) {
            $parts[] = $t->textContent;
        }

        return implode(' ', $parts);
    }

    public static function shapeName(DOMElement $shape): string
    {
        $cNvPr = $shape->getElementsByTagNameNS(self::NS_P, 'cNvPr')->item(0);

        return $cNvPr instanceof DOMElement ? $cNvPr->getAttribute('name') : '';
    }

    /**
     * @return array{0: ?string, 1: ?string} x, y offsets as strings (EMU)
     */
    public static function offset(DOMElement $shape): array
    {
        $off = $shape->getElementsByTagNameNS(self::NS_A, 'off')->item(0);

        return $off instanceof DOMElement ? [$off->getAttribute('x'), $off->getAttribute('y')] : [null, null];
    }

    private static function spTree(DOMDocument $slide): DOMElement
    {
        $tree = $slide->getElementsByTagNameNS(self::NS_P, 'spTree')->item(0);
        if (! $tree instanceof DOMElement) {
            throw new RuntimeException('Slide has no spTree');
        }

        return $tree;
    }
}
