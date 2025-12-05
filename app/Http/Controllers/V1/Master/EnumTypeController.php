<?php

namespace App\Http\Controllers\V1\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\EnumTypeRequest;
use App\Models\Master\EnumType;
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
class EnumTypeController extends Controller
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
	#[QueryParam('sort', description: 'Tambah tanda minus (-) di depan untuk descending', required: false, example: '', enum: ['code',
		'created_at', 'name'])]
	public function index(Request $request): JsonResponse
	{
//		$this->authorize('viewAny', EnumType::class);

		$query = QueryBuilder::for(
			subject: EnumType::class,
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
	 * @param EnumTypeRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(EnumTypeRequest $request): JsonResponse
	{
		try {
			$this->authorize('create', EnumType::class);

			DB::beginTransaction();

			$data = EnumType::create($request->validated());

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
	 * @param EnumType $enumType
	 *
	 * @return JsonResponse
	 */
	public function show(EnumType $enumType): JsonResponse
	{
//		$this->authorize('view', $enumType);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $enumType,
		);
	}

	/**
	 * Update Data
	 *
	 * @param EnumTypeRequest $request
	 * @param EnumType        $enumType
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(EnumTypeRequest $request, EnumType $enumType): JsonResponse
	{
		try {
			$this->authorize('update', $enumType);

			DB::beginTransaction();

			$enumType->update($request->validated());

			DB::commit();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $enumType,
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
	 * @param EnumType $enumType
	 *
	 * @return JsonResponse
	 */
	public function destroy(EnumType $enumType): JsonResponse
	{
		$this->authorize('delete', $enumType);

		$enumType->delete();

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
		$this->authorize('bulkDelete', EnumType::class);

		EnumType::whereIn('id', $request->data)->delete();

		return $this->response(
			message: 'Berhasil menghapus data.',
		);
	}
}
