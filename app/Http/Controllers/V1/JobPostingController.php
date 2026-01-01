<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobPostingRequest;
use App\Models\JobPosting;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

#[Group("Job Posting", "API Endpoint for job posting.")]
class JobPostingController extends Controller
{
	/**
	 * Get Data
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	#[QueryParam('filter[search]', required: false, example: '', enum: ['search'])]
	#[QueryParam('include', required: false, example: '', enum: ['category'])]
	#[QueryParam('rows', 'int', required: false, example: 10)]
	#[QueryParam('sort', description: 'Tambah tanda minus (-) di depan untuk descending', required: false, example: '', enum: [
		'closed_at', 'created_at', 'published_at'])]
	public function index(Request $request): JsonResponse
	{
//		$this->authorize('viewAny', JobPosting::class);

		$query = QueryBuilder::for(
			subject: JobPosting::select(['id', 'address', 'closed_at', 'location', 'published_at', 'slug', 'title',
				'created_at', 'updated_at', 'category_id', 'created_by_id', 'updated_by_id']),
		)->allowedSorts(
			sorts: [
				'closed_at',
				'created_at',
				'published_at',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$q->whereAny(['title'], 'LIKE', '%' . $value . '%');
				}),
			],
		)->allowedIncludes(
			includes: ['category'],
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
	 * @param JobPostingRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(JobPostingRequest $request): JsonResponse
	{
		try {
			$this->authorize('create', JobPosting::class);

			DB::beginTransaction();

			$data = JobPosting::create($request->validated());

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
	 * @param JobPosting $jobPosting
	 *
	 * @return JsonResponse
	 */
	public function show(JobPosting $jobPosting): JsonResponse
	{
//		$this->authorize('view', $jobPosting);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $jobPosting,
		);
	}

	/**
	 * Update Data
	 *
	 * @param JobPostingRequest $request
	 * @param JobPosting        $jobPosting
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(JobPostingRequest $request, JobPosting $jobPosting): JsonResponse
	{
		try {
			$this->authorize('update', $jobPosting);

			DB::beginTransaction();

			$jobPosting->update($request->validated());

			DB::commit();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $jobPosting,
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
	 * @param JobPosting $jobPosting
	 *
	 * @return JsonResponse
	 */
	public function destroy(JobPosting $jobPosting): JsonResponse
	{
		$this->authorize('delete', $jobPosting);

		$jobPosting->delete();

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
		$this->authorize('bulkDelete', JobPosting::class);

		$ids = collect($request->data)->pluck(value: 'id');
		foreach ($ids as $id) {
			$data = JobPosting::where('id', $id)->firstOrFail();
			$data->delete();
		}

		return $this->response(
			message: 'Berhasil menghapus data.',
		);
	}
}
