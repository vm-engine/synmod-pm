<?php

namespace VmEngine\Pm\Enums;

enum SalesSources: int
{
    case WEB = 1;
    case MEDIA = 2;
    case VIRAL = 3;
    case NETWORKING = 4;
    case RESELLER = 5;
    case SEARCH_ENGINE = 6;

    public function label(): string
    {
        return match ($this) {
            SalesSources::WEB => 'Web',
            SalesSources::MEDIA => 'Media',
            SalesSources::VIRAL => 'Viral',
            SalesSources::NETWORKING => 'Networking',
            SalesSources::RESELLER => 'Reseller',
            SalesSources::SEARCH_ENGINE => 'Search Engine',
        };
    }
}
