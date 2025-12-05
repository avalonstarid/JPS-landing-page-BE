<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class AccountPolicy
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
		return $user->can('account_read');
	}

	/**
	 * Determine whether the user can view the model.
	 *
	 * @param User    $user
	 * @param Account $account
	 *
	 * @return Response|bool
	 */
	public function view(User $user, Account $account): Response|bool
	{
		return $user->can('account_read');
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
		return $user->can('account_create');
	}

	/**
	 * Determine whether the user can update the model.
	 *
	 * @param User    $user
	 * @param Account $account
	 *
	 * @return Response|bool
	 */
	public function update(User $user, Account $account): Response|bool
	{
		return $user->can('account_update');
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param User    $user
	 * @param Account $account
	 *
	 * @return Response|bool
	 */
	public function delete(User $user, Account $account): Response|bool
	{
		return $user->can('account_delete');
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param User    $user
	 * @param Account $account
	 *
	 * @return Response|bool
	 */
	public function adjust(User $user, Account $account): Response|bool
	{
		return $user->can('account_adjust');
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
		return $user->can('account_delete');
	}

	/**
	 * Determine whether the user can restore the model.
	 *
	 * @param User    $user
	 * @param Account $account
	 *
	 * @return Response|bool
	 */
	public function restore(User $user, Account $account)
	{
		//
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 *
	 * @param User    $user
	 * @param Account $account
	 *
	 * @return Response|bool
	 */
	public function forceDelete(User $user, Account $account)
	{
		//
	}
}
