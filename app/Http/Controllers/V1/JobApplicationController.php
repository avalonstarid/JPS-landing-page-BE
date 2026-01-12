<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobApplicationRequest;
use App\Models\JobApplication;
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

#[Group("Job Application", "API Endpoint for job application.")]
class JobApplicationController extends Controller
{
	/**
	 * Get Data
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	#[QueryParam('filter[search]', required: false, example: '', enum: ['search'])]
	#[QueryParam('include', required: false, example: '', enum: ['gender', 'jobPosting', 'resume', 'statusKawin'])]
	#[QueryParam('rows', 'int', required: false, example: 10)]
	#[QueryParam('sort', description: 'Tambah tanda minus (-) di depan untuk descending', required: false, example: '', enum: [
		'created_at'])]
	public function index(Request $request): JsonResponse
	{
//		$this->authorize('viewAny', JobApplication::class);

		$query = QueryBuilder::for(
			subject: JobApplication::class,
		)->allowedSorts(
			sorts: [
				'created_at',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$q->whereAny(['email', 'name'], 'LIKE', '%' . $value . '%');
				}),
			],
		)->allowedIncludes(
			includes: ['gender', 'jobPosting', 'resume', 'statusKawin'],
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
	 * @param JobApplicationRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(JobApplicationRequest $request): JsonResponse
	{
		try {
			$this->authorize('create', JobApplication::class);

			DB::beginTransaction();

			$data = JobApplication::create($request->safe()->except(['resume']));

			$data->addMedia($request->file('resume'))->toMediaCollection('resume');

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
	 * @param JobApplication $jobApplication
	 *
	 * @return JsonResponse
	 */
	public function show(JobApplication $jobApplication): JsonResponse
	{
//		$this->authorize('view', $jobApplication);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $jobApplication,
		);
	}

	/**
	 * Update Data
	 *
	 * @param JobApplicationRequest $request
	 * @param JobApplication        $jobApplication
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(JobApplicationRequest $request, JobApplication $jobApplication): JsonResponse
	{
		try {
			$this->authorize('update', $jobApplication);

			DB::beginTransaction();

			$jobApplication->update($request->validated());

			DB::commit();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $jobApplication,
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
	 * @param JobApplication $jobApplication
	 *
	 * @return JsonResponse
	 */
	public function destroy(JobApplication $jobApplication): JsonResponse
	{
		$this->authorize('delete', $jobApplication);

		$jobApplication->delete();

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
		$this->authorize('bulkDelete', JobApplication::class);

		$ids = collect($request->data)->pluck(value: 'id');
		foreach ($ids as $id) {
			$data = JobApplication::where('id', $id)->firstOrFail();
			$data->delete();
		}

		return $this->response(
			message: 'Berhasil menghapus data.',
		);
	}
}
