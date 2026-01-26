<?php

namespace App\Http\Controllers\V1\Investor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Investor\FinancialReportRequest;
use App\Http\Resources\Investor\FinancialReportSource;
use App\Models\Investor\FinancialReport;
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
	#[QueryParam('filter[search]', required: false, example: '', enum: ['search', 'tahun'])]
	#[QueryParam('include', required: false, example: '', enum: ['document'])]
	#[QueryParam('rows', 'int', required: false, example: 10)]
	#[QueryParam('sort', description: 'Tambah tanda minus (-) di depan untuk descending', required: false, example: '', enum: [
		'created_at', 'tahun'])]
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
				AllowedFilter::exact('tahun'),
			],
		)->allowedIncludes(
			includes: ['document'],
		);

		if ($request->boolean('all')) {
			$data = $query->get();
		} else {
			$rows = $request->input('rows', 10);

			$data = $query->fastPaginate($rows)->withQueryString();
		}

		return $this->responseNew(
			message: 'Berhasil mengambil data.',
			data: FinancialReportSource::collection($data),
		);
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

			DB::commit();

			$this->clearCache();

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
		$this->authorize('bulkDelete', FinancialReport::class);

		$ids = collect($request->data)->pluck(value: 'id');
		foreach ($ids as $id) {
			$data = FinancialReport::where('id', $id)->firstOrFail();
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
		Cache::forget('landing:relasiInvestor:laporan-keuangan');
	}
}
