<?php

namespace App\Models;

use App\Traits\InteractsWithHashedMedia;
use App\Traits\OwnerTrait;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Setting extends Model implements HasMedia
{
	use HasFactory,
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

	public function getValueAttribute($value)
	{
		switch ($this->type) {
			case 'boolean':
				return (bool)$value;
			case 'int':
			case 'integer':
				return (int)$value;
			case 'json':
				return json_decode($value, true);
			default:
				return $value;
		}
	}

	public function setValueAttribute($value)
	{
		if (is_array($value)) {
			$this->attributes['value'] = json_encode($value);
			$this->attributes['type'] = 'json';
		} else if (is_bool($value)) {
			$this->attributes['value'] = $value ? '1' : '0';
			$this->attributes['type'] = 'boolean';
		} else {
			$this->attributes['value'] = $value;
		}
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
			->width(1000)
			->quality(80);
	}
}
