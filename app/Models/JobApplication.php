<?php

namespace App\Models;

use App\Models\Master\Enums;
use App\Traits\InteractsWithHashedMedia;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class JobApplication extends Model implements HasMedia
{
	use HasFactory,
		HasUuids,
		InteractsWithHashedMedia,
		LogsActivity;

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

	/**
	 * @return BelongsTo
	 */
	public function gender(): BelongsTo
	{
		return $this->belongsTo(Enums::class, 'gender_id', 'code');
	}

	/**
	 * @return JobApplication|HasOne
	 */
	public function resume(): JobApplication|HasOne
	{
		return $this->hasOne(Media::class, 'model_id')->where('collection_name', 'resume');
	}

	/**
	 * @return BelongsTo
	 */
	public function statusKawin(): BelongsTo
	{
		return $this->belongsTo(Enums::class, 'status_kawin_id');
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
		$this->addMediaCollection('resume')->singleFile();
	}
}
