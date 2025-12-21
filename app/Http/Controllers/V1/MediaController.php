<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
		$request->validate([
			'image' => ['required', 'image', 'max:5120'],
		]);

		$file = $request->file('image');

		$originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
		$safeName = Str::slug($originalName);
		// Hasil: "foto-kegiatan-1708456123-ax9z.jpg"
		$filename = $safeName . '-' . time() . '-' . Str::random(4) . '.' . $file->getClientOriginalExtension();

		$path = $file->storeAs('uploads/images', $filename, 'public');

		return $this->response(
			message: 'Berhasil upload image.',
			data: [
				'url' => asset(Storage::url($path)),
			],
		);
	}
}
