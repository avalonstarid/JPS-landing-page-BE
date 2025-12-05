<?php

namespace App\Http\Controllers\V1\UserManagement;

use App\Exports\UserManagement\UserExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserManagement\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Subgroup;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

#[Group("User Management", "API Endpoint for User Management.")]
#[Subgroup("Users", "API endpoint for users management.")]
class UserController extends Controller
{
	/**
	 * Get Data
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	#[QueryParam('filter[search]', required: false, example: '', enum: ['active', 'role', 'search'])]
	#[QueryParam('rows', 'int', required: false, example: 10)]
	#[QueryParam('sort', required: false, example: '', enum: ['created_at', 'email', 'name'])]
	public function index(Request $request): JsonResponse
	{
//		$this->authorize('viewAny', User::class);

		$query = $this->query();

		if ($request->input('all', '') == 1) {
			return $this->response(
				message: 'Berhasil mengambil data.',
				data: $query->get(),
			);
		} else {
			$request->merge([
				'page' => $request->input('page', 1),
			]);

			$data = $query->fastPaginate($request->input('rows', 10))->withQueryString();

			return response()->json(array_merge([
				'success' => true,
				'message' => 'Berhasil mengambil data.',
			], $data->toArray()));
		}
	}

	/**
	 * Insert Data
	 *
	 * @param UserRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(UserRequest $request): JsonResponse
	{
		try {
			$this->authorize('create', User::class);

			DB::beginTransaction();

			$data = User::create($request->safe()->except(['avatar', 'avatar_remove', 'permissions', 'roles']));

			// Sync Roles & Permission
			$data->syncRoles($request->safe()->input('roles'));
			$data->syncPermissions($request->safe()->array('permissions'));

			// Upload Image
			if ($request->hasFile('avatar')) {
				$data->addMedia($request->file('avatar'))->toMediaCollection('avatar');
			} else if ($request->safe()->boolean('avatar_remove')) {
				$data->clearMediaCollection('avatar');
			}

			DB::commit();

			return $this->response(
				message: 'Berhasil menambah data.',
				data: $data,
				status_code: 201,
			);
		} catch (Exception $e) {
			DB::rollBack();
			return $this->response(
				message: $e->getMessage(),
				status_code: $e->getCode(),
			);
		}
	}

	/**
	 * Get Detail Data
	 *
	 * @param User $user
	 *
	 * @return JsonResponse
	 */
	public function show(User $user): JsonResponse
	{
//		$this->authorize('view', $user);

		$user = $user->load(['roles:id', 'permissions:id']);
		$roles = $user->roles->pluck('id');
		$permissions = $user->permissions->pluck('id');
		$user = $user->toArray();
		$user['roles'] = $roles;
		$user['permissions'] = $permissions;

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $user,
		);
	}

	/**
	 * Update Data
	 *
	 * @param UserRequest $request
	 * @param User        $user
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(UserRequest $request, User $user): JsonResponse
	{
		try {
			$this->authorize('update', $user);

			DB::beginTransaction();

			$user->update($request->safe()->except(['avatar', 'avatar_remove', 'permissions', 'roles']));

			// Sync Roles & Permission
			$user->syncRoles($request->safe()->array('roles'));
			$user->syncPermissions($request->safe()->array('permissions'));

			// Upload Image
			if ($request->hasFile('avatar')) {
				$user->addMedia($request->file('avatar'))->toMediaCollection('avatar');
			} else if ($request->safe()->boolean('avatar_remove')) {
				$user->clearMediaCollection('avatar');
			}

			// revoke semua token jika user dinonaktifkan
			if (!$user->active) {
				foreach ($user->tokens as $token) {
					$token->revoke();
				}
			}

			DB::commit();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $user,
			);
		} catch (Exception $e) {
			DB::rollBack();

			return $this->response(
				message: $e->getMessage(),
				status_code: $e->getCode(),
			);
		}
	}

	/**
	 * Delete Data
	 *
	 * @param User $user
	 *
	 * @return JsonResponse
	 */
	public function destroy(User $user): JsonResponse
	{
		$this->authorize('delete', $user);

		$user->delete();

		return $this->response(
			message: 'Berhasil menghapus data.',
		);
	}

	/**
	 * Bulk Delete Data
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	#[BodyParam("data", "object[]", "List of id", example: [['id' => 1]])]
	public function bulkDestroy(Request $request): JsonResponse
	{
		$this->authorize('bulkDelete', User::class);

		User::whereIn('id', $request->data)->withoutRole('super-admin')->delete();

		return $this->response(
			message: 'Berhasil menghapus data.',
		);
	}

	/**
	 * Export Data
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	#[QueryParam('type', enum: ['xlsx'])]
	public function export(Request $request): JsonResponse
	{
		$this->validate($request, [
			'type' => ['required', Rule::in(['xlsx'])],
		]);

		$file_name = date('YmdHis') . '-users-' . auth()->id() . '.' . $request->type;

		(new UserExport($this->query()))->store($file_name, 'download');

		return $this->response(
			message: 'Berhasil melakukan export data.',
			data: [
				'file_name' => $file_name,
				'url' => Storage::disk('download')->temporaryUrl($file_name, now()->addMinutes(30)),
			],
		);
	}

	/**
	 * Custom Query Get Data + Export
	 *
	 * @return QueryBuilder
	 */
	private function query(): QueryBuilder
	{
		return QueryBuilder::for(
			subject: User::class,
		)->allowedSorts(
			sorts: [
				'created_at',
				'name',
				'email',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('role', function (Builder $q, $value) {
					$q->role($value);
				}),
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$q->whereAny(['name', 'email'], 'LIKE', '%' . $value . '%');
				}),
				AllowedFilter::exact('active'),
			],
		)->allowedIncludes(
			includes: ['permissions', 'roles'],
		);
	}
}
