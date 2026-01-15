<?php

namespace App\Models;

use App\Traits\InteractsWithHashedMedia;
use App\Traits\OwnerTrait;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Post extends Model implements HasMedia
{
	use HasFactory,
		HasSlug,
		HasSEO,
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
		'content',
		'title',
	];

	/**
	 * @return BelongsTo
	 */
	public function author(): BelongsTo
	{
		return $this->belongsTo(User::class, 'created_by_id');
	}

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
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'is_published' => 'boolean',
		];
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

	/**
	 * Scope a query to only include news.
	 */
	#[Scope]
	protected function news(Builder $query): void
	{
		$query->where('type', 'news');
	}

	/**
	 * Scope a query to only include published.
	 */
	#[Scope]
	protected function published(Builder $query): void
	{
		$query->where('is_published', true)
			->where('published_at', '<=', now());
	}
}
