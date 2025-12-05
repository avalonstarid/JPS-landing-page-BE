<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use WhichBrowser\Parser;

class SessionModel extends Model
{
	use HasFactory,
		HasUuids;

	/**
	 * The accessors to append to the model's array form.
	 *
	 * @var array
	 */
	protected $appends = ['ua'];

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = [];

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'sessions';

	/**
	 * make object user_agent.
	 *
	 * @return Attribute
	 */
	public function ua(): Attribute
	{
		return Attribute::make(
			get: fn() => new Parser($this->user_agent),
		);
	}

	/**
	 * The "booted" method of the model.
	 */
	protected static function booted(): void
	{
		if (!app()->runningInConsole() && auth()->check()) {
			static::addGlobalScope('user_id', function (Builder $builder) {
				$builder->where('user_id', auth()->id());
			});
		}
	}
}
