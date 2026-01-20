<?php

namespace App\Http\Controllers\V1\Landing;

use App\Http\Controllers\Controller;
use App\Http\Resources\Landing\BusinessLineDetailResource;
use App\Models\BusinessLine;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class LiniBisnisController extends Controller
{
	/**
	 * Get Lini Bisnis Landing Page
	 *
	 * @return JsonResponse
	 */
	public function index(string $slug)
	{
		$data = Cache::remember("landing:liniBisnis:$slug", 3600, function () use ($slug) {
			$businessLine = BusinessLine::with(['images'])->where('slug', $slug)->firstOrFail();

			$settings = Cache::rememberForever('settings:landing_lini_bisnis', function () {
				return Setting::where('group', 'landing_lini_bisnis')->get();
			})->mapWithKeys(function ($item) {
				if ($item->type === 'image') {
					return [$item->key => $item->getFirstMediaUrl($item->key, 'thumb')];
				}

				return [$item->key => $item->value];
			});

			$seo = new SEOData(
				title: $businessLine->title . ' - ' . config('app.name'),
				description: $businessLine->desc,
				url: config('app.landing_url') . '/' . request()->path(),
				type: 'website',
				site_name: config('app.name'),
				locale: 'id_ID',
				robots: 'index, follow',
				canonical_url: config('app.landing_url') . '/' . request()->path(),
			);

			return [
				// Business Line
				'business_line' => [
					'data' => new BusinessLineDetailResource($businessLine),
					'title' => $settings['business_line_title'] ?? [
							'en' => 'Business Lines',
							'id' => 'Lini Bisnis',
						],
				],

				// CTA
				'cta' => [
					'text' => $settings['cta_text'] ?? [
							'en' => 'Farm Information',
							'id' => 'Informasi Peternakan',
						],
				],

				// Hero
				'hero' => [
					'background' => $settings['hero_background'] ?? null,
					'subtitle' => $settings['hero_subtitle'] ?? [
							'en' => 'Our Business',
							'id' => 'Bisnis Kami',
						],
					'title' => $settings['hero_title'] ?? [
							'en' => 'Our Business',
							'id' => 'Bisnis Kami',
						],
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
