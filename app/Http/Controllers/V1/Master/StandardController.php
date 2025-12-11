<?php

namespace App\Http\Controllers\V1\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StandardRequest;
use App\Models\Master\Standard;
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
#[Subgroup("Enum Type", "API endpoint for enum type.")]
class StandardController extends Controller
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
	#[QueryParam('sort', description: 'Tambah tanda minus (-) di depan untuk descending', required: false, example: '', enum: [
		'created_at', 'sort_order'])]
	public function index(Request $request): JsonResponse
	{
//		$this->authorize('viewAny', Standard::class);

		$query = QueryBuilder::for(
			subject: Standard::class,
		)->allowedSorts(
			sorts: [
				'created_at',
				'sort_order',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$q->whereAny(['desc', 'title'], 'LIKE', '%' . $value . '%');
				}),
			],
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
	 * @param StandardRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(StandardRequest $request): JsonResponse
	{
		try {
			$this->authorize('create', Standard::class);

			DB::beginTransaction();

			$data = Standard::create($request->validated());

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
	 * @param Standard $standard
	 *
	 * @return JsonResponse
	 */
	public function show(Standard $standard): JsonResponse
	{
//		$this->authorize('view', $standard);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $standard,
		);
	}

	/**
	 * Update Data
	 *
	 * @param StandardRequest $request
	 * @param Standard        $standard
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(StandardRequest $request, Standard $standard): JsonResponse
	{
		try {
			$this->authorize('update', $standard);

			DB::beginTransaction();

			$standard->update($request->validated());

			DB::commit();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $standard,
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
	 * @param Standard $standard
	 *
	 * @return JsonResponse
	 */
	public function destroy(Standard $standard): JsonResponse
	{
		$this->authorize('delete', $standard);

		$standard->delete();

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
		$this->authorize('bulkDelete', Standard::class);

		Standard::whereIn('id', $request->data)->delete();

		return $this->response(
			message: 'Berhasil menghapus data.',
		);
	}
}
