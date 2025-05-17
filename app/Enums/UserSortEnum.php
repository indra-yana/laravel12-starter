<?php

namespace App\Enums;

enum UserSortEnum: string
{
    case Name = 'name';
    case Email = 'email';
    case Status = 'is_active';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
