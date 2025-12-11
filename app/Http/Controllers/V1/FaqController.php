<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\FaqRequest;
use App\Models\Faq;
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

#[Group("FAQ", "API Endpoint for faq.")]
class FaqController extends Controller
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
		'created_at', 'sort_order'])]
	public function index(Request $request): JsonResponse
	{
//		$this->authorize('viewAny', Faq::class);

		$query = QueryBuilder::for(
			subject: Faq::class,
		)->allowedSorts(
			sorts: [
				'created_at',
				'sort_order',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$q->whereAny(['answer', 'question'], 'LIKE', '%' . $value . '%');
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
	 * @param FaqRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(FaqRequest $request): JsonResponse
	{
		try {
			$this->authorize('create', Faq::class);

			DB::beginTransaction();

			$data = Faq::create($request->validated());

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
	 * @param Faq $faq
	 *
	 * @return JsonResponse
	 */
	public function show(Faq $faq): JsonResponse
	{
//		$this->authorize('view', $faq);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $faq,
		);
	}

	/**
	 * Update Data
	 *
	 * @param FaqRequest $request
	 * @param Faq        $faq
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(FaqRequest $request, Faq $faq): JsonResponse
	{
		try {
			$this->authorize('update', $faq);

			DB::beginTransaction();

			$faq->update($request->validated());

			DB::commit();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $faq,
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
	 * @param Faq $faq
	 *
	 * @return JsonResponse
	 */
	public function destroy(Faq $faq): JsonResponse
	{
		$this->authorize('delete', $faq);

		$faq->delete();

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
		$this->authorize('bulkDelete', Faq::class);

		$ids = collect($request->data)->pluck(value: 'id');
		foreach ($ids as $id) {
			$data = Faq::where('id', $id)->firstOrFail();
			$data->delete();
		}

		return $this->response(
			message: 'Berhasil menghapus data.',
		);
	}
}
