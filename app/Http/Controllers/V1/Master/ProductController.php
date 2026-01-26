<?php

namespace App\Http\Controllers\V1\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\ProductRequest;
use App\Models\Master\Product;
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

#[Group("Master", "API Endpoint for Master.")]
#[Subgroup("Product", "API endpoint for product list.")]
class ProductController extends Controller
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
	#[QueryParam('sort', description: 'Tambah tanda minus (-) di depan untuk descending', required: false, example: '', enum: ['created_at',
		'sort_order', 'title'])]
	public function index(Request $request): JsonResponse
	{
//		$this->authorize('viewAny', Product::class);

		$query = QueryBuilder::for(
			subject: Product::class,
		)->allowedSorts(
			sorts: [
				'created_at',
				'sort_order',
				'title',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$q->whereAny(['slug', 'title'], 'LIKE', '%' . $value . '%');
				}),
			],
		)->allowedIncludes(
			includes: [
				'media',
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
	 * @param ProductRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(ProductRequest $request): JsonResponse
	{
		try {
			$this->authorize('create', Product::class);

			DB::beginTransaction();

			$data = Product::create($request->safe()->except([
				'featured', 'featured_remove', 'images', 'images_remove',
			]));

			if ($request->hasFile('featured')) {
				$data->addMedia($request->file('featured'))->toMediaCollection('featured');
			}
			if ($request->hasFile('images')) {
				$data->addMultipleMediaFromRequest(['images'])
					->each(function ($fileAdder) {
						$fileAdder->toMediaCollection('images');
					});
			}

			// Disable Logging
			activity()->disableLogging();

			// Set Order
			$sameOrder = Product::where([
				'sort_order' => $data->sort_order,
			])->first();

			if ($sameOrder) {
				$orderNew = Product::where([
					['id', '!=', $data->id],
					['sort_order', '>=', $data->sort_order],
				])->get();

				foreach ($orderNew as $item) {
					$item->sort_order = $item->sort_order + 1;
					$item->save();
				}
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
	 * @param Product $product
	 *
	 * @return JsonResponse
	 */
	public function show(Product $product): JsonResponse
	{
//		$this->authorize('view', $product);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $product->load([
				'featured',
				'images',
			]),
		);
	}

	/**
	 * Update Data
	 *
	 * @param ProductRequest $request
	 * @param Product        $product
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(ProductRequest $request, Product $product): JsonResponse
	{
		try {
			$this->authorize('update', $product);

			DB::beginTransaction();

			$oldData = Product::find($product->id);
			$sameOrder = Product::where([
				'sort_order' => $product->sort_order,
			])->first();

			$product->update($request->safe()->except(['featured', 'featured_remove', 'images', 'images_remove']));

			// Set Featured Image
			if ($request->hasFile('featured')) {
				$product->addMedia($request->file('featured'))->toMediaCollection('featured');
			} else if ($request->safe()->boolean('featured_remove')) {
				$product->clearMediaCollection('featured');
			}

			// Set Images
			if (!empty($request->safe()->array('images_remove'))) {
				$product->media()
					->whereIn('id', $request->safe()->array('images_remove'))
					->get()
					->each->delete();
			}
			if ($request->hasFile('images')) {
				$product->addMultipleMediaFromRequest(['images'])
					->each(function ($fileAdder) {
						$fileAdder->toMediaCollection('images');
					});
			}

			// Disable Logging
			activity()->disableLogging();

			if ($sameOrder) {
				if ($oldData) {
					$orderOld = Product::where([
						['id', '!=', $product->id],
						['sort_order', '>', $product->sort_order],
					])->get();
					foreach ($orderOld as $item) {
						if ($item->sort_order > 1) {
							$item->sort_order = $item->sort_order - 1;
							$item->save();
						}
					}
				}

				$orderNew = Product::where([
					['id', '!=', $product->id],
					['sort_order', '>=', $product->sort_order],
				])->get();
				foreach ($orderNew as $item) {
					$item->sort_order = $item->sort_order + 1;
					$item->save();
				}
			}

			DB::commit();

			$this->clearCache();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $product,
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
	 * @param Product $product
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function destroy(Product $product): JsonResponse
	{
		try {
			$this->authorize('delete', $product);

			DB::beginTransaction();

			$product->delete();

			$order = Product::where([
				['id', '!=', $product->id],
				['sort_order', '>', $product->sort_order],
			])->get();
			foreach ($order as $item) {
				if ($item->sort_order > 1) {
					$item->sort_order = $item->sort_order - 1;
					$item->save();
				}
			}

			DB::commit();

			$this->clearCache();

			return $this->response(
				message: 'Berhasil menghapus data.',
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
	 * Bulk Delete Data
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	#[BodyParam("data", "object[]", "List of id", example: [['id' => 1]])]
	public function bulkDestroy(Request $request): JsonResponse
	{
		try {
			$this->authorize('bulkDelete', Product::class);

			DB::beginTransaction();

			$ids = collect($request->data)->pluck(value: 'id');
			foreach ($ids as $id) {
				$data = Product::where('id', $id)->firstOrFail();
				$data->delete();

				$order = Product::where([
					['id', '!=', $data->id],
					['sort_order', '>', $data->sort_order],
				])->get();

				foreach ($order as $item2) {
					if ($item2->sort_order > 1) {
						$item2->sort_order = $item2->sort_order - 1;
						$item2->save();
					}
				}
			}

			DB::commit();

			$this->clearCache();

			return $this->response(
				message: 'Data berhasil dihapus.',
			);
		} catch (Exception $e) {
			DB::rollBack();

			return $this->response(
				message: 'Data gagal dihapus. ' . $e->getMessage(),
				status_code: 500,
			);
		}
	}

	/**
	 * Clear Cache
	 *
	 * @return void
	 */
	private function clearCache()
	{
		Cache::forget('landing:index');
		Cache::forget('landing:produk');
	}
}
