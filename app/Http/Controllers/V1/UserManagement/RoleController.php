<?php

namespace App\Http\Controllers\V1\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserManagement\RoleRequest;
use App\Models\UserManagement\Role;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Subgroup;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

#[Group("User Management", "API Endpoint for User Management.")]
#[Subgroup("Roles", "API endpoint for roles management.")]
class RoleController extends Controller
{
	/**
	 * Get Data
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	#[QueryParam('filter[search]', required: false, example: '', enum: ['search'])]
	#[QueryParam('include', required: false, example: '', enum: ['permissions', 'users'])]
	#[QueryParam('rows', 'int', required: false, example: 10)]
	#[QueryParam('sort', required: false, example: '', enum: ['name'])]
	public function index(Request $request): JsonResponse
	{
//		$this->authorize('viewAny', Role::class);

		$query = QueryBuilder::for(
			subject: Role::class,
		)->allowedSorts(
			sorts: [
				'created_at',
				'guard_name',
				'name',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$q->whereAny(['name'], 'LIKE', '%' . $value . '%');
				}),
			],
		)->allowedIncludes(
			includes: ['permissions', 'users'],
		);

		if ($request->input('all', '') == 1) {
			return $this->response(
				message: 'Berhasil mengambil data.',
				data: $query->get(),
			);
		} else {
			$request->merge([
				'page' => $request->input('page', 1),
			]);

			$data = $query->fastPaginate(perPage: $request->input('rows', 10))->withQueryString();

			return response()->json(array_merge([
				'success' => true,
				'message' => 'Berhasil mengambil data.',
			], $data->toArray()));
		}
	}

	/**
	 * Insert Data
	 *
	 * @param RoleRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(RoleRequest $request): JsonResponse
	{
		try {
			$this->authorize('create', Role::class);

			DB::beginTransaction();

			$data = Role::create($request->safe()->except(['permissions']));
			$data->syncPermissions($request->safe()->array('permissions'));

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
	 * @param Role $role
	 *
	 * @return JsonResponse
	 */
	public function show(Role $role): JsonResponse
	{
//		$this->authorize('view', $role);

		$permissions = $role->permissions->pluck('id');
		$role = $role->toArray();
		$role['permissions'] = $permissions;

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $role,
		);
	}

	/**
	 * Update Data
	 *
	 * @param RoleRequest $request
	 * @param Role        $role
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(RoleRequest $request, Role $role): JsonResponse
	{
		try {
			$this->authorize('update', $role);

			DB::beginTransaction();

			$role->update($request->safe()->except(['permissions']));
			$role->syncPermissions($request->safe()->array('permissions'));

			DB::commit();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $role,
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
	 * @param Role $role
	 *
	 * @return JsonResponse
	 */
	public function destroy(Role $role): JsonResponse
	{
		$this->authorize('delete', $role);

		$role->delete();

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
		$this->authorize('bulkDelete', Role::class);

		Role::whereIn('id', $request->data)->delete();

		return $this->response(
			message: 'Berhasil menghapus data.',
		);
	}
}
