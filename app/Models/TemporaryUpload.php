<?php

namespace App\Models;

use App\Traits\InteractsWithHashedMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TemporaryUpload extends Model implements HasMedia
{
	use InteractsWithHashedMedia;

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

	/**
	 * @param Media|null $media
	 *
	 * @return void
	 */
	public function registerMediaConversions(?Media $media = null): void
	{
		$this->addMediaConversion('thumb')
			->format('webp')
			->width(300)
			->quality(80)
			->nonQueued();
	}
}
