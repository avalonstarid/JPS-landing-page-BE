<?php

namespace App\Http\Controllers\V1\Investor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Investor\LaporanTahunanRequest;
use App\Models\Investor\LaporanTahunan;
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

#[Group("Relasi Investor", "API Endpoint for relasi investor.")]
#[Subgroup("Laporan Tahunan", "API endpoint for laporan tahunan.")]
class LaporanTahunanController extends Controller
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
		'created_at', 'name'])]
	public function index(Request $request): JsonResponse
	{
//		$this->authorize('viewAny', LaporanTahunan::class);

		$query = QueryBuilder::for(
			subject: LaporanTahunan::class,
		)->allowedSorts(
			sorts: [
				'created_at',
				'name',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$q->whereAny(['name'], 'LIKE', '%' . $value . '%');
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
	 * @param LaporanTahunanRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(LaporanTahunanRequest $request): JsonResponse
	{
		try {
			$this->authorize('create', LaporanTahunan::class);

			DB::beginTransaction();

			$data = LaporanTahunan::create($request->safe()->except(['document', 'featured']));

			if ($request->hasFile('document')) {
				$data->addMedia($request->file('document'))->toMediaCollection('document');
			}
			if ($request->hasFile('featured')) {
				$data->addMedia($request->file('featured'))->toMediaCollection('featured');
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
	 * @param LaporanTahunan $laporanTahunan
	 *
	 * @return JsonResponse
	 */
	public function show(LaporanTahunan $laporanTahunan): JsonResponse
	{
//		$this->authorize('view', $laporanTahunan);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $laporanTahunan->load([
				'document',
				'featured',
			]),
		);
	}

	/**
	 * Update Data
	 *
	 * @param LaporanTahunanRequest $request
	 * @param LaporanTahunan        $laporanTahunan
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(LaporanTahunanRequest $request, LaporanTahunan $laporanTahunan): JsonResponse
	{
		try {
			$this->authorize('update', $laporanTahunan);

			DB::beginTransaction();

			$laporanTahunan->update($request->safe()->except(['document', 'featured']));

			if ($request->hasFile('document')) {
				$laporanTahunan->addMedia($request->file('document'))->toMediaCollection('document');
			}
			if ($request->hasFile('featured')) {
				$laporanTahunan->addMedia($request->file('featured'))->toMediaCollection('featured');
			}

			DB::commit();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $laporanTahunan,
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
	 * @param LaporanTahunan $laporanTahunan
	 *
	 * @return JsonResponse
	 */
	public function destroy(LaporanTahunan $laporanTahunan): JsonResponse
	{
		$this->authorize('delete', $laporanTahunan);

		$laporanTahunan->delete();

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
		$this->authorize('bulkDelete', LaporanTahunan::class);

		$ids = collect($request->data)->pluck(value: 'id');
		foreach ($ids as $id) {
			$data = LaporanTahunan::where('id', $id)->firstOrFail();
			$data->delete();
		}

		return $this->response(
			message: 'Berhasil menghapus data.',
		);
	}
}
