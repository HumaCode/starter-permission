<?php

namespace App\Enums;

use InvalidArgumentException;

enum MonthEnum: string
{
    case JANUARI = 'januari';
    case FEBRUARI = 'februari';
    case MARET = 'maret';
    case APRIL = 'april';
    case MEI = 'mei';
    case JUNI = 'juni';
    case JULI = 'juli';
    case AGUSTUS = 'agustus';
    case SEPTEMBER = 'september';
    case OKTOBER = 'oktober';
    case NOVEMBER = 'november';
    case DESEMBER = 'desember';

    public static function options(array $exclude = []): array
    {
        return collect(self::cases())
            ->filter(fn ($item) => ! in_array($item->name, $exclude))
            ->map(fn ($item) => [
                'value' => $item->value,
                'label' => $item->value,
            ])
            ->values()
            ->toArray();
    }

    public static function month(int $month): self
    {
        return match ($month) {
            1 => self::JANUARI,
            2 => self::FEBRUARI,
            3 => self::MARET,
            4 => self::APRIL,
            5 => self::MEI,
            6 => self::JUNI,
            7 => self::JULI,
            8 => self::AGUSTUS,
            9 => self::SEPTEMBER,
            10 => self::OKTOBER,
            11 => self::NOVEMBER,
            12 => self::DESEMBER,

            default => throw new InvalidArgumentException("Invalid month: {$month}"),
        };
    }
}
