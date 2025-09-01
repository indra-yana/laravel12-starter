<?php

namespace App\Services\ACL;

enum ACLPermission: string
{
    case DASHBOARD = 'dashboard';
    case PROFILE = 'profile';
    case PASSWORD = 'password';
    case APPEARANCE = 'appearance';
    case USER = 'user';
    case ROLE = 'role';
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
                'password.edit',
                'password.update',
            ],
            self::APPEARANCE => [
                'appearance.index',
            ],
            self::USER => [
                'users.index',
                'users.create',
                'users.update',
                'users.destroy',
            ],
            self::ROLE => [
                'roles.index',
                'roles.assign',
            ],
            self::PERMISSION => [
                'permissions.index',
                'permissions.assign',
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
