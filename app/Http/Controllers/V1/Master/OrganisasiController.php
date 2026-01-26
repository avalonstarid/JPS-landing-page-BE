<?php

namespace App\Http\Controllers\V1\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\OrganisasiRequest;
use App\Models\Master\Organisasi;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Subgroup;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

#[Group("Master", "API Endpoint for Master.")]
#[Subgroup("Organisasi", "API endpoint for organisasi.")]
class OrganisasiController extends Controller
{
	/**
	 * Get Data
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	#[QueryParam('filter[search]', required: false, example: '', enum: ['search'])]
	#[QueryParam('include', required: false, example: '', enum: ['parent'])]
	#[QueryParam('rows', 'int', required: false, example: 10)]
	#[QueryParam('sort', description: 'Tambah tanda minus (-) di depan untuk descending', required: false, example: '', enum: ['created_at',
		'name'])]
	public function index(Request $request): JsonResponse
	{
//		$this->authorize('viewAny', Organisasi::class);

		$query = QueryBuilder::for(
			subject: Organisasi::class,
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
						$subQuery->orWhereRaw('LOWER(desc) LIKE ?', [$searchTerm]);
						$subQuery->orWhereRaw('LOWER(name) LIKE ?', [$searchTerm]);
					});
				}),
				AllowedFilter::callback('parent_id', function (Builder $q, $value) {
					if ($value == 'null') {
						$q->whereNull('parent_id');
					} else {
						$q->where('parent_id', $value);
					}
				}),
			],
		)->allowedIncludes(
			includes: [
				'parent',
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
	 * @param OrganisasiRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(OrganisasiRequest $request): JsonResponse
	{
		try {
			$this->authorize('create', Organisasi::class);

			DB::beginTransaction();

			$data = Organisasi::create($request->validated());

			DB::commit();

			$this->clearCache();

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
	 * @param Organisasi $organisasi
	 *
	 * @return JsonResponse
	 */
	public function show(Organisasi $organisasi): JsonResponse
	{
//		$this->authorize('view', $organisasi);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $organisasi,
		);
	}

	/**
	 * Update Data
	 *
	 * @param OrganisasiRequest $request
	 * @param Organisasi        $organisasi
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(OrganisasiRequest $request, Organisasi $organisasi): JsonResponse
	{
		try {
			$this->authorize('update', $organisasi);

			DB::beginTransaction();

			$organisasi->update($request->validated());

			DB::commit();

			$this->clearCache();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $organisasi,
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
	 * @param Organisasi $organisasi
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function destroy(Organisasi $organisasi): JsonResponse
	{
		try {
			$this->authorize('delete', $organisasi);

			DB::beginTransaction();

			$organisasi->delete();

			DB::commit();

			$this->clearCache();

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
			$this->authorize('bulkDelete', Organisasi::class);

			DB::beginTransaction();

			$ids = collect($request->data)->pluck(value: 'id');
			foreach ($ids as $id) {
				$data = Organisasi::where('id', $id)->firstOrFail();
				$data->delete();
			}

			DB::commit();

			$this->clearCache();

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

	/**
	 * Clear Cache
	 *
	 * @return void
	 */
	private function clearCache()
	{
		Cache::forget('landing:tentangPerusahaan');
	}
}
