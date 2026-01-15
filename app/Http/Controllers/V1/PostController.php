<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

#[Group("Post", "API Endpoint for post.")]
class PostController extends Controller
{
	/**
	 * Get Data
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	#[QueryParam('filter[search]', required: false, example: '', enum: ['search'])]
	#[QueryParam('include', required: false, example: '', enum: ['author', 'featured', 'media'])]
	#[QueryParam('rows', 'int', required: false, example: 10)]
	#[QueryParam('sort', description: 'Tambah tanda minus (-) di depan untuk descending', required: false, example: '', enum: ['created_at',
		'published_at'])]
	public function index(Request $request): JsonResponse
	{
//		$this->authorize('viewAny', Post::class);

		$query = QueryBuilder::for(
			subject: Post::select([
				'id',
				'is_published',
				'likes',
				'published_at',
				'slug',
				'title',
				'type',
				'views',
				'created_at',
				'updated_at',
				'created_by_id',
				'updated_by_id',
			]),
		)->allowedSorts(
			sorts: [
				'created_at',
				'published_at',
				'views',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$q->whereAny(['slug', 'title'], 'LIKE', '%' . $value . '%');
				}),
				AllowedFilter::exact('type'),
			],
		)->allowedIncludes(
			includes: [
				'author',
				'featured',
				'media',
			],
		)->defaultSort('-published_at');

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
	 * @param PostRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(PostRequest $request): JsonResponse
	{
		try {
			$this->authorize('create', Post::class);

			DB::beginTransaction();

			$data = Post::create($request->safe()->except([
				'featured', 'featured_remove', 'seo',
			]));

			if ($request->hasFile('featured')) {
				$data->addMedia($request->file('featured'))->toMediaCollection('featured');
			}

			// Update SEO
			$title = $request->safe()->input('seo.title') ?? $data->title;
			$data->seo->update([
				'author' => $data->author->name,
				'description' => $request->safe()->input('seo.desc') ??
					Str::limit(strip_tags($data->content), 160, ''),
				'image' => $data->getFirstMediaUrl('featured') ?: null,
				'title' => $title . ' - ' . config('app.name'),
			]);

			// Ubah Kepemilikan Temp Media
			foreach ($request->safe()->array('temp_media_ids') as $mediaId) {
				$media = Media::find($mediaId);

				$media->move($data, 'uploads');
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
	 * @param Post $post
	 *
	 * @return JsonResponse
	 */
	public function show(Post $post): JsonResponse
	{
//		$this->authorize('view', $post);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $post->load([
				'featured',
				'seo',
			]),
		);
	}

	/**
	 * Update Data
	 *
	 * @param PostRequest $request
	 * @param Post        $post
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(PostRequest $request, Post $post): JsonResponse
	{
		try {
			$this->authorize('update', $post);

			DB::beginTransaction();

			$post->update($request->safe()->except(['featured', 'featured_remove']));

			// Set Featured Image
			if ($request->hasFile('featured')) {
				$post->addMedia($request->file('featured'))->toMediaCollection('featured');
			} else if ($request->safe()->boolean('featured_remove')) {
				$post->clearMediaCollection('featured');
			}

			// Update SEO
			$post->seo->update([
				'author' => $post->author->name,
				'description' => $request->safe()->input('seo.desc') ??
					Str::limit(strip_tags($post->content), 160, ''),
				'image' => $post->getFirstMediaUrl('featured') ?: null,
				'title' => $request->safe()->input('seo.title') ?? $post->title,
			]);

			// Ubah Kepemilikan Temp Media
			foreach ($request->safe()->array('temp_media_ids') as $mediaId) {
				$media = Media::find($mediaId);

				$media->move($post, 'uploads');
			}

			DB::commit();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $post,
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
	 * @param Post $post
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function destroy(Post $post): JsonResponse
	{
		try {
			$this->authorize('delete', $post);

			DB::beginTransaction();

			$post->delete();

			DB::commit();

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
			$this->authorize('bulkDelete', Post::class);

			DB::beginTransaction();

			$ids = collect($request->data)->pluck(value: 'id');
			foreach ($ids as $id) {
				$data = Post::where('id', $id)->firstOrFail();
				$data->delete();
			}

			DB::commit();

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
}
