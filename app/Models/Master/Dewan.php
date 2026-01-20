<?php

namespace App\Models\Master;

use App\Traits\InteractsWithHashedMedia;
use App\Traits\OwnerTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Dewan extends Model implements HasMedia
{
	use HasFactory,
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
		'avatar_thumb',
	];

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'dewan';

	public array $translatable = [
		'jabatan',
	];

	/**
	 * @return HasOne
	 */
	public function avatar(): HasOne
	{
		return $this->hasOne(Media::class, 'model_id')->where('collection_name', 'avatar');
	}

	/**
	 * Interact with the thumbnail featured.
	 *
	 * @return Attribute
	 */
	protected function avatarThumb(): Attribute
	{
		return Attribute::make(
			get: fn() => $this->getFirstMedia('avatar')?->getFullUrl('thumb'),
		);
	}

	/**
	 * @return BelongsTo
	 */
	public function organisasi(): BelongsTo
	{
		return $this->belongsTo(Organisasi::class, 'organisasi_id');
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
		$this->addMediaCollection('avatar')->singleFile();
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
			->width(300)
			->quality(80);
	}
}
