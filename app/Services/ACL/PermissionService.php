<?php

namespace App\Services\ACL;

use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Permission;

class PermissionService
{
	/**
	 * Get all permissions organized by groups
	 * 
	 * @return array
	 */
	public function getAllPermissions(): array
	{
		$permissions = [
			[
				'group_name' => 'dashboard',
				'permissions' => [
					'dashboard.index',
				],
			],
			[
				'group_name' => 'profile',
				'permissions' => [
					'profile.edit',
					'profile.update',
					'profile.destroy',
				],
			],
			[
				'group_name' => 'password',
				'permissions' => [
					'profile.edit',
					'profile.update',
				],
			],
			[
				'group_name' => 'appearance',
				'permissions' => [
					'appearance.index',
				],
			],
			[
				'group_name' => 'user',
				'permissions' => [
					'user.index',
					'user.create',
					'user.update',
					'user.destroy',
				],
			],
			[
				'group_name' => 'permission',
				'permissions' => [
					'permission.index',
					'permission.create',
					'permission.update',
					'permission.destroy',
				],
			],
		];

		return $permissions;
	}

	/**
	 * Create all permissions from the definitions
	 * 
	 * @return array Created permissions
	 */
	public function createPermissions(): array
	{
		$createdPermissions = [];
		$permissions = $this->getAllPermissions();

		foreach ($permissions as $permissionGroup) {
			$groupName = $permissionGroup['group_name'];

			foreach ($permissionGroup['permissions'] as $permissionName) {
				$permission = $this->findOrCreatePermission($permissionName, $groupName);
				$createdPermissions[] = $permission;
			}
		}

		return $createdPermissions;
	}

	/**
	 * Find or create a permission
	 * 
	 * @param string $name
	 * @param string $groupName
	 * @return Permission
	 */
	public function findOrCreatePermission(string $name, string $groupName): Permission
	{
		return Permission::firstOrCreate(
			['name' => $name],
			[
				'name' => $name,
				'group_name' => $groupName,
				'guard_name' => 'web',
			]
		);
	}

	/**
	 * Get permissions by group name from database
	 *
	 * @param string $group_name
	 * @return Collection
	 */
	public function getPermissionByGroupName(?string $group_name = null)
	{
		return Permission::select('id', 'name', 'group_name')
			->when($group_name, fn($q) => $q->where('group_name', $group_name))
			->get()
			->groupBy('group_name');
	}
}
