<?php

namespace App\Models\Master;

use App\Traits\MultiTenantTrait;
use App\Traits\OwnerTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
	use HasFactory,
		HasUuids,
		LogsActivity,
		MultiTenantTrait,
		OwnerTrait;

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = [];

	/**
	 * @return HasMany
	 */
	public function children(): HasMany
	{
		return $this->hasMany(Category::class, 'parent_id')->orderBy('name');
	}

	/**
	 * @return BelongsTo
	 */
	public function parent(): BelongsTo
	{
		return $this->belongsTo(Category::class, 'parent_id');
	}

	/**
	 * Perform any actions required after the model boots.
	 *
	 * @return void
	 */
	protected static function booted(): void
	{
		static::addGlobalScope('multi_tenant', function (Builder $builder) {
			$builder->where('is_public', true)->orWhere('created_by_id', auth()->id());
		});
	}

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'is_public' => 'boolean',
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
}
