<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TransactionPolicy
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
		return $user->can('transaction_read');
	}

	/**
	 * Determine whether the user can view the model.
	 *
	 * @param User        $user
	 * @param Transaction $transaction
	 *
	 * @return Response|bool
	 */
	public function view(User $user, Transaction $transaction): Response|bool
	{
		return $user->can('transaction_read');
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
		return $user->can('transaction_create');
	}

	/**
	 * Determine whether the user can update the model.
	 *
	 * @param User        $user
	 * @param Transaction $transaction
	 *
	 * @return Response|bool
	 */
	public function update(User $user, Transaction $transaction): Response|bool
	{
		return $user->can('transaction_update');
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param User        $user
	 * @param Transaction $transaction
	 *
	 * @return Response|bool
	 */
	public function delete(User $user, Transaction $transaction): Response|bool
	{
		return $user->can('transaction_delete');
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
		return $user->can('transaction_delete');
	}

	/**
	 * Determine whether the user can restore the model.
	 *
	 * @param User        $user
	 * @param Transaction $transaction
	 *
	 * @return Response|bool
	 */
	public function restore(User $user, Transaction $transaction)
	{
		//
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 *
	 * @param User        $user
	 * @param Transaction $transaction
	 *
	 * @return Response|bool
	 */
	public function forceDelete(User $user, Transaction $transaction)
	{
		//
	}
}
