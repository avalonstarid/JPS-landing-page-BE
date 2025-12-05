<?php

namespace App\Policies\UserManagement;

use App\Models\User;
use App\Models\UserManagement\Role;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RolePolicy
{
	use HandlesAuthorization;

	/**
	 * Create a new policy instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Determine whether the user can view any models.
	 *
	 * @param User $user
	 *
	 * @return Response|bool
	 */
	public function viewAny(User $user): Response|bool
	{
		return $user->can('role_read');
	}

	/**
	 * Determine whether the user can view the model.
	 *
	 * @param User $user
	 * @param Role $role
	 *
	 * @return Response|bool
	 */
	public function view(User $user, Role $role): Response|bool
	{
		return $user->can('role_read');
	}

	/**
	 * Determine whether the user can create models.
	 *
	 * @param User $user
	 *
	 * @return Response|bool
	 */
	public function create(User $user): Response|bool
	{
		return $user->can('role_create');
	}

	/**
	 * Determine whether the user can update the model.
	 *
	 * @param User $user
	 * @param Role $role
	 *
	 * @return Response|bool
	 */
	public function update(User $user, Role $role): Response|bool
	{
		return $user->can('role_update');
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param User $user
	 * @param Role $role
	 *
	 * @return Response|bool
	 */
	public function delete(User $user, Role $role): Response|bool
	{
		return $user->can('role_delete');
	}

	/**
	 * Determine whether the user can bulk delete the model.
	 *
	 * @param User $user
	 *
	 * @return Response|bool
	 */
	public function bulkDelete(User $user): Response|bool
	{
		return $user->can('role_delete');
	}

	/**
	 * Determine whether the user can restore the model.
	 *
	 * @param User $user
	 * @param Role $role
	 *
	 * @return Response|bool
	 */
	public function restore(User $user, Role $role)
	{
		//
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 *
	 * @param User $user
	 * @param Role $role
	 *
	 * @return Response|bool
	 */
	public function forceDelete(User $user, Role $role)
	{
		//
	}
}
