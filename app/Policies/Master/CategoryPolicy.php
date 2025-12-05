<?php

namespace App\Policies\Master;

use App\Models\Master\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
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
		return $user->can('category_read');
	}

	/**
	 * Determine whether the user can view the model.
	 *
	 * @param User     $user
	 * @param Category $category
	 *
	 * @return Response|bool
	 */
	public function view(User $user, Category $category): Response|bool
	{
		return $user->can('category_read');
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
		return $user->can('category_create');
	}

	/**
	 * Determine whether the user can update the model.
	 *
	 * @param User     $user
	 * @param Category $category
	 *
	 * @return Response|bool
	 */
	public function update(User $user, Category $category): Response|bool
	{
		return $user->can('category_update') || $category->created_by_id == $user->id;
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param User     $user
	 * @param Category $category
	 *
	 * @return Response|bool
	 */
	public function delete(User $user, Category $category): Response|bool
	{
		return $user->can('category_delete') || $category->created_by_id == $user->id;
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
		return $user->can('category_delete');
	}

	/**
	 * Determine whether the user can restore the model.
	 *
	 * @param User     $user
	 * @param Category $category
	 *
	 * @return Response|bool
	 */
	public function restore(User $user, Category $category)
	{
		//
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 *
	 * @param User     $user
	 * @param Category $category
	 *
	 * @return Response|bool
	 */
	public function forceDelete(User $user, Category $category)
	{
		//
	}
}
