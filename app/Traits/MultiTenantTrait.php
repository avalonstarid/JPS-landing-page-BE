<?php

namespace App\Traits;

use Closure;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method static addGlobalScope(string $string, Closure $param)
 */
trait MultiTenantTrait
{
	public static function bootMultiTenantTrait(): void
	{
		static::addGlobalScope('multi_tenant', function (Builder $builder) {
			// Cek hanya jika aplikasi tidak berjalan di console dan ada user yang login
			if (app()->runningInConsole() || !auth()->guard()->check()) {
				return;
			}

			$user = auth()->user();

//			if ($user->hasRole('super-admin')) {
//				return;
//			}

			$builder->where('created_by_id', $user->id);
		});
	}
}
