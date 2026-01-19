<?php

namespace App\Enums;

enum Status: string
{
    case ACTIVE = 'Aktif';
    case INACTIVE = 'Tidak Aktif';

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
}
