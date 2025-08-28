<?php

namespace App\Services\ACL;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;

class RoleService
{

	/**
	 * Assign user roles
	 * 
	 * @return int
	 */
	public function assignUserPermissions(int $userId, array $roles): int
	{
		app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

		$user = User::find($userId);
		$validRoles = Role::whereIn('name', $roles)->pluck('name')->toArray();
		$user->syncRoles($validRoles);

		return count($validRoles);
	}

	/**
	 * Define all role
	 * 
	 * @return array
	 */
	public function getAllRoles(): array
	{
		$roles = [
			[
				'role' => 'Super Admin',
				'permissions' => [
					'dashboard.index',
					'profile.edit',
					'profile.update',
					'profile.destroy',
					'profile.edit',
					'profile.update',
					'appearance.index',
					'user.index',
					'user.create',
					'user.update',
					'user.destroy',
					'permission.index',
					'permission.create',
					'permission.update',
					'permission.destroy',
				],
			],
			[
				'role' => 'Admin',
				'permissions' => [
					'dashboard.index',
					'profile.edit',
					'profile.update',
					'profile.destroy',
					'profile.edit',
					'profile.update',
					'appearance.index',
					'user.index',
					'user.create',
					'user.update',
					'user.destroy',
				],
			],
			[
				'role' => 'User',
				'permissions' => [
					'dashboard.index',
					'profile.edit',
					'profile.update',
					'profile.destroy',
					'profile.edit',
					'profile.update',
					'appearance.index',
					'user.index',
					'user.create',
					'user.update',
					'user.destroy',
				],
			],
		];

		return $roles;
	}

	/**
	 * Create all roles from the definitions
	 * 
	 * @return array Created roles
	 */
	public function createRoles(): array
	{
		$createdRoles = [];
		$roles = $this->getAllRoles();

		foreach ($roles as $permissionGroup) {
			$roleName = $permissionGroup['role'];
			$permissions = $permissionGroup['permissions'];

			$createdRoles[] = $this->findOrCreateRole($roleName, $permissions);
		}

		return $createdRoles;
	}

	/**
	 * Find or create a role
	 * 
	 * @param string $name
	 * @param array $permissions
	 * @return Role
	 */
	public function findOrCreateRole(string $name, string $permissions): Role
	{
		$role = Role::firstOrCreate(
			['name' => $name],
			[
				'name' => $name,
				'guard_name' => 'web',
			]
		);

		$role->syncPermissions($permissions);
		return $role;
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

		if ($user->permissions->count()) {
			// if has direct permissions use it
			$userPermissions = $this->mapPermissions($user->permissions);
		} else {
			// get the permissions via roles
			$userPermissions = $this->mapPermissions($user->getAllPermissions());
		}

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
