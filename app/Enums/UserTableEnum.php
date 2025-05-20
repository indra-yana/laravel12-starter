<?php

namespace App\Enums;

enum UserTableEnum: string
{
    case Name = 'name';
    case Email = 'email';
    case Status = 'is_active';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function allowedFilters(): array
    {
        return [
            self::Name->value,
            self::Email->value,
            self::Status->value,
        ];
    }
}
