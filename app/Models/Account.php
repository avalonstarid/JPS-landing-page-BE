<?php

namespace App\Models;

use App\Models\Master\Bank;
use App\Models\Master\Enums;
use App\Traits\MultiTenantTrait;
use App\Traits\OwnerTrait;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Account extends Model
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
	 * @return BelongsTo
	 */
	public function bank(): BelongsTo
	{
		return $this->belongsTo(Bank::class, 'bank_id');
	}

	/**
	 * @return BelongsTo
	 */
	public function type(): BelongsTo
	{
		return $this->belongsTo(Enums::class, 'type_id', 'code');
	}

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'saldo' => 'double',
			'saldo_awal' => 'double',
			'saldo_batas' => 'double',
			'saldo_mengendap' => 'double',
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
