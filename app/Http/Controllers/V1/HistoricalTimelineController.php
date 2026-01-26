<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\HistoricalTimelineRequest;
use App\Models\HistoricalTimeline;
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

#[Group("Settings", "API Endpoint for Settings.")]
#[Subgroup("Linimasa Sejarah", "API endpoint for linimasa sejarah perusahaan.")]
class HistoricalTimelineController extends Controller
{
	/**
	 * Get Data
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	#[QueryParam('filter[search]', required: false, example: '', enum: ['search', 'active'])]
	#[QueryParam('rows', 'int', required: false, example: 10)]
	#[QueryParam('sort', description: 'Tambah tanda minus (-) di depan untuk descending', required: false, example: '', enum: [
		'created_at', 'sort_order'])]
	public function index(Request $request): JsonResponse
	{
//		$this->authorize('viewAny', HistoricalTimeline::class);

		$query = QueryBuilder::for(
			subject: HistoricalTimeline::class,
		)->allowedSorts(
			sorts: [
				'created_at',
				'year',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$q->whereAny(['desc'], 'LIKE', '%' . $value . '%');
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
	 * @param HistoricalTimelineRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(HistoricalTimelineRequest $request): JsonResponse
	{
		try {
			$this->authorize('create', HistoricalTimeline::class);

			DB::beginTransaction();

			$data = HistoricalTimeline::create($request->validated());

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
	 * @param HistoricalTimeline $historicalTimeline
	 *
	 * @return JsonResponse
	 */
	public function show(HistoricalTimeline $historicalTimeline): JsonResponse
	{
//		$this->authorize('view', $historicalTimeline);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $historicalTimeline,
		);
	}

	/**
	 * Update Data
	 *
	 * @param HistoricalTimelineRequest $request
	 * @param HistoricalTimeline        $historicalTimeline
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(HistoricalTimelineRequest $request, HistoricalTimeline $historicalTimeline): JsonResponse
	{
		try {
			$this->authorize('update', $historicalTimeline);

			DB::beginTransaction();

			$historicalTimeline->update($request->validated());

			DB::commit();

			$this->clearCache();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $historicalTimeline,
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
	 * @param HistoricalTimeline $historicalTimeline
	 *
	 * @return JsonResponse
	 */
	public function destroy(HistoricalTimeline $historicalTimeline): JsonResponse
	{
		$this->authorize('delete', $historicalTimeline);

		$historicalTimeline->delete();

		$this->clearCache();

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
		$this->authorize('bulkDelete', HistoricalTimeline::class);

		$ids = collect($request->data)->pluck(value: 'id');
		foreach ($ids as $id) {
			$data = HistoricalTimeline::where('id', $id)->firstOrFail();
			$data->delete();
		}

		$this->clearCache();

		return $this->response(
			message: 'Berhasil menghapus data.',
		);
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
