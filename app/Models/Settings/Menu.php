<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class Menu extends Model
{
	use HasFactory,
		HasRoles,
		HasUuids,
		LogsActivity;

	/**
	 * @var string
	 */
	protected string $guard_name = 'web';

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = [];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'active' => 'boolean',
	];

	/**
	 * @return BelongsTo
	 */
	public function parent(): BelongsTo
	{
		return $this->belongsTo(Menu::class, 'parent_id')->with(['parent']);
	}

	/**
	 * @return HasMany
	 */
	public function children(): HasMany
	{
		return $this->hasMany(Menu::class, 'parent_id')->with(['children'])->orderBy('order');
	}

	/**
	 * @return HasMany
	 */
	public function childrenActive(): HasMany
	{
		return $this->hasMany(Menu::class, 'parent_id')
			->where('active', true)
			->with(['childrenActive'])
			->orderBy('order');
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
