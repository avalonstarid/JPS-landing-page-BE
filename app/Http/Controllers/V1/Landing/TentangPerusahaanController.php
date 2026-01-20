<?php

namespace App\Http\Controllers\V1\Landing;

use App\Http\Controllers\Controller;
use App\Http\Resources\Landing\BusinessLineResource;
use App\Http\Resources\Landing\HistoricalTimelineResource;
use App\Http\Resources\Landing\OrganisasiResource;
use App\Http\Resources\Landing\VisionMissionResource;
use App\Models\BusinessLine;
use App\Models\HistoricalTimeline;
use App\Models\Master\Organisasi;
use App\Models\Master\VisionMission;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class TentangPerusahaanController extends Controller
{
	/**
	 * Get Tentang Perusahaan Landing Page
	 *
	 * @return JsonResponse
	 */
	public function index()
	{
		$data = Cache::remember('landing:tentangPerusahaan', 1, function () {
			$historyTimelines = HistoricalTimeline::orderBy('year')->get();

			$locations = BusinessLine::with(['locations'])->orderBy('sort_order')->get();

			$orga = Organisasi::with(['dewans'])->get();

			$settings = Cache::rememberForever('settings:landing_tentang_perusahaan', function () {
				return Setting::where('group', 'landing_tentang_perusahaan')->get();
			})->mapWithKeys(function ($item) {
				if ($item->type === 'image') {
					return [$item->key => $item->getFirstMediaUrl($item->key, 'thumb')];
				}

				return [$item->key => $item->value];
			});

			$visionMissions = VisionMission::active()->orderBy('sort_order')->take(6)->get();

			$seo = new SEOData(
				title: $settings['seo_title'] ?? 'Tentang Perusahaan - ' . config('app.name'),
				description: $settings['seo_description'] ??
				'PT Janu Putra Sejahtera Tbk tersebar di daerah Yogyakarta dan Jawa Tengah. Perusahaan ayam terintegrasi.',
				url: config('app.landing_url') . '/' . request()->path(),
				type: 'website',
				site_name: config('app.name'),
				locale: 'id_ID',
				robots: 'index, follow',
				canonical_url: config('app.landing_url') . '/' . request()->path(),
			);

			return [
				// Dewan
				'dewan' => [
					'data' => OrganisasiResource::collection($orga),
					'title' => $settings['dewan_title'] ?? [
							'en' => 'Board of Commissioners',
							'id' => 'Dewan Komisaris',
						],
				],

				// Hero
				'hero' => [
					'background' => $settings['hero_background'] ?? null,
					'stat' => $settings['hero_stat'] ?? [
							[
								'icon' => 'building',
								'label' => [
									'en' => 'Facilities',
									'id' => 'Fasilitas',
								],
								'value' => '9+',
							],
							[
								'icon' => 'business',
								'label' => [
									'en' => 'Business Lines',
									'id' => 'Lini Bisnis',
								],
								'value' => '5+',
							],
							[
								'icon' => 'product',
								'label' => [
									'en' => 'Products',
									'id' => 'Produk',
								],
								'value' => '5+',
							],
							[
								'icon' => 'people',
								'label' => [
									'en' => 'Employees',
									'id' => 'Karyawan',
								],
								'value' => '300+',
							],
						],
					'subtitle' => $settings['hero_subtitle'] ?? [
							'en' => 'PT Janu Putra Sejahtera Tbk is spread across Yogyakarta and Central Java',
							'id' => 'PT Janu Putra Sejahtera Tbk tersebar di daerah Yogyakarta dan Jawa Tengah',
						],
					'title' => $settings['hero_title'] ?? [
							'en' => 'Integrated Poultry Company',
							'id' => 'Perusahaan Ayam Terintegrasi',
						],
				],

				// History Timeline
				'history_timeline' => [
					'data' => HistoricalTimelineResource::collection($historyTimelines),
					'title' => $settings['history_timeline_title'] ?? [
							'en' => 'History Timeline',
							'id' => 'Linimasa Sejarah',
						],
				],

				// Lokasi
				'location' => [
					'data' => BusinessLineResource::collection($locations),
					'title' => $settings['location_title'] ?? [
							'en' => 'Business Locations',
							'id' => 'Lokasi Usaha',
						],
				],

				// Organisasi
				'organization' => [
					'featured' => $settings['organization_featured'] ?? null,
					'title' => $settings['organization_title'] ?? [
							'en' => 'Organization Structure',
							'id' => 'Struktur Organisasi',
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

				// Video
				'video' => [
					'link' => $settings['video_link'] ?? 'https://www.youtube.com/embed/qNaC8V1wBDo',
					'title' => $settings['video_title'] ?? [
							'en' => 'PT Janu Putra Sejahtera Tbk in 3 Minutes',
							'id' => 'PT Janu Putra Sejahtera Tbk dalam 3 Menit',
						],
				],

				// Visi & Misi
				'visi_misi' => [
					'data' => VisionMissionResource::collection($visionMissions),
					'featured' => $settings['visi_misi_featured'] ?? null,
					'subtitle' => $settings['visi_misi_subtitle'] ?? [
							'en' => 'To become the leading poultry integrator company in Indonesia that contributes positively to poultry farming and society',
							'id' => 'Menjadi perusahaan ayam integrator terkemuka di Indonesia yang memberikan kontribusi positif bagi peternakan ayam dan masyarakat',
						],
					'title' => $settings['visi_misi_title'] ?? [
							'en' => 'Company Vision and Mission',
							'id' => 'Visi dan Misi Perusahaan',
						],
				],
			];
		});

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $data,
		);
	}
}
