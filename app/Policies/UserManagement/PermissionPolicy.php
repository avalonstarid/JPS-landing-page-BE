<?php

namespace App\Policies\UserManagement;

use App\Models\User;
use App\Models\UserManagement\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PermissionPolicy
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
		return $user->can('permission_read');
	}

	/**
	 * Determine whether the user can view the model.
	 *
	 * @param User       $user
	 * @param Permission $permission
	 *
	 * @return Response|bool
	 */
	public function view(User $user, Permission $permission): Response|bool
	{
		return $user->can('permission_read');
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
		return $user->can('permission_create');
	}

	/**
	 * Determine whether the user can update the model.
	 *
	 * @param User       $user
	 * @param Permission $permission
	 *
	 * @return Response|bool
	 */
	public function update(User $user, Permission $permission): Response|bool
	{
		return $user->can('permission_update');
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param User       $user
	 * @param Permission $permission
	 *
	 * @return Response|bool
	 */
	public function delete(User $user, Permission $permission): Response|bool
	{
		return $user->can('permission_delete');
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
		return $user->can('permission_delete');
	}

	/**
	 * Determine whether the user can restore the model.
	 *
	 * @param User       $user
	 * @param Permission $permission
	 *
	 * @return Response|bool
	 */
	public function restore(User $user, Permission $permission)
	{
		//
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 *
	 * @param User       $user
	 * @param Permission $permission
	 *
	 * @return Response|bool
	 */
	public function forceDelete(User $user, Permission $permission)
	{
		//
	}
}
