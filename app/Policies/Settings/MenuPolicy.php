<?php

namespace App\Policies\Settings;

use App\Models\Settings\Menu;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class MenuPolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view any models.
	 *
	 * @param User $user
	 *
	 * @return Response|bool
	 */
	public function viewAny(User $user): Response|bool
	{
		return $user->can('menu_read');
	}

	/**
	 * Determine whether the user can view the model.
	 *
	 * @param User $user
	 * @param Menu $menu
	 *
	 * @return Response|bool
	 */
	public function view(User $user, Menu $menu): Response|bool
	{
		return $user->can('menu_read');
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
		return $user->can('menu_create');
	}

	/**
	 * Determine whether the user can update the model.
	 *
	 * @param User $user
	 * @param Menu $menu
	 *
	 * @return Response|bool
	 */
	public function update(User $user, Menu $menu): Response|bool
	{
		return $user->can('menu_update');
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param User $user
	 * @param Menu $menu
	 *
	 * @return Response|bool
	 */
	public function delete(User $user, Menu $menu): Response|bool
	{
		return $user->can('menu_delete');
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
		return $user->can('menu_delete');
	}

	/**
	 * Determine whether the user can restore the model.
	 *
	 * @param User $user
	 * @param Menu $menu
	 *
	 * @return Response|bool
	 */
	public function restore(User $user, Menu $menu)
	{
		//
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 *
	 * @param User $user
	 * @param Menu $menu
	 *
	 * @return Response|bool
	 */
	public function forceDelete(User $user, Menu $menu)
	{
		//
	}
}
