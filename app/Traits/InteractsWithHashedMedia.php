<?php

namespace App\Traits;

use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\FileAdder;

trait InteractsWithHashedMedia
{
	use InteractsWithMedia {
		InteractsWithMedia::addMediaFromBase64 as parentAddMediaFromBase64;
	}

	public function addMediaFromBase64(string $base64data, array|string ...$allowedMimeTypes): FileAdder
	{
		return $this->parentAddMediaFromBase64($base64data, ...$allowedMimeTypes)
			->usingFileName(date('Ymd_His_') . uniqid() . '.' . $this->getBase64ImageExtension($base64data));
	}

	function getBase64ImageExtension($base64Image): string
	{
		// Pisahkan metadata dan data Base64
		$metadata = explode(';', $base64Image)[0]; // Mengambil bagian 'data:image/jpeg'

		// Pisahkan berdasarkan titik dua (':') dan slash ('/')
		$mimeType = explode('/', $metadata)[1]; // Mengambil bagian 'jpeg'

		return $mimeType; // Ini akan mengembalikan 'jpeg', 'png', dll.
	}
}
