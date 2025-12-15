<?php

namespace App\Http\Controllers\V1\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\LocationRequest;
use App\Models\Master\Location;
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
#[Subgroup("Location", "API endpoint for lokasi.")]
class LocationController extends Controller
{
	/**
	 * Get Data
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	#[QueryParam('filter[search]', required: false, example: '', enum: ['search'])]
	#[QueryParam('include', required: false, example: '', enum: ['businessLine', 'media'])]
	#[QueryParam('rows', 'int', required: false, example: 10)]
	#[QueryParam('sort', description: 'Tambah tanda minus (-) di depan untuk descending', required: false, example: '', enum: ['created_at'])]
	public function index(Request $request): JsonResponse
	{
//		$this->authorize('viewAny', Location::class);

		$query = QueryBuilder::for(
			subject: Location::class,
		)->allowedSorts(
			sorts: [
				'created_at',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$q->whereAny(['address', 'desc', 'phone'], 'LIKE', '%' . $value . '%');
				}),
			],
		)->allowedIncludes(
			includes: [
				'businessLine',
				'media',
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
	 * @param LocationRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(LocationRequest $request): JsonResponse
	{
		try {
			$this->authorize('create', Location::class);

			DB::beginTransaction();

			$data = Location::create($request->safe()->except(['images', 'images_remove',]));

			if ($request->hasFile('images')) {
				$data->addMultipleMediaFromRequest(['images'])
					->each(function ($fileAdder) {
						$fileAdder->toMediaCollection('images');
					});
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
	 * @param Location $location
	 *
	 * @return JsonResponse
	 */
	public function show(Location $location): JsonResponse
	{
//		$this->authorize('view', $location);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $location->load([
				'images',
			]),
		);
	}

	/**
	 * Update Data
	 *
	 * @param LocationRequest $request
	 * @param Location        $location
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(LocationRequest $request, Location $location): JsonResponse
	{
		try {
			$this->authorize('update', $location);

			DB::beginTransaction();

			$location->update($request->safe()->except(['images', 'images_remove']));

			// Set Images
			if (!empty($request->safe()->array('images_remove'))) {
				$location->media()
					->whereIn('id', $request->safe()->array('images_remove'))
					->get()
					->each->delete();
			}
			if ($request->hasFile('images')) {
				$location->addMultipleMediaFromRequest(['images'])
					->each(function ($fileAdder) {
						$fileAdder->toMediaCollection('images');
					});
			}

			DB::commit();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $location,
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
	 * @param Location $location
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function destroy(Location $location): JsonResponse
	{
		try {
			$this->authorize('delete', $location);

			DB::beginTransaction();

			$location->delete();

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
			$this->authorize('bulkDelete', Location::class);

			DB::beginTransaction();

			$ids = collect($request->data)->pluck(value: 'id');
			foreach ($ids as $id) {
				$data = Location::where('id', $id)->firstOrFail();
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
