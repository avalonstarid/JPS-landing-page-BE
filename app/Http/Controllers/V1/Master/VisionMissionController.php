<?php

namespace App\Http\Controllers\V1\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\VisionMissionRequest;
use App\Models\Master\VisionMission;
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
#[Subgroup("Visi dan Misi", "API endpoint for visi dan misi perusahaan.")]
class VisionMissionController extends Controller
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
//		$this->authorize('viewAny', VisionMission::class);

		$query = QueryBuilder::for(
			subject: VisionMission::class,
		)->allowedSorts(
			sorts: [
				'created_at',
				'sort_order',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$q->whereAny(['desc'], 'LIKE', '%' . $value . '%');
				}),
				AllowedFilter::exact('active'),
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
	 * @param VisionMissionRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(VisionMissionRequest $request): JsonResponse
	{
		try {
			$this->authorize('create', VisionMission::class);

			DB::beginTransaction();

			$data = VisionMission::create($request->validated());

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
	 * @param VisionMission $visionMission
	 *
	 * @return JsonResponse
	 */
	public function show(VisionMission $visionMission): JsonResponse
	{
//		$this->authorize('view', $visionMission);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $visionMission,
		);
	}

	/**
	 * Update Data
	 *
	 * @param VisionMissionRequest $request
	 * @param VisionMission        $visionMission
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(VisionMissionRequest $request, VisionMission $visionMission): JsonResponse
	{
		try {
			$this->authorize('update', $visionMission);

			DB::beginTransaction();

			$visionMission->update($request->validated());

			DB::commit();

			$this->clearCache();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $visionMission,
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
	 * @param VisionMission $visionMission
	 *
	 * @return JsonResponse
	 */
	public function destroy(VisionMission $visionMission): JsonResponse
	{
		$this->authorize('delete', $visionMission);

		$visionMission->delete();

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
		$this->authorize('bulkDelete', VisionMission::class);

		$ids = collect($request->data)->pluck(value: 'id');
		foreach ($ids as $id) {
			$data = VisionMission::where('id', $id)->firstOrFail();
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
