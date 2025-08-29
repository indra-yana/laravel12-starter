<?php

namespace App\Services\ACL;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;

class PermissionService
{

	/**
	 * Assign user permissions
	 * 
	 * @return int
	 */
	public function assignUserPermissions(int $userId, array $permissions): int
	{
		app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

		$user = User::find($userId);
		$validPermissions = Permission::whereIn('name', $permissions)->pluck('name')->toArray();
		$user->syncPermissions($validPermissions);

		return count($validPermissions);
	}

	/**
	 * Get all permissions organized by groups
	 * 
	 * @return array
	 */
	public function getAllPermissions(): array
	{
		return ACLPermission::all();
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

	/**
	 * Get user permission with mapped permissions 
	 *
	 * @param int $userId
	 * @return array
	 */
	public function getUserPermissions(User|int|null $user = null): array
	{
		if (!$user) {
			return [];
		}

		if (!$user instanceof User) {
			$user = User::with([
				'permissions' => function ($query) {
					$query->get(['id', 'name']);
				},
			])->findOrFail($user);
		}

		// Get all the permissions both directly and via roles.
		$userPermissions = $this->mapPermissions($user->getAllPermissions());
		return Arr::pluck($userPermissions, 'name');
	}

	/**
	 * Map the results
	 *
	 * @param Collection $permissions
	 * @return array
	 */
	private function mapPermissions(Collection $permissions): array
	{
		return $permissions->map(fn($permission) => [
			'id' => $permission->id,
			'name' => $permission->name,
		])->toArray();
	}
}
