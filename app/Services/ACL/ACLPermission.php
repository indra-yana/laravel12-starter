<?php

namespace App\Services\ACL;

enum ACLPermission: string
{
    case DASHBOARD = 'dashboard';
    case PROFILE = 'profile';
    case PASSWORD = 'password';
    case APPEARANCE = 'appearance';
    case USER = 'user';
    case PERMISSION = 'permission';

    public function permissions(): array
    {
        return match ($this) {
            self::DASHBOARD => [
                'dashboard.index',
            ],
            self::PROFILE => [
                'profile.edit',
                'profile.update',
                'profile.destroy',
            ],
            self::PASSWORD => [
                'profile.edit',
                'profile.update',
            ],
            self::APPEARANCE => [
                'appearance.index',
            ],
            self::USER => [
                'user.index',
                'user.create',
                'user.update',
                'user.destroy',
            ],
            self::PERMISSION => [
                'permission.index',
                'permission.create',
                'permission.update',
                'permission.destroy',
            ],
        };
    }

    public static function all(): array
    {
        return array_map(fn($case) => [
            'group_name' => $case->value,
            'permissions' => $case->permissions(),
        ], self::cases());
    }
}
