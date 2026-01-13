<?php

namespace App\Models\Master;

use App\Models\Investor\DocumentInvs;
use App\Models\JobPosting;
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

class Category extends Model
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
		'name',
	];

	/**
	 * @return HasMany
	 */
	public function children(): HasMany
	{
		return $this->hasMany(Category::class, 'parent_id');
	}

	/**
	 * @return HasMany
	 */
	public function documentInvs(): HasMany
	{
		return $this->hasMany(DocumentInvs::class, 'category_id');
	}

	/**
	 * @return HasMany
	 */
	public function jobPostings(): HasMany
	{
		return $this->hasMany(JobPosting::class, 'category_id');
	}

	/**
	 * @return BelongsTo
	 */
	public function parent(): BelongsTo
	{
		return $this->belongsTo(Category::class, 'parent_id');
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
			->generateSlugsFrom('name')
			->saveSlugsTo('slug')
			->preventOverwrite();
	}
}
