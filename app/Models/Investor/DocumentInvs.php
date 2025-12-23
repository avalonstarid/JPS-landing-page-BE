<?php

namespace App\Models\Investor;

use App\Models\Master\Category;
use App\Traits\InteractsWithHashedMedia;
use App\Traits\OwnerTrait;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class DocumentInvs extends Model implements HasMedia
{
	use HasFactory,
		HasSlug,
		HasTranslations,
		HasUuids,
		InteractsWithHashedMedia,
		LogsActivity,
		OwnerTrait;

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

	public array $translatable = [
		'title',
	];

	/**
	 * @return BelongsTo
	 */
	public function category(): BelongsTo
	{
		return $this->belongsTo(Category::class, 'category_id');
	}

	/**
	 * @return HasOne
	 */
	public function document(): HasOne
	{
		return $this->hasOne(Media::class, 'model_id')->where('collection_name', 'document');
	}

	/**
	 * @return HasOne
	 */
	public function featured(): HasOne
	{
		return $this->hasOne(Media::class, 'model_id')->where('collection_name', 'featured');
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
		$this->addMediaCollection('document')->singleFile();
		$this->addMediaCollection('featured')->singleFile();
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
			->width(361)
			->height(300)
			->quality(100);
	}
}
