<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountAdjustRequest;
use App\Http\Requests\AccountRequest;
use App\Models\Account;
use App\Models\Master\Category;
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

#[Group("Account", "API Endpoint for Account Management.")]
class AccountController extends Controller
{
	/**
	 * Get Data
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	#[QueryParam('filter[search]', required: false, example: '', enum: ['search'])]
	#[QueryParam('include', required: false, example: '', enum: ['type'])]
	#[QueryParam('rows', 'int', required: false, example: 10)]
	#[QueryParam('sort', description: 'Tambah tanda minus (-) di depan untuk descending', required: false, example: '', enum: ['name',
		'saldo', 'tgl_transaksi'])]
	public function index(Request $request): JsonResponse
	{
//		$this->authorize('viewAny', Account::class);

		$query = QueryBuilder::for(
			subject: Account::class,
		)->allowedSorts(
			sorts: [
				'name',
				'saldo',
				'saldo_batas',
				'tgl_transaksi',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$q->whereAny(['desc', 'name'], 'LIKE', '%' . $value . '%');
				}),
				AllowedFilter::exact('type_id'),
			],
		)->allowedIncludes(
			includes: [
				'bank',
				'type',
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
	 * @param AccountRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(AccountRequest $request): JsonResponse
	{
		try {
			$this->authorize('create', Account::class);

			DB::beginTransaction();

			$data = Account::create($request->validated());

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
	 * @param Account $account
	 *
	 * @return JsonResponse
	 */
	public function show(Account $account): JsonResponse
	{
//		$this->authorize('view', $account);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $account,
		);
	}

	/**
	 * Update Data
	 *
	 * @param AccountRequest $request
	 * @param Account        $account
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(AccountRequest $request, Account $account): JsonResponse
	{
		try {
			$this->authorize('update', $account);

			DB::beginTransaction();

			$account->update($request->validated());

			DB::commit();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $account,
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
	 * @param Account $account
	 *
	 * @return JsonResponse
	 */
	public function destroy(Account $account): JsonResponse
	{
		$this->authorize('delete', $account);

		$account->delete();

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
		$this->authorize('bulkDelete', Account::class);

		Account::whereIn('id', $request->data)->delete();

		return $this->response(
			message: 'Berhasil menghapus data.',
		);
	}

	/**
	 * Adjust Saldo Akun.
	 *
	 * @param AccountAdjustRequest $request
	 * @param Account              $account
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function adjust(AccountAdjustRequest $request, Account $account): JsonResponse
	{
		try {
			$this->authorize('adjust', $account);

			DB::beginTransaction();

			$perbandingan = $request->validated('saldo') - $account->saldo;
			$category = Category::where('type', 'A')->first();

			$account->update($request->validated());

			Transaction::withoutEvents(function () use ($account, $perbandingan, $category) {
				Transaction::create([
					'amount' => $perbandingan,
					'desc' => 'Adjust Saldo',
					'tanggal' => Carbon::now(),
					'type' => ($perbandingan > 0) ? 'I' : 'E',
					'category_id' => $category->id,
					'created_by_id' => auth()->id(),
					'from_account_id' => $account->id,
				]);
			});

			DB::commit();

			return $this->response(
				message: 'Berhasil melakukan penyesuaian saldo.',
				data: $account,
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
	 * Statistik Saldo Akun
	 *
	 * @return JsonResponse
	 */
	public function stat()
	{
		$saldoAvail = Account::whereIn('type_id', ['BANK', 'KAS'])->sum('saldo');
		$saldoPasif = Account::whereIn('type_id', ['INV', 'PIUTANG'])->sum('saldo');
		$saldoCredit = Account::whereIn('type_id', ['CC'])->sum('saldo');

		$akumulasi = $saldoAvail + $saldoPasif - $saldoCredit;

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: [
				'avail' => (double)$saldoAvail,
				'pasif' => (double)$saldoPasif,
				'credit' => (double)$saldoCredit,
				'akumulasi' => (double)$akumulasi,
			],
		);
	}
}
