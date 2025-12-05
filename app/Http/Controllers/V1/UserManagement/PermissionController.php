<?php

namespace App\Http\Controllers\V1\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserManagement\PermissionRequest;
use App\Models\UserManagement\Permission;
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
#[Subgroup("Permissions", "API endpoint for permissions management.")]
class PermissionController extends Controller
{
	/**
	 * Get Data
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	#[QueryParam('filter[search]', required: false, example: '', enum: ['search'])]
	#[QueryParam('rows', 'int', required: false, example: 10)]
	#[QueryParam('sort', description: 'Tambah tanda minus (-) di depan untuk descending', required: false, example: '', enum: ['name'])]
	public function index(Request $request): JsonResponse
	{
//		$this->authorize('viewAny', Permission::class);

		$query = QueryBuilder::for(
			subject: Permission::class,
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
		)->defaultSorts(
			sorts: 'name',
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
				'errors' => null,
			], $data->toArray()));
		}
	}

	/**
	 * Insert Data
	 *
	 * @param PermissionRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(PermissionRequest $request): JsonResponse
	{
		try {
			$this->authorize('create', Permission::class);

			DB::beginTransaction();

			$data = Permission::create($request->validated());

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
	 * @param Permission $permission
	 *
	 * @return JsonResponse
	 */
	public function show(Permission $permission): JsonResponse
	{
//		$this->authorize('view', $permission);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $permission,
		);
	}

	/**
	 * Update Data
	 *
	 * @param PermissionRequest $request
	 * @param Permission        $permission
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(PermissionRequest $request, Permission $permission): JsonResponse
	{
		try {
			$this->authorize('update', $permission);

			DB::beginTransaction();

			$permission->update($request->validated());

			DB::commit();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $permission,
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
	 * @param Permission $permission
	 *
	 * @return JsonResponse
	 */
	public function destroy(Permission $permission): JsonResponse
	{
		$this->authorize('delete', $permission);

		$permission->delete();

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
		$this->authorize('bulkDelete', Permission::class);

		Permission::whereIn('id', $request->data)->delete();

		return $this->response(
			message: 'Berhasil menghapus data.',
		);
	}
}
