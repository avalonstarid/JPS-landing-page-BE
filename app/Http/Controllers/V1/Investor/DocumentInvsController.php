<?php

namespace App\Http\Controllers\V1\Investor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Investor\DocumentInvsRequest;
use App\Http\Resources\Investor\DocumentInvsSource;
use App\Models\Investor\DocumentInvs;
use App\Models\Master\Category;
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
class DocumentInvsController extends Controller
{
	/**
	 * Get Data
	 *
	 * @param Request  $request
	 * @param Category $category
	 *
	 * @return JsonResponse
	 */
	#[QueryParam('filter[search]', required: false, example: '', enum: ['search'])]
	#[QueryParam('include', required: false, example: '', enum: ['category', 'document', 'featured', 'media'])]
	#[QueryParam('rows', 'int', required: false, example: 10)]
	#[QueryParam('sort', description: 'Tambah tanda minus (-) di depan untuk descending', required: false, example: '', enum: [
		'created_at', 'tahun'])]
	public function index(Request $request, Category $category): JsonResponse
	{
//		$this->authorize('viewAny', DocumentInvs::class);

		$query = QueryBuilder::for(
			subject: $category->documentInvs(),
		)->allowedSorts(
			sorts: [
				'created_at',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$q->whereAny(['title'], 'LIKE', '%' . $value . '%');
				}),
			],
		)->allowedIncludes(
			includes: ['category', 'document', 'featured', 'media'],
		);

		if ($request->boolean('all')) {
			$data = $query->get();
		} else {
			$rows = $request->input('rows', 10);

			$data = $query->fastPaginate($rows)->withQueryString();
		}

		return $this->responseNew(
			message: 'Berhasil mengambil data.',
			data: DocumentInvsSource::collection($data),
		);
	}

	/**
	 * Insert Data
	 *
	 * @param DocumentInvsRequest $request
	 * @param Category            $category
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(DocumentInvsRequest $request, Category $category): JsonResponse
	{
		try {
			$this->authorize('create', DocumentInvs::class);

			DB::beginTransaction();

			$data = $category->documentInvs()->create($request->safe()->except(['document', 'featured']));

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
	 * @param Category     $category
	 * @param DocumentInvs $document
	 *
	 * @return JsonResponse
	 */
	public function show(Category $category, DocumentInvs $document): JsonResponse
	{
//		$this->authorize('view', $document);

		if ($document->category->slug !== $category->slug) {
			abort(404, 'Data tidak ditemukan.');
		}

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $document->load([
				'document',
				'featured',
			]),
		);
	}

	/**
	 * Update Data
	 *
	 * @param DocumentInvsRequest $request
	 * @param Category            $category
	 * @param DocumentInvs        $document
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(DocumentInvsRequest $request, Category $category, DocumentInvs $document): JsonResponse
	{
		try {
			$this->authorize('update', $document);

			if ($document->category->slug !== $category->slug) {
				abort(404, 'Data tidak ditemukan.');
			}

			DB::beginTransaction();

			$document->update($request->safe()->except(['document', 'featured']));

			if ($request->hasFile('document')) {
				$document->addMedia($request->file('document'))->toMediaCollection('document');
			}
			if ($request->hasFile('featured')) {
				$document->addMedia($request->file('featured'))->toMediaCollection('featured');
			}

			DB::commit();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $document,
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
	 * @param Category     $category
	 * @param DocumentInvs $document
	 *
	 * @return JsonResponse
	 */
	public function destroy(Category $category, DocumentInvs $document): JsonResponse
	{
		$this->authorize('delete', $document);

		if ($document->category->slug !== $category->slug) {
			abort(404, 'Data tidak ditemukan.');
		}

		$document->delete();

		return $this->response(
			message: 'Berhasil menghapus data.',
		);
	}

	/**
	 * Bulk Delete Data
	 *
	 * @param Request  $request
	 * @param Category $category
	 *
	 * @return JsonResponse
	 */
	#[BodyParam("data", "object[]", "List of id", example: [['id' => 1]])]
	public function bulkDestroy(Request $request, Category $category): JsonResponse
	{
		$this->authorize('bulkDelete', DocumentInvs::class);

		$ids = collect($request->data)->pluck(value: 'id');
		foreach ($ids as $id) {
			$data = DocumentInvs::where('id', $id)->firstOrFail();
			$data->delete();
		}

		return $this->response(
			message: 'Berhasil menghapus data.',
		);
	}
}
