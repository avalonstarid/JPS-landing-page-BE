<?php

namespace App\Models\Investor;

use App\Traits\InteractsWithHashedMedia;
use App\Traits\OwnerTrait;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class FinancialReport extends Model implements HasMedia
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
		'name',
	];

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
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'arus_kas_bersih' => 'double',
			'ekuitas' => 'double',
			'laba_bersih' => 'double',
			'liabilitas' => 'double',
			'penjualan' => 'double',
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
