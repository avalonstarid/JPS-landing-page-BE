<?php

namespace App\Http\Controllers\V1\Landing;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class HubungiKamiController extends Controller
{
	/**
	 * Get Hubungi Kami Landing Page
	 *
	 * @return JsonResponse
	 */
	public function index()
	{
		$data = Cache::remember('landing:hubungiKami', 3600, function () {
			$contactData = Cache::rememberForever('settings:company', function () {
				return Setting::where('group', 'company')->get();
			})->mapWithKeys(function ($item) {
				if ($item->type === 'image') {
					return [$item->key => $item->getFirstMediaUrl($item->key)];
				}

				return [$item->key => $item->value];
			});

			$settings = Cache::rememberForever('settings:landing_hubungi_kami', function () {
				return Setting::where('group', 'landing_hubungi_kami')->get();
			})->mapWithKeys(function ($item) {
				if ($item->type === 'image') {
					return [$item->key => $item->getFirstMediaUrl($item->key, 'thumb')];
				}

				return [$item->key => $item->value];
			});

			$seo = new SEOData(
				title: $settings['seo_title'] ?? 'Hubungi Kami - ' . config('app.name'),
				description: $settings['seo_description'] ??
				'Untuk informasi lebih lanjut, kemitraan, pertanyaan seputar produk.',
				url: config('app.landing_url') . '/' . request()->path(),
				type: 'website',
				site_name: config('app.name'),
				locale: 'id_ID',
				robots: 'index, follow',
				canonical_url: config('app.landing_url') . '/' . request()->path(),
			);

			return [
				// Contact
				'contact' => [
					'data' => $contactData['company_social'],
					'title' => $settings['contact_title'] ?? [
							'en' => 'More Contact',
							'id' => 'Kontak Lebih Lanjut',
						],
				],

				// Hero
				'hero' => [
					'background' => $settings['hero_background'] ?? null,
					'subtitle' => $settings['hero_subtitle'] ?? [
							'en' => 'For further information, partnerships, product inquiries',
							'id' => 'Untuk informasi lebih lanjut, kemitraan, pertanyaan seputar produk',
						],
					'title' => $settings['hero_title'] ?? [
							'en' => 'Contact Us',
							'id' => 'Hubungi Kami',
						],
				],

				// Map
				'map' => [
					'address' => $settings['map_address'] ??
						'Ruko Casa Grande No.35, Jl. Ringroad Utara, Maguwoharjo, Depok, Sleman, Daerah Istimewa Yogyakarta',
					'link' => $settings['map_link'] ??
						'https://www.google.com/maps?q=-7.763563,110.421265&z=17&output=embed',
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

				// Title
				'title' => $settings['title'] ?? [
						'en' => 'Send Us a Message',
						'id' => 'Kirim Pesan ke Kami',
					],
			];
		});

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $data,
		);
	}
}
