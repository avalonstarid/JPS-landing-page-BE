<?php

namespace App\Http\Controllers\V1\Keberlanjutan;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Throwable;

class TinjauanController extends Controller
{
	/**
	 * Insert or Update Data
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

			$data = Post::whereHas('type', function ($query) {
				$query->where('code', 'TPST5');
			})->first();

			if ($data) {
				$data->update($request->safe()->except(['featured', 'featured_remove', 'seo', 'type']));
			} else {
				$data = Post::create($request->safe()->except([
					'featured', 'featured_remove', 'seo', 'type',
				]));
			}

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
				message: 'Berhasil menyimpan data.',
				data: $data,
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
	 * @return JsonResponse
	 */
	public function show(): JsonResponse
	{
		$data = Post::with(['featured', 'seo'])->whereHas('type', function ($query) {
			$query->where('code', 'TPST5');
		})->first();

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $data,
		);
	}
}
