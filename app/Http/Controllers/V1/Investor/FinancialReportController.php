<?php

namespace App\Http\Controllers\V1\Investor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Investor\FinancialReportRequest;
use App\Models\Investor\FinancialReport;
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
#[Subgroup("Laporan Keuangan", "API endpoint for laporan keuangan.")]
class FinancialReportController extends Controller
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
//		$this->authorize('viewAny', FinancialReport::class);

		$query = QueryBuilder::for(
			subject: FinancialReport::class,
		)->allowedSorts(
			sorts: [
				'created_at',
				'tahun',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$q->whereAny(['name', 'tahun'], 'LIKE', '%' . $value . '%');
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
	 * @param FinancialReportRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(FinancialReportRequest $request): JsonResponse
	{
		try {
			$this->authorize('create', FinancialReport::class);

			DB::beginTransaction();

			$data = FinancialReport::create($request->safe()->except(['document', 'featured']));

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
	 * @param FinancialReport $financialReport
	 *
	 * @return JsonResponse
	 */
	public function show(FinancialReport $financialReport): JsonResponse
	{
//		$this->authorize('view', $financialReport);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $financialReport->load([
				'document',
				'featured',
			]),
		);
	}

	/**
	 * Update Data
	 *
	 * @param FinancialReportRequest $request
	 * @param FinancialReport        $financialReport
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(FinancialReportRequest $request, FinancialReport $financialReport): JsonResponse
	{
		try {
			$this->authorize('update', $financialReport);

			DB::beginTransaction();

			$financialReport->update($request->safe()->except(['document', 'featured']));

			if ($request->hasFile('document')) {
				$financialReport->addMedia($request->file('document'))->toMediaCollection('document');
			}
			if ($request->hasFile('featured')) {
				$financialReport->addMedia($request->file('featured'))->toMediaCollection('featured');
			}

			DB::commit();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $financialReport,
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
	 * @param FinancialReport $financialReport
	 *
	 * @return JsonResponse
	 */
	public function destroy(FinancialReport $financialReport): JsonResponse
	{
		$this->authorize('delete', $financialReport);

		$financialReport->delete();

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
		$this->authorize('bulkDelete', FinancialReport::class);

		$ids = collect($request->data)->pluck(value: 'id');
		foreach ($ids as $id) {
			$data = FinancialReport::where('id', $id)->firstOrFail();
			$data->delete();
		}

		return $this->response(
			message: 'Berhasil menghapus data.',
		);
	}
}
