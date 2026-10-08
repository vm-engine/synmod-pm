<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Proposal\Pptx;

use DOMElement;

/**
 * Slide roles in resources/templates/proposal/proposal.pptx (the Think deck).
 * Replacing the template means updating these numbers.
 */
final class TemplateMap
{
    public const COVER = 1;

    public const DIVIDER = 12;

    public const BACKGROUND = 13;

    public const GOAL = 14;

    public const MENU = 15;

    public const MOCKUP = 16;

    public const FEATURES = 20;

    public const FEATURES_EXTRA = 21;

    public const COSTING = 22;

    public const HOSTING = 23;

    public const TIMELINE = 24;

    public const DELIVERABLES = 25;

    public const AFTER_SALES = 26;

    /** Slides that are plain images in the PDF (company profile, tech/security/framework, closing). */
    public const STATIC_PDF_SLIDES = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 17, 18, 19, 27, 28, 29, 30];

    /** Y offset (EMU) of the red underline bar under each slide title. */
    private const CHROME_BAR_Y = '1269759';

    /** X offset (EMU) of the 4VM logo at the top right. */
    private const CHROME_LOGO_X = '10155590';

    public static function packageRoot(): string
    {
        return dirname(__DIR__, 4);
    }

    public static function path(): string
    {
        return self::asset('proposal.pptx');
    }

    public static function asset(string $relative): string
    {
        return self::packageRoot().'/resources/templates/proposal/'.ltrim($relative, '/');
    }

    /** Title, red bar and logo survive a body replacement; everything else is content. */
    public static function isChrome(DOMElement $shape): bool
    {
        if (str_starts_with(PptxSlide::shapeName($shape), 'Title')) {
            return true;
        }
        [$x, $y] = PptxSlide::offset($shape);

        return ($shape->localName === 'sp' && $y === self::CHROME_BAR_Y)
            || ($shape->localName === 'pic' && $x === self::CHROME_LOGO_X);
    }
}
