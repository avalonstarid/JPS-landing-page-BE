<?php

namespace App\Services\MediaLibrary;

use Carbon\Carbon;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator as BasePathGenerator;

class CustomPathGenerator implements BasePathGenerator
{
	/**
	 * Get the path for conversions of the given media, relative to the root storage path.
	 *
	 * @param Media $media
	 *
	 * @return string
	 */
	public function getPathForConversions(Media $media): string
	{
		return $this->getPath($media) . 'conversions/';
	}

	/**
	 * Get the path for the given media, relative to the root storage path.
	 *
	 * @param Media $media
	 *
	 * @return string
	 */
	public function getPath(Media $media): string
	{
		$prefix = config('media-library.prefix');

		if ($media->collection_name) {
			$prefix .= $media->collection_name . '/';
		}

		if ($media->hasCustomProperty('date')) {
			$date = Carbon::make($media->getCustomProperty('date'));
			$prefix .= $date->year . '/' . $date->isoFormat('MM') . '/' . $date->isoFormat('DD');
		}

		return $prefix;
	}

	/**
	 * Get the path for responsive images of the given media, relative to the root storage path.
	 *
	 * @param Media $media
	 *
	 * @return string
	 */
	public function getPathForResponsiveImages(Media $media): string
	{
		return $this->getPath($media) . '/responsive-images/';
	}
}
