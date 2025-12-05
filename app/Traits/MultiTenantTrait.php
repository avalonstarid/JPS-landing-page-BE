<?php

namespace App\Traits;

use App\Models\Master\Category;
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
			$model = $builder->getModel();

//			if ($user->hasRole('super-admin')) {
//				return;
//			}

			if ($model instanceof Category) {
				$builder->where(function (Builder $query) use ($user) {
					$query->where('is_public', true)->orWhere('created_by_id', $user->id);
				});
				return;
			}

			$builder->where('created_by_id', $user->id);
		});
	}
}
