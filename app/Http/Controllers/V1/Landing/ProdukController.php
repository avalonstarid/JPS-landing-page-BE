<?php

namespace App\Http\Controllers\V1\Landing;

use App\Http\Controllers\Controller;
use App\Http\Resources\Landing\ProductDetailResource;
use App\Models\Master\Product;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class ProdukController extends Controller
{
	/**
	 * Get Produk Landing Page
	 *
	 * @return JsonResponse
	 */
	public function index()
	{
		$data = Cache::remember('landing:produk', 3600, function () {
			$settingQuery = Cache::rememberForever('settings:landing_produk', function () {
				return Setting::where('group', 'landing_produk')->get();
			});

			$settings = $settingQuery->mapWithKeys(function ($item) {
				if ($item->type === 'image') {
					return [$item->key => $item->getFirstMediaUrl($item->key, 'thumb')];
				}

				return [$item->key => $item->value];
			});

			$products = Product::with(['images'])->active()->orderBy('sort_order')->get();

			$stockUpdated = Carbon::parse($settingQuery->where('key', 'commercial_stock_products')
				->first()?->updated_at ?? now());

			$seo = new SEOData(
				title: $settings['seo_title'] ?? 'Produk - ' . config('app.name'),
				description: $settings['seo_description'] ??
				'Rangkaian produk unggas kami memenuhi standar kualitas, keamanan, dan konsistensi.',
				url: config('app.landing_url') . '/' . request()->path(),
				type: 'website',
				site_name: config('app.name'),
				locale: 'id_ID',
				robots: 'index, follow',
				canonical_url: config('app.landing_url') . '/' . request()->path(),
			);

			return [
				// Commercial
				'commercial' => [
					'stock' => [
						'last_update' => [
							'en' => 'Last Updated ' . $stockUpdated->copy()->locale('en')->translatedFormat('d F Y'),
							'id' => 'Terakhir diupdate ' .
								$stockUpdated->copy()->locale('id')->translatedFormat('d F Y'),
						],
						'products' => $settings['commercial_stock_products'] ?? [],
						'title' => $settings['commercial_stock_title'] ?? [
								'en' => 'Stock Update',
								'id' => 'Pembaruan Stok',
							],
					],
					'title' => $settings['commercial_title'] ?? [
							'en' => 'Our Commercial Products',
							'id' => 'Produk Komersial Kami',
						],
				],

				// Hero
				'hero' => [
					'background' => $settings['hero_background'] ?? null,
					'subtitle' => $settings['hero_subtitle'] ?? [
							'en' => 'We provide a range of poultry products that meet quality, safety, and consistency standards',
							'id' => 'Kami menyediakan rangkaian produk unggas yang memenuhi standar kualitas, keamanan, dan konsisten',
						],
					'title' => $settings['hero_title'] ?? [
							'en' => 'Integrated Poultry Solutions',
							'id' => 'Solusi Perunggasan Terintegrasi',
						],
				],

				// Produk
				'product' => [
					'data' => ProductDetailResource::collection($products),
				],

				// SEO
				'seo' => [
					'title' => $seo->title,
					'description' => $seo->description,
					'url' => $seo->url,
					'type' => $seo->type,
					'site_name' => $seo->site_name,
					'locale' => $seo->locale,
					'robots' => $seo->robots,
					'canonical_url' => $seo->canonical_url,
				],
			];
		});

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $data,
		);
	}
}
