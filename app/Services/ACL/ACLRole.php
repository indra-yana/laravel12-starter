<?php

namespace App\Services\ACL;

enum ACLRole: string
{
    case SUPER_ADMIN = 'Super Admin';
    case ADMIN = 'Admin';
    case USER = 'User';

    public function permissions(): array
    {
        return match ($this) {
            self::SUPER_ADMIN => array_unique(
                array_merge(
                    ACLPermission::DASHBOARD->permissions(),
                    ACLPermission::PROFILE->permissions(),
                    ACLPermission::PASSWORD->permissions(),
                    ACLPermission::APPEARANCE->permissions(),
                    ACLPermission::USER->permissions(),
                    ACLPermission::PERMISSION->permissions(),
                )
            ),
            self::ADMIN => array_unique(
                array_merge(
                    ACLPermission::DASHBOARD->permissions(),
                    ACLPermission::PROFILE->permissions(),
                    ACLPermission::PASSWORD->permissions(),
                    ACLPermission::APPEARANCE->permissions(),
                    ACLPermission::USER->permissions(),
                ),
            ),
            self::USER => array_unique(
                array_merge(
                    ACLPermission::DASHBOARD->permissions(),
                    ACLPermission::PROFILE->permissions(),
                    ACLPermission::PASSWORD->permissions(),
                    ACLPermission::APPEARANCE->permissions(),
                ),
            ),
        };
    }

    public static function all(): array
    {
        return array_map(fn($case) => [
            'role' => $case->value,
            'permissions' => $case->permissions(),
        ], self::cases());
    }
}
