<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use Carbon\Carbon;
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

#[Group("Transaction", "API Endpoint for transaction.")]
class TransactionController extends Controller
{
	/**
	 * Get Data
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	#[QueryParam('filter[search]', required: false, example: '', enum: ['search', 'tanggal'])]
	#[QueryParam('include', required: false, example: '', enum: ['category', 'fromAccount', 'toAccount'])]
	#[QueryParam('rows', 'int', required: false, example: 10)]
	#[QueryParam('sort', description: 'Tambah tanda minus (-) di depan untuk descending', required: false, example: '', enum: ['amount',
		'tanggal'])]
	public function index(Request $request): JsonResponse
	{
//		$this->authorize('viewAny', Transaction::class);

		$query = QueryBuilder::for(
			subject: Transaction::class,
		)->allowedSorts(
			sorts: [
				'amount',
				'tanggal',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$q->whereAny(['desc'], 'LIKE', '%' . $value . '%');
				}),
				AllowedFilter::callback('tanggal', function (Builder $q, $value) {
					$q->whereBetween('tanggal',
						[Carbon::parse($value[0])->startOfDay(), Carbon::parse($value[1])->endOfDay()]);
				}),
				AllowedFilter::exact('type'),
			],
		)->allowedIncludes(
			includes: [
				'category',
				'fromAccount',
				'toAccount',
			],
		)->defaultSorts(
			sorts: 'tanggal',
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
	 * @param TransactionRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(TransactionRequest $request): JsonResponse
	{
		try {
			$this->authorize('create', Transaction::class);

			DB::beginTransaction();

			$data = Transaction::create($request->validated());

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
	 * @param Transaction $transaction
	 *
	 * @return JsonResponse
	 */
	public function show(Transaction $transaction): JsonResponse
	{
//		$this->authorize('view', $transaction);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $transaction,
		);
	}

	/**
	 * Update Data
	 *
	 * @param TransactionRequest $request
	 * @param Transaction        $transaction
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(TransactionRequest $request, Transaction $transaction): JsonResponse
	{
		try {
			$this->authorize('update', $transaction);

			DB::beginTransaction();

			$transaction->update($request->validated());

			DB::commit();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $transaction,
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
	 * @param Transaction $transaction
	 *
	 * @return JsonResponse
	 */
	public function destroy(Transaction $transaction): JsonResponse
	{
		$this->authorize('delete', $transaction);

		$transaction->delete();

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
		$this->authorize('bulkDelete', Transaction::class);

		$ids = collect($request->data)->pluck(value: 'id');

		Transaction::whereIn('id', $ids)->delete();

		return $this->response(
			message: 'Berhasil menghapus data.',
		);
	}
}
