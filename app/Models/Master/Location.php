<?php

namespace App\Models\Master;

use App\Models\BusinessLine;
use App\Traits\InteractsWithHashedMedia;
use App\Traits\OwnerTrait;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Location extends Model implements HasMedia
{
	use HasFactory,
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
		'desc',
	];

	/**
	 * @return BelongsTo
	 */
	public function businessLine(): BelongsTo
	{
		return $this->belongsTo(BusinessLine::class, 'business_line_id');
	}

	/**
	 * @return HasMany
	 */
	public function images(): HasMany
	{
		return $this->hasMany(Media::class, 'model_id')->where('collection_name', 'images');
	}

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'active' => 'boolean',
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
	 * @return void
	 */
	public function registerMediaCollections(): void
	{
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
