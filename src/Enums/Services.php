<?php

namespace VmEngine\Pm\Enums;

enum Services: int
{
    case COMPRO = 1;
    case WEBAPP = 2;
    case HOSTING = 3;
    case DOMAIN = 4;
    case MAINTENANCE = 5;
    case DIGITAL_MARKETING = 6;
    case FOUR_VM_STUDIO = 7;
    case CCTV = 8;

    public function label(): string
    {
        return match ($this) {
            Services::COMPRO => 'Company Profile',
            Services::WEBAPP => 'Web Application',
            Services::HOSTING => 'Hosting',
            Services::DOMAIN => 'Domain',
            Services::MAINTENANCE => 'Maintenance',
            Services::DIGITAL_MARKETING => 'Digital Marketing',
            Services::FOUR_VM_STUDIO => '4vm Studio',
            Services::CCTV => 'CCTV',
        };
    }
}
