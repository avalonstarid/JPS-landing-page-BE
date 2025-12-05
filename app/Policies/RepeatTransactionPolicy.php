<?php

namespace App\Policies;

use App\Models\RepeatTransaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RepeatTransactionPolicy
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
		return $user->can('repeat_transaction_read');
	}

	/**
	 * Determine whether the user can view the model.
	 *
	 * @param User              $user
	 * @param RepeatTransaction $repeatTransaction
	 *
	 * @return Response|bool
	 */
	public function view(User $user, RepeatTransaction $repeatTransaction): Response|bool
	{
		return $user->can('repeat_transaction_read');
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
		return $user->can('repeat_transaction_create');
	}

	/**
	 * Determine whether the user can update the model.
	 *
	 * @param User              $user
	 * @param RepeatTransaction $repeatTransaction
	 *
	 * @return Response|bool
	 */
	public function update(User $user, RepeatTransaction $repeatTransaction): Response|bool
	{
		return $user->can('repeat_transaction_update');
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param User              $user
	 * @param RepeatTransaction $repeatTransaction
	 *
	 * @return Response|bool
	 */
	public function delete(User $user, RepeatTransaction $repeatTransaction): Response|bool
	{
		return $user->can('repeat_transaction_delete');
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
		return $user->can('repeat_transaction_delete');
	}

	/**
	 * Determine whether the user can restore the model.
	 *
	 * @param User              $user
	 * @param RepeatTransaction $repeatTransaction
	 *
	 * @return Response|bool
	 */
	public function restore(User $user, RepeatTransaction $repeatTransaction)
	{
		//
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 *
	 * @param User              $user
	 * @param RepeatTransaction $repeatTransaction
	 *
	 * @return Response|bool
	 */
	public function forceDelete(User $user, RepeatTransaction $repeatTransaction)
	{
		//
	}
}
