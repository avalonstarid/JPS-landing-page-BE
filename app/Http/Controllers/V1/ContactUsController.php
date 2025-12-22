<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactUsRequest;
use App\Mail\ContactUsMail;
use App\Models\ContactUs;
use App\Models\Setting;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

#[Group("Contact Us", "API Endpoint for contact us form.")]
class ContactUsController extends Controller
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
		'created_at'])]
	public function index(Request $request): JsonResponse
	{
//		$this->authorize('viewAny', ContactUs::class);

		$query = QueryBuilder::for(
			subject: ContactUs::select([
				'id', 'email', 'location', 'name', 'phone', 'created_at', 'updated_at', 'created_by_id',
				'updated_by_id',
			]),
		)->allowedSorts(
			sorts: [
				'created_at',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$q->whereAny(['email', 'location', 'message', 'name'], 'LIKE', '%' . $value . '%');
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
	 * @param ContactUsRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(ContactUsRequest $request): JsonResponse
	{
		try {
//			$this->authorize('create', ContactUs::class);

			DB::beginTransaction();

			$data = ContactUs::create($request->validated());

			DB::commit();

			// Send Email
			$company = Setting::where('group', 'company')->get()
				->mapWithKeys(function ($item) {
					return [$item->key => $item->value];
				})->toArray();
			Mail::to($company['company_social']['email'])->send(new ContactUsMail(array_merge($data->toArray(),
				$company)));

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
	 * @param ContactUs $contactUs
	 *
	 * @return JsonResponse
	 */
	public function show(ContactUs $contactUs): JsonResponse
	{
//		$this->authorize('view', $contactUs);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $contactUs,
		);
	}

	/**
	 * Update Data
	 *
	 * @param ContactUsRequest $request
	 * @param ContactUs        $contactUs
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(ContactUsRequest $request, ContactUs $contactUs): JsonResponse
	{
		try {
			$this->authorize('update', $contactUs);

			DB::beginTransaction();

			$contactUs->update($request->validated());

			DB::commit();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $contactUs,
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
	 * @param ContactUs $contactUs
	 *
	 * @return JsonResponse
	 */
	public function destroy(ContactUs $contactUs): JsonResponse
	{
		$this->authorize('delete', $contactUs);

		$contactUs->delete();

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
		$this->authorize('bulkDelete', ContactUs::class);

		$ids = collect($request->data)->pluck(value: 'id');
		foreach ($ids as $id) {
			$data = ContactUs::where('id', $id)->firstOrFail();
			$data->delete();
		}

		return $this->response(
			message: 'Berhasil menghapus data.',
		);
	}
}
