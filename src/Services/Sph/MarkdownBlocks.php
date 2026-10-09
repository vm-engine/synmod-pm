<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Sph;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Extension\CommonMark\Node\Block\ListBlock;
use League\CommonMark\Extension\CommonMark\Node\Inline\Emphasis;
use League\CommonMark\Extension\CommonMark\Node\Inline\Strong;
use League\CommonMark\Node\Block\Paragraph;
use League\CommonMark\Node\Inline\Newline;
use League\CommonMark\Node\Node;
use League\CommonMark\Node\StringContainerInterface;
use League\CommonMark\Parser\MarkdownParser;

/**
 * The Markdown subset of SPH item descriptions, flattened for the DOCX and PDF renderers:
 * paragraphs, bullet/numbered lists (nested) and bold/italic. Anything else becomes plain text.
 * Level 0 is a plain paragraph, level N a paragraph inside an Nth-level list item.
 *
 * @phpstan-type Run array{text: string, bold: bool, italic: bool}
 * @phpstan-type Block array{level: int, marker: string, runs: list<Run>}
 */
final class MarkdownBlocks
{
    /**
     * @return list<Block>
     */
    public static function parse(string $markdown): array
    {
        if (trim($markdown) === '') {
            return [];
        }

        $environment = new Environment;
        $environment->addExtension(new CommonMarkCoreExtension);
        $blocks = [];
        self::collect((new MarkdownParser($environment))->parse($markdown), 0, '', $blocks);

        return $blocks;
    }

    /**
     * @param  list<Block>  $blocks
     */
    private static function collect(Node $parent, int $level, string $marker, array &$blocks): void
    {
        foreach ($parent->children() as $node) {
            if ($node instanceof ListBlock) {
                $data = $node->getListData();
                $number = $data->start ?? 1;
                foreach ($node->children() as $item) {
                    $itemMarker = $data->type === ListBlock::TYPE_ORDERED ? ($number++).'.' : '•';
                    self::collect($item, $level + 1, $itemMarker, $blocks);
                }
                $marker = '';

                continue;
            }

            if ($node instanceof Paragraph || $node instanceof Heading) {
                $runs = self::runs($node, false, false);
            } elseif ($node instanceof StringContainerInterface) {
                // Code blocks and raw HTML: literal text
                $runs = [['text' => trim($node->getLiteral()), 'bold' => false, 'italic' => false]];
            } else {
                // Block quotes and other containers
                $before = count($blocks);
                self::collect($node, $level, $marker, $blocks);
                $marker = count($blocks) > $before ? '' : $marker;

                continue;
            }

            $runs = array_values(array_filter($runs, fn (array $run): bool => $run['text'] !== ''));
            if ($runs === []) {
                continue;
            }
            $blocks[] = ['level' => $level, 'marker' => $marker, 'runs' => $runs];
            $marker = '';
        }
    }

    /**
     * @return list<Run>
     */
    private static function runs(Node $node, bool $bold, bool $italic): array
    {
        $runs = [];
        foreach ($node->children() as $child) {
            if ($child instanceof Newline) {
                $runs[] = ['text' => ' ', 'bold' => $bold, 'italic' => $italic];
            } elseif ($child instanceof StringContainerInterface) {
                $runs[] = ['text' => $child->getLiteral(), 'bold' => $bold, 'italic' => $italic];
            } else {
                // Strong, emphasis, links (their text), images (alt text)
                array_push($runs, ...self::runs($child, $bold || $child instanceof Strong, $italic || $child instanceof Emphasis));
            }
        }

        return $runs;
    }
}
