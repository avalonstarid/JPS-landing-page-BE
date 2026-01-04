<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessLineRequest;
use App\Models\BusinessLine;
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

#[Group("Settings", "API Endpoint for Settings.")]
#[Subgroup("Lini Bisnis", "API endpoint for lini bisnis.")]
class BusinessLineController extends Controller
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
	#[QueryParam('sort', description: 'Tambah tanda minus (-) di depan untuk descending', required: false, example: '', enum: ['created_at',
		'sort_order', 'title'])]
	public function index(Request $request): JsonResponse
	{
//		$this->authorize('viewAny', BusinessLine::class);

		$query = QueryBuilder::for(
			subject: BusinessLine::class,
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
		)->allowedIncludes(
			includes: [
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
	 * @param BusinessLineRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(BusinessLineRequest $request): JsonResponse
	{
		try {
			$this->authorize('create', BusinessLine::class);

			DB::beginTransaction();

			$data = BusinessLine::create($request->safe()->except([
				'featured', 'featured_remove', 'images', 'images_remove',
			]));

			if ($request->hasFile('featured')) {
				$data->addMedia($request->file('featured'))->toMediaCollection('featured');
			}
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
	 * @param BusinessLine $businessLine
	 *
	 * @return JsonResponse
	 */
	public function show(BusinessLine $businessLine): JsonResponse
	{
//		$this->authorize('view', $businessLine);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $businessLine->load([
				'featured',
				'images',
			]),
		);
	}

	/**
	 * Update Data
	 *
	 * @param BusinessLineRequest $request
	 * @param BusinessLine        $businessLine
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(BusinessLineRequest $request, BusinessLine $businessLine): JsonResponse
	{
		try {
			$this->authorize('update', $businessLine);

			DB::beginTransaction();

			$businessLine->update($request->safe()->except(['featured', 'featured_remove', 'images', 'images_remove']));

			// Set Featured Image
			if ($request->hasFile('featured')) {
				$businessLine->addMedia($request->file('featured'))->toMediaCollection('featured');
			} else if ($request->safe()->boolean('featured_remove')) {
				$businessLine->clearMediaCollection('featured');
			}

			// Set Images
			if (!empty($request->safe()->array('images_remove'))) {
				$businessLine->media()
					->whereIn('id', $request->safe()->array('images_remove'))
					->get()
					->each->delete();
			}
			if ($request->hasFile('images')) {
				$businessLine->addMultipleMediaFromRequest(['images'])
					->each(function ($fileAdder) {
						$fileAdder->toMediaCollection('images');
					});
			}

			DB::commit();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $businessLine,
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
	 * @param BusinessLine $businessLine
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function destroy(BusinessLine $businessLine): JsonResponse
	{
		try {
			$this->authorize('delete', $businessLine);

			DB::beginTransaction();

			$businessLine->delete();

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
			$this->authorize('bulkDelete', BusinessLine::class);

			DB::beginTransaction();

			$ids = collect($request->data)->pluck(value: 'id');
			foreach ($ids as $id) {
				$data = BusinessLine::where('id', $id)->firstOrFail();
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
