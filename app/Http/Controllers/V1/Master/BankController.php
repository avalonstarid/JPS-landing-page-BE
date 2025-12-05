<?php

namespace App\Http\Controllers\V1\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\BankRequest;
use App\Models\Master\Bank;
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
#[Subgroup("Bank", "API endpoint for bank.")]
class BankController extends Controller
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
	#[QueryParam('sort', description: 'Tambah tanda minus (-) di depan untuk descending', required: false, example: '', enum: ['code',
		'created_at', 'name', 'short_name', 'updated_at'])]
	public function index(Request $request): JsonResponse
	{
//		$this->authorize('viewAny', Bank::class);

		$query = QueryBuilder::for(
			subject: Bank::class,
		)->allowedSorts(
			sorts: [
				'code',
				'created_at',
				'name',
				'short_name',
				'updated_at',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$q->whereAny(['code', 'name', 'short_name'], 'LIKE', '%' . $value . '%');
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
	 * @param BankRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(BankRequest $request): JsonResponse
	{
		try {
			$this->authorize('create', Bank::class);

			DB::beginTransaction();

			$data = Bank::create($request->safe()->except(['logo', 'logo_remove']));

			if ($request->hasFile('logo')) {
				$data->addMedia($request->file('logo'))->toMediaCollection('logo');
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
	 * @param Bank $bank
	 *
	 * @return JsonResponse
	 */
	public function show(Bank $bank): JsonResponse
	{
//		$this->authorize('view', $bank);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $bank,
		);
	}

	/**
	 * Update Data
	 *
	 * @param BankRequest $request
	 * @param Bank        $bank
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(BankRequest $request, Bank $bank): JsonResponse
	{
		try {
			$this->authorize('update', $bank);

			DB::beginTransaction();

			$bank->update($request->safe()->except(['logo', 'logo_remove']));

			if ($request->hasFile('logo')) {
				$bank->addMedia($request->file('logo'))->toMediaCollection('logo');
			} else if ($request->safe()->boolean('logo_remove')) {
				$bank->clearMediaCollection('logo');
			}

			DB::commit();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $bank,
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
	 * @param Bank $bank
	 *
	 * @return JsonResponse
	 */
	public function destroy(Bank $bank): JsonResponse
	{
		$this->authorize('delete', $bank);

		$bank->delete();

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
		$this->authorize('bulkDelete', Bank::class);

		foreach ($request->data as $id) {
			Bank::where('id', $id)->delete();
		}

		return $this->response(
			message: 'Berhasil menghapus data.',
		);
	}
}
