<?php

namespace App\Traits;

use App\Models\Setting;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Throwable;

trait ManagesSettings
{
	/**
	 * Logic terpusat untuk menyimpan settings.
	 *
	 * @param array  $settings Array Key-Value (Contoh: ['company_name' => 'PT A', 'hero_image' => $file])
	 * @param string $group    Group setting (Contoh: 'company', 'homepage')
	 *
	 * @return array Data yang disimpan
	 * @throws Throwable
	 */
	protected function updateSettings(array $settings, string $group): array
	{
		try {
			DB::beginTransaction();

			foreach ($settings as $key => $value) {
				// 1. SKIP jika value null (Opsional, tergantung aturan bisnismu)
				// if (is_null($value)) continue;

				// 2. Handle Upload File (Gambar/Dokumen)
				// Jika inputnya adalah File, kita tidak simpan binary-nya di kolom 'value'
				// Tapi kita upload via Spatie, lalu simpan URL-nya atau biarkan kosong (karena Spatie handle via relation)
				if ($value instanceof UploadedFile) {
					$this->handleFileUpload($key, $value, $group);
					continue; // Skip proses simpan text di bawah, lanjut ke item berikutnya
				}

				// 3. Tentukan Type Data (Otomatis)
				$type = 'string';
				if (is_array($value)) {
					$type = 'json';
				} else if (is_bool($value) || $value === '1' || $value === '0') {
					$type = 'boolean';
				}

				// 4. Simpan ke Database
				Setting::updateOrCreate(
					[
						'group' => $group,
						'key' => $key,
					],
					[
						'value' => $value,
						'type' => $type,
					],
				);
			}

			// 5. Wajib: Hapus Cache Global
			// Agar perubahan langsung nampil di Frontend tanpa delay
			Cache::forget('site_settings');

			DB::commit();

			return $settings;
		} catch (Exception $e) {
			DB::rollBack();

			throw $e;
		}
	}

	/**
	 * Helper khusus untuk handle upload gambar setting (Spatie)
	 */
	private function handleFileUpload(string $key, UploadedFile $file, string $group): void
	{
		// Cari atau Buat row setting-nya dulu
		$setting = Setting::firstOrCreate(
			[
				'group' => $group,
				'key' => $key,
			],
			['type' => 'image'], // Tandai tipe image
		);

		// Hapus gambar lama & Upload baru
		$setting->clearMediaCollection($key); // Gunakan nama key sebagai nama collection biar unik
		$setting->addMedia($file)->toMediaCollection($key);
	}
}
