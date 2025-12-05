<?php

namespace App\Http\Controllers\V1\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\CategoryRequest;
use App\Models\Master\Category;
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

#[Group("Master", "API Endpoint for Master.")]
#[Subgroup("Category", "API endpoint for category.")]
class CategoryController extends Controller
{
	/**
	 * Get Data
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	#[QueryParam('filter[search]', required: false, example: '', enum: ['search', 'is_public', 'parent_id', 'type'])]
	#[QueryParam('rows', 'int', required: false, example: 10)]
	#[QueryParam('sort', description: 'Tambah tanda minus (-) di depan untuk descending', required: false, example: '', enum: ['name'])]
	public function index(Request $request): JsonResponse
	{
//		$this->authorize('viewAny', Category::class);

		$query = QueryBuilder::for(
			subject: Category::class,
		)->allowedSorts(
			sorts: [
				'name',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('parent_null', function (Builder $q, $value) {
					if ($value == 1) {
						$q->whereNull('parent_id');
					} else if ($value == 0) {
						$q->whereNotNull('parent_id');
					}
				}),
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$q->whereAny(['name'], 'LIKE', '%' . $value . '%');
				}),
				AllowedFilter::exact('is_public'),
				AllowedFilter::exact('parent_id'),
				AllowedFilter::exact('type'),
			],
		)->allowedIncludes(
			includes: ['children', 'parent'],
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
	 * @param CategoryRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(CategoryRequest $request): JsonResponse
	{
		try {
//			$this->authorize('create', Category::class);

			DB::beginTransaction();

			$data = Category::create($request->validated());

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
	 * @param Category $category
	 *
	 * @return JsonResponse
	 */
	public function show(Category $category): JsonResponse
	{
//		$this->authorize('view', $category);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $category,
		);
	}

	/**
	 * Update Data
	 *
	 * @param CategoryRequest $request
	 * @param Category        $category
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(CategoryRequest $request, Category $category): JsonResponse
	{
		try {
			$this->authorize('update', $category);

			DB::beginTransaction();

			$category->update($request->validated());

			DB::commit();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $category,
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
	 * @param Category $category
	 *
	 * @return JsonResponse
	 */
	public function destroy(Category $category): JsonResponse
	{
		$this->authorize('delete', $category);

		$category->delete();

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
		$this->authorize('bulkDelete', Category::class);

		$ids = collect($request->data)->pluck(value: 'id');

		Category::whereIn('id', $ids)->delete();

		return $this->response(
			message: 'Berhasil menghapus data.',
		);
	}
}
