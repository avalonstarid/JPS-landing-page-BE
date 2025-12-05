<?php

namespace App\Models;

use App\Models\Master\Category;
use App\Observers\TransactionObserver;
use App\Traits\MultiTenantTrait;
use App\Traits\OwnerTrait;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

#[ObservedBy([TransactionObserver::class])]
class Transaction extends Model
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
	public function category(): BelongsTo
	{
		return $this->belongsTo(Category::class, 'category_id');
	}

	/**
	 * @return BelongsTo
	 */
	public function fromAccount(): BelongsTo
	{
		return $this->belongsTo(Account::class, 'from_account_id');
	}

	/**
	 * @return BelongsTo
	 */
	public function toAccount(): BelongsTo
	{
		return $this->belongsTo(Account::class, 'to_account_id');
	}

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'amount' => 'double',
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
