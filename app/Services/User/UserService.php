<?php

namespace App\Services\User;

use App\Enums\UserTableEnum;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
	public function __construct() {}

	/**
	 * Store a new user
	 */
	public function store(array $params)
	{
		$user = User::create([
			'name' => $params['name'],
			'email' => $params['email'],
			'password' => Hash::make($params['password'] ?? 'password'),
			'is_active' => $params['is_active'] ?? false,
		]);

		return $user->only(['id']);
	}

	/**
	 * Get user by ID
	 */
	public function find(int $id): ?User
	{
		return User::find($id);
	}

	/**
	 * Update user by ID
	 */
	public function update(int $id, array $params): ?User
	{
		$user = User::find($id);
		$passwordUpdate = [];

		if (!empty($params['password'])) {
			$passwordUpdate = [
				'password' => Hash::make($params['password']),
			];
		}

		$user->update(array_merge($passwordUpdate, [
			'name' => $params['name'] ?? $user->name,
			'email' => $params['email'] ?? $user->email,
			'is_active' => $params['is_active'] ?? $user->is_active,
		]));

		return $user->only(['id']);
	}

	/**
	 * Delete user by ID
	 */
	public function destroy(int|array $ids): bool
	{
		$ids = is_array($ids) ? $ids : [$ids];

		return User::whereIn('id', $ids)->delete();
	}

	/**
	 * Get all users
	 */
	public function getAll(array $params = [])
	{
		$allowedFilters = UserTableEnum::allowedFilters();
		$columnFilters = $params['column_filters'] ?? null;
		$search = $params['search'];
		$sorting = in_array($params['sorting'], UserTableEnum::values())
			? $params['sorting']
			: UserTableEnum::Name->value;
		$direction = in_array($params['direction'], ['asc', 'desc'])
			? $params['direction']
			: 'asc';

		$users = User::query()
			->when(
				$search && !$columnFilters,
				function ($query) use ($allowedFilters, $search) {
					foreach ($allowedFilters as $columnFilter) {
						$query->orWhere($columnFilter, 'ilike', "%{$search}%");
					}
				}
			)
			->when($columnFilters && $search && is_array($columnFilters), function ($query) use ($columnFilters, $allowedFilters) {
				foreach ($columnFilters as $filter) {
					$column = $filter['id'] ?? null;
					$value = $filter['value'] ?? null;

					if ($column && $value !== null && in_array($column, $allowedFilters)) {
						$query->orWhere($column, 'ilike', "%{$value}%");
					}
				}
			})
			->orderBy($sorting, $direction)
			->paginate($params['per_page'] ?? 10)
			->withQueryString();

		return $users;
	}
}
