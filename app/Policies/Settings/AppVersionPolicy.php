<?php

namespace App\Policies\Settings;

use App\Models\AppVersion;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class AppVersionPolicy
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
		return $user->can('app_version_read');
	}

	/**
	 * Determine whether the user can view the model.
	 *
	 * @param User       $user
	 * @param AppVersion $appVersion
	 *
	 * @return Response|bool
	 */
	public function view(User $user, AppVersion $appVersion): Response|bool
	{
		return $user->can('app_version_read');
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
		return $user->can('app_version_create');
	}

	/**
	 * Determine whether the user can update the model.
	 *
	 * @param User       $user
	 * @param AppVersion $appVersion
	 *
	 * @return Response|bool
	 */
	public function update(User $user, AppVersion $appVersion): Response|bool
	{
		return $user->can('app_version_update');
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param User       $user
	 * @param AppVersion $appVersion
	 *
	 * @return Response|bool
	 */
	public function delete(User $user, AppVersion $appVersion): Response|bool
	{
		return $user->can('app_version_delete');
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
		return $user->can('app_version_delete');
	}

	/**
	 * Determine whether the user can restore the model.
	 *
	 * @param User       $user
	 * @param AppVersion $appVersion
	 *
	 * @return Response|bool
	 */
	public function restore(User $user, AppVersion $appVersion)
	{
		//
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 *
	 * @param User       $user
	 * @param AppVersion $appVersion
	 *
	 * @return Response|bool
	 */
	public function forceDelete(User $user, AppVersion $appVersion)
	{
		//
	}
}
