<?php

namespace App\Http\Controllers\V1\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\EnumRequest;
use App\Models\Master\Enums;
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
#[Subgroup("Enum", "API endpoint for enum.")]
class EnumController extends Controller
{
	/**
	 * Get Data
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	#[QueryParam('filter[search]', required: false, example: '', enum: ['search', 'type_id'])]
	#[QueryParam('include', required: false, example: '', enum: ['accounts', 'type'])]
	#[QueryParam('rows', 'int', required: false, example: 10)]
	#[QueryParam('sort', description: 'Tambah tanda minus (-) di depan untuk descending', required: false, example: '', enum: ['code',
		'created_at', 'name'])]
	public function index(Request $request): JsonResponse
	{
//		$this->authorize('viewAny', Enums::class);

		$query = QueryBuilder::for(
			subject: Enums::class,
		)->allowedSorts(
			sorts: [
				'code',
				'created_at',
				'name',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$q->whereAny(['code', 'desc', 'name'], 'LIKE', '%' . $value . '%');
				}),
				AllowedFilter::exact('code'),
				AllowedFilter::exact('type_code', 'type.code'),
				AllowedFilter::exact('type_id'),
			],
		)->allowedIncludes(
			includes: ['accounts', 'type'],
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
	 * @param EnumRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(EnumRequest $request): JsonResponse
	{
		try {
			$this->authorize('create', Enums::class);

			DB::beginTransaction();

			$data = Enums::create($request->validated());

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
	 * @param Enums $enum
	 *
	 * @return JsonResponse
	 */
	public function show(Enums $enum): JsonResponse
	{
//		$this->authorize('view', $enum);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $enum,
		);
	}

	/**
	 * Update Data
	 *
	 * @param EnumRequest $request
	 * @param Enums       $enum
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(EnumRequest $request, Enums $enum): JsonResponse
	{
		try {
			$this->authorize('update', $enum);

			DB::beginTransaction();

			$enum->update($request->validated());

			DB::commit();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $enum,
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
	 * @param Enums $enum
	 *
	 * @return JsonResponse
	 */
	public function destroy(Enums $enum): JsonResponse
	{
		$this->authorize('delete', $enum);

		$enum->delete();

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
		$this->authorize('bulkDelete', Enums::class);

		Enums::whereIn('id', $request->data)->delete();

		return $this->response(
			message: 'Berhasil menghapus data.',
		);
	}
}
