<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

trait SyncsRelations
{
	/**
	 * Sinkronisasi relasi hasMany (update, create, dan delete).
	 *
	 * @param HasMany $relation Relasi Eloquent yang akan disinkronkan.
	 * @param array   $items Data dari payload request yang sudah divalidasi.
	 *
	 * @return void
	 */
	protected function syncHasMany(HasMany $relation, array $items): void
	{
		$processedIds = [];

		foreach ($items as $itemData) {
			// Pisahkan 'id' dari data lainnya
			$id = $itemData['id'] ?? null;
			$dataToUpdateOrCreate = Arr::except($itemData, ['id']);

			// Lakukan update atau create pada relasi
			$model = $relation->updateOrCreate(
				['id' => $id],       // Kondisi untuk mencari
				$dataToUpdateOrCreate  // Data untuk diisi
			);

			// Kumpulkan ID dari item yang ada di payload
			$processedIds[] = $model->id;
		}

		// Hapus semua item relasi yang ID-nya tidak ada di payload
		$relation->whereNotIn('id', $processedIds)->delete();
	}
}
