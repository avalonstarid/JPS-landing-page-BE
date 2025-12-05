<?php

namespace App\Traits;

use Closure;

/**
 * @method static creating(Closure $param)
 * @method static updating(Closure $param)
 */
trait OwnerTrait
{
	/**
	 * Boot the trait.
	 *
	 * @return void
	 */
	public static function bootOwnerTrait(): void
	{
		// Kita tidak perlu mengecek auth di sini.
		// Cukup periksa apakah kita sedang berjalan di console atau tidak.
		if (app()->runningInConsole()) {
			return;
		}

		/**
		 * Daftarkan event listener 'creating'.
		 * Pengecekan auth dilakukan di DALAM listener,
		 * sehingga dievaluasi untuk SETIAP request.
		 */
		static::creating(function ($model) {
			// Gunakan helper auth()->id()
			// Ini aman: akan mengembalikan ID pengguna jika login,
			// atau NULL jika tidak, tanpa menyebabkan error.
			if (!$model->created_by_id) {
				$model->setAttribute('created_by_id', auth()->id());
			}
		});

		/**
		 * Lakukan hal yang sama untuk 'updating'.
		 */
		static::updating(function ($model) {
			$model->setAttribute('updated_by_id', auth()->id());
		});
	}
}
