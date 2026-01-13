<?php

namespace App\Models;

use App\Models\Master\Location;
use App\Traits\HasSortOrder;
use App\Traits\InteractsWithHashedMedia;
use App\Traits\OwnerTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class BusinessLine extends Model implements HasMedia
{
	use HasFactory,
		HasSlug,
		HasSortOrder,
		HasTranslations,
		HasUuids,
		InteractsWithHashedMedia,
		LogsActivity,
		OwnerTrait;

	/**
	 * The accessors to append to the model's array form.
	 *
	 * @var list<string>
	 */
	protected $appends = [
		'featured_thumb',
	];

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

	public array $translatable = [
		'desc',
		'title',
	];

	/**
	 * @return HasOne
	 */
	public function featured(): HasOne
	{
		return $this->hasOne(Media::class, 'model_id')->where('collection_name', 'featured');
	}

	/**
	 * Interact with the thumbnail featured.
	 *
	 * @return Attribute
	 */
	protected function featuredThumb(): Attribute
	{
		return Attribute::make(
			get: fn() => $this->getFirstMedia('featured')?->getFullUrl('thumb'),
		);
	}

	/**
	 * @return HasMany
	 */
	public function images(): HasMany
	{
		return $this->hasMany(Media::class, 'model_id')->where('collection_name', 'images');
	}

	/**
	 * @return HasMany
	 */
	public function locations(): HasMany
	{
		return $this->hasMany(Location::class, 'business_line_id');
	}

	/**
	 * @return LogOptions
	 */
	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logAll()
			->logOnlyDirty()
			->dontSubmitEmptyLogs();
	}

	/**
	 * Get the options for generating the slug.
	 */
	public function getSlugOptions(): SlugOptions
	{
		return SlugOptions::create()
			->generateSlugsFrom('title')
			->saveSlugsTo('slug')
			->preventOverwrite();
	}

	/**
	 * @return void
	 */
	public function registerMediaCollections(): void
	{
		$this->addMediaCollection('featured')->singleFile();
		$this->addMediaCollection('images');
	}

	/**
	 * @param Media|null $media
	 *
	 * @return void
	 */
	public function registerMediaConversions(?Media $media = null): void
	{
		$this->addMediaConversion('thumb')
			->format('webp')
			->width(287)
			->height(257)
			->quality(80);
	}
}
