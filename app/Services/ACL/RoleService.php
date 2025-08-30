<?php

namespace App\Services\ACL;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService
{

	/**
	 * Assign user roles
	 * 
	 * @return int
	 */
	public function assignUserRoleAndPermissions(int $userId, string $role): int
	{
		app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

		$user = User::find($userId);
		$validRoles = Role::whereIn('name', [$role])->pluck('name')->toArray();
		$user->syncRoles($validRoles);

		// Add permission directly based on selected role
		$permissions = $this->getPermissionsByRole($role);
		$validPermissions = Permission::whereIn('name', $permissions)->pluck('name')->toArray();
		$user->syncPermissions($validPermissions);

		return count($validPermissions);
	}

	/**
	 * Define all role
	 * 
	 * @return array
	 */
	public function getAllRoles(bool $flat = false): array
	{
		return $flat ? Arr::pluck(ACLRole::all(), 'role') : ACLRole::all();
	}

	/**
	 * Define all role
	 * 
	 * @return array
	 */
	public function getPermissionsByRole(string $roleName): array
	{
		$role = ACLRole::tryFrom($roleName);

		if (!$role) {
			throw new InvalidArgumentException("Role {$roleName} tidak ditemukan.");
		}

		return $role->permissions();
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
			$createdRoles[] = $this->findOrCreateRole(
				$permissionGroup['role'],
				$permissionGroup['permissions']
			);
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
	public function findOrCreateRole(string $name, array $permissions): Role
	{
		$role = Role::firstOrCreate(
			['name' => $name],
			[
				'name' => $name,
				'guard_name' => 'web',
			]
		);

		// TODO: Enable this if needed
		// $role->syncPermissions($permissions);

		return $role;
	}

	/**
	 * Get user role
	 *
	 * @param int $userId
	 * @return array
	 */
	public function getUserRole(User|int|null $user = null): array
	{
		if (!$user) {
			return [];
		}

		if (!$user instanceof User) {
			$user = User::with([
				'roles' => function ($query) {
					$query->get(['id', 'name']);
				},
			])->findOrFail($user);
		}

		return $this->mapRole($user->roles);
	}

	/**
	 * Map the results
	 *
	 * @param Collection $permissions
	 * @return array
	 */
	private function mapRole(Collection $roles): array
	{
		return $roles->map(fn($role) => [
			'id' => $role->id,
			'name' => $role->name,
		])->toArray();
	}
}
