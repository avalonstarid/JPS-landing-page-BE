<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TestimonialRequest;
use App\Models\Testimonial;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

#[Group("Testimonial", "API Endpoint for testimonial.")]
class TestimonialController extends Controller
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
	#[QueryParam('sort', description: 'Tambah tanda minus (-) di depan untuk descending', required: false, example: '', enum: ['created_at'])]
	public function index(Request $request): JsonResponse
	{
//		$this->authorize('viewAny', Testimonial::class);

		$query = QueryBuilder::for(
			subject: Testimonial::class,
		)->allowedSorts(
			sorts: [
				'created_at',
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
	 * @param TestimonialRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(TestimonialRequest $request): JsonResponse
	{
		try {
			$this->authorize('create', Testimonial::class);

			DB::beginTransaction();

			$data = Testimonial::create($request->validated());

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
	 * @param Testimonial $testimonial
	 *
	 * @return JsonResponse
	 */
	public function show(Testimonial $testimonial): JsonResponse
	{
//		$this->authorize('view', $testimonial);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $testimonial,
		);
	}

	/**
	 * Update Data
	 *
	 * @param TestimonialRequest $request
	 * @param Testimonial        $testimonial
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(TestimonialRequest $request, Testimonial $testimonial): JsonResponse
	{
		try {
			$this->authorize('update', $testimonial);

			DB::beginTransaction();

			$testimonial->update($request->validated());

			DB::commit();

			$this->clearCache();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $testimonial,
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
	 * @param Testimonial $testimonial
	 *
	 * @return JsonResponse
	 */
	public function destroy(Testimonial $testimonial): JsonResponse
	{
		$this->authorize('delete', $testimonial);

		$testimonial->delete();

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
		$this->authorize('bulkDelete', Testimonial::class);

		$ids = collect($request->data)->pluck(value: 'id');
		foreach ($ids as $id) {
			$data = Testimonial::where('id', $id)->firstOrFail();
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
		Cache::forget('landing:index');
	}
}
