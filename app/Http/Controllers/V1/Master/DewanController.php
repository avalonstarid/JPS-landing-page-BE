<?php

namespace App\Http\Controllers\V1\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\DewanRequest;
use App\Models\Master\Dewan;
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
#[Subgroup("Dewan", "API endpoint for dewan.")]
class DewanController extends Controller
{
	/**
	 * Get Data
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	#[QueryParam('filter[search]', required: false, example: '', enum: ['search', 'organisasi_id'])]
	#[QueryParam('include', required: false, example: '', enum: ['avatar', 'organisasi'])]
	#[QueryParam('rows', 'int', required: false, example: 10)]
	#[QueryParam('sort', description: 'Tambah tanda minus (-) di depan untuk descending', required: false, example: '', enum: ['created_at',
		'name'])]
	public function index(Request $request): JsonResponse
	{
//		$this->authorize('viewAny', Dewan::class);

		$query = QueryBuilder::for(
			subject: Dewan::class,
		)->allowedSorts(
			sorts: [
				'created_at',
				'name',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$searchTerm = '%' . strtolower($value) . '%';

					$q->where(function ($subQuery) use ($searchTerm) {
						$subQuery->orWhereRaw('LOWER(jabatan) LIKE ?', [$searchTerm]);
						$subQuery->orWhereRaw('LOWER(name) LIKE ?', [$searchTerm]);
					});
				}),
				AllowedFilter::exact('organisasi_id'),
			],
		)->allowedIncludes(
			includes: [
				'avatar', 'organisasi',
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
	 * @param DewanRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(DewanRequest $request): JsonResponse
	{
		try {
			$this->authorize('create', Dewan::class);

			DB::beginTransaction();

			$data = Dewan::create($request->safe()->except([
				'avatar', 'avatar_remove',
			]));

			if ($request->hasFile('avatar')) {
				$data->addMedia($request->file('avatar'))->toMediaCollection('avatar');
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
	 * @param Dewan $dewan
	 *
	 * @return JsonResponse
	 */
	public function show(Dewan $dewan): JsonResponse
	{
//		$this->authorize('view', $dewan);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $dewan->load([
				'avatar',
			]),
		);
	}

	/**
	 * Update Data
	 *
	 * @param DewanRequest $request
	 * @param Dewan        $dewan
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(DewanRequest $request, Dewan $dewan): JsonResponse
	{
		try {
			$this->authorize('update', $dewan);

			DB::beginTransaction();

			$dewan->update($request->safe()->except(['avatar', 'avatar_remove']));

			// Set Avatar Image
			if ($request->hasFile('avatar')) {
				$dewan->addMedia($request->file('avatar'))->toMediaCollection('avatar');
			} else if ($request->safe()->boolean('avatar_remove')) {
				$dewan->clearMediaCollection('avatar');
			}

			DB::commit();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $dewan,
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
	 * @param Dewan $dewan
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function destroy(Dewan $dewan): JsonResponse
	{
		try {
			$this->authorize('delete', $dewan);

			DB::beginTransaction();

			$dewan->delete();

			DB::commit();

			return $this->response(
				message: 'Berhasil menghapus data.',
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
	 * Bulk Delete Data
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	#[BodyParam("data", "object[]", "List of id", example: [['id' => 1]])]
	public function bulkDestroy(Request $request): JsonResponse
	{
		try {
			$this->authorize('bulkDelete', Dewan::class);

			DB::beginTransaction();

			$ids = collect($request->data)->pluck(value: 'id');
			foreach ($ids as $id) {
				$data = Dewan::where('id', $id)->firstOrFail();
				$data->delete();
			}

			DB::commit();

			return $this->response(
				message: 'Data berhasil dihapus.',
			);
		} catch (Exception $e) {
			DB::rollBack();

			return $this->response(
				message: 'Data gagal dihapus. ' . $e->getMessage(),
				status_code: 500,
			);
		}
	}
}
