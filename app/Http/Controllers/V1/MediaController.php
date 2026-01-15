<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\TemporaryUpload;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Knuckles\Scribe\Attributes\Group;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Throwable;

#[Group("Media", "API Endpoint for media.")]
class MediaController extends Controller
{
	/**
	 * Delete Data
	 *
	 * @param Media $media
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function destroy(Media $media): JsonResponse
	{
		try {
			DB::beginTransaction();

			$media->delete();

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
	 * Upload Media Image
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function upload(Request $request)
	{
		try {
			$request->validate([
				'image' => ['required', 'image', 'max:10240'],
			]);

			$tempModel = TemporaryUpload::create([
				'session_id' => session()->getId(),
			]);

			$media = $tempModel->addMedia($request->file('image'))->withCustomProperties(['date' => now()])
				->toMediaCollection('uploads');

			return $this->response(
				message: 'Berhasil upload image.',
				data: [
					'media_id' => $media->id,
					'url_original' => $media->getUrl(),
					'url' => $media->getUrl('thumb'),
				],
			);
		} catch (Exception $e) {
			return $this->response(
				message: $e->getMessage(),
				status_code: $e->getCode(),
			);
		}
	}
}
