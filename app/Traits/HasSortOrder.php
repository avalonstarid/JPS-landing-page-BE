<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasSortOrder
{
	public static function bootHasSortOrder()
	{
		// 1. EVENT CREATING
		static::creating(function (Model $model) {
			// Ambil urutan tertinggi saat ini di database
			// Jika kosong, max = 0
			$maxOrder = static::max('sort_order') ?? 0;

			// Tentukan batas maksimal yang diizinkan (Next Number)
			// Kalau data 3, berarti maksimal boleh input 4.
			$nextAvailable = $maxOrder + 1;

			// LOGIC BARU: Validasi Input User
			if (empty($model->sort_order)) {
				// Kasus A: User tidak isi -> Kasih urutan terakhir
				$model->sort_order = $nextAvailable;
			} else {
				// Kasus B: User isi manual
				// Kita paksa nilainya tidak boleh lebih dari $nextAvailable
				// Contoh: DB ada 3. User input 10. min(10, 4) -> Jadi 4.
				// Contoh: DB Kosong (0). User input 5. min(5, 1) -> Jadi 1.
				$model->sort_order = min($model->sort_order, $nextAvailable);
			}

			// Setelah angka divalidasi/di-clamp, baru jalankan logic geser
			// Jika user input urutan di tengah-tengah (misal input 2 padahal max 4)
			static::query()
				->where('sort_order', '>=', $model->sort_order)
				->increment('sort_order');
		});

		// 2. EVENT UPDATING
		static::updating(function (Model $model) {
			if (!$model->isDirty('sort_order')) {
				return;
			}

			// Validasi Max untuk Update juga penting
			// Misal data total 5, user mau ubah urutan 2 menjadi 100.
			// Harus kita paksa jadi 5 (urutan terakhir).
			$maxOrder = static::max('sort_order'); // Total data, misal 5

			// Clamp input user agar tidak melebihi jumlah data
			$model->sort_order = min($model->sort_order, $maxOrder);

			$newOrder = $model->sort_order;
			$oldOrder = $model->getOriginal('sort_order');

			// Logic geser tetap sama
			if ($newOrder < $oldOrder) {
				// Pindah ke Atas
				static::query()
					->where('sort_order', '>=', $newOrder)
					->where('sort_order', '<', $oldOrder)
					->where('id', '!=', $model->id) // Safety
					->increment('sort_order');
			} else if ($newOrder > $oldOrder) {
				// Pindah ke Bawah
				static::query()
					->where('sort_order', '>', $oldOrder)
					->where('sort_order', '<=', $newOrder)
					->where('id', '!=', $model->id) // Safety
					->decrement('sort_order');
			}
		});

		// 3. EVENT DELETED (Tetap sama)
		static::deleted(function (Model $model) {
			static::query()
				->where('sort_order', '>', $model->sort_order)
				->decrement('sort_order');
		});
	}
}
