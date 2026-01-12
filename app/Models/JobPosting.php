<?php

namespace App\Models;

use App\Models\Master\Category;
use App\Traits\OwnerTrait;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class JobPosting extends Model
{
	use HasFactory,
		HasSlug,
		HasTranslations,
		HasUuids,
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
		'desc_short',
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
	 * @return HasMany
	 */
	public function pelamar(): HasMany
	{
		return $this->hasMany(JobApplication::class, 'job_posting_id');
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
}
