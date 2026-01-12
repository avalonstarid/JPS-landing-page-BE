<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Landing\BusinessLineResource;
use App\Http\Resources\Landing\FaqResource;
use App\Http\Resources\Landing\HistoricalTimelineResource;
use App\Http\Resources\Landing\ProductResource;
use App\Http\Resources\Landing\StandardResource;
use App\Http\Resources\Landing\TestimonialResource;
use App\Http\Resources\Landing\VisionMissionResource;
use App\Models\BusinessLine;
use App\Models\Faq;
use App\Models\HistoricalTimeline;
use App\Models\Master\Product;
use App\Models\Master\Standard;
use App\Models\Master\VisionMission;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class LandingController extends Controller
{
	/**
	 * Get Index Landing Page
	 *
	 * @return JsonResponse
	 */
	public function index()
	{
		$seo = new SEOData(
			title: 'PT Janu Putra Sejahtera - Perusahaan Peternakan Terintegrasi',
			description: 'Perusahaan peternakan ayam terintegrasi terkemuka di Indonesia yang menyediakan produk berkualitas dan terjangkau.',
			url: config('app.frontend_url'),
			type: 'website',
			site_name: 'PT Janu Putra Sejahtera',
			locale: 'id_ID',
			robots: 'index, follow',
			canonical_url: config('app.frontend_url'),
		);

		$data = Cache::remember('landing:index', 3600, function () use ($seo) {
			return [
				// FAQ
				'faq' => [
					'cta' => [
						'lead' => [
							'en' => 'Still have questions?',
							'id' => 'Masih memiliki pertanyaan?',
						],
						'link' => '/hubungi-kami',
						'text' => [
							'en' => 'Contact Us',
							'id' => 'Hubungi Kami',
						],
					],
					'data' => FaqResource::collection(
						Faq::active()->orderBy('sort_order')->take(4)->get(),
					),
					'featured' => '/images/hero.jpg',
					'title' => [
						'en' => 'Frequently Asked Questions',
						'id' => 'Pertanyaan yang sering diajukan',
					],
				],

				// Hero
				'hero' => [
					'background' => '/images/hero.jpg',
					'cta' => [
						'link' => '/tentang-perusahaan',
						'text' => [
							'en' => 'Learn More',
							'id' => 'Selengkapnya',
						],
					],
					'rotation_words' => [
						'en' => explode(',',
							'DOC Parent Stock,DOC Final Stock,Live Chicken,Commercial Eggs,RPA Products'),
						'id' => explode(',', 'DOC Parent Stock,DOC Final Stock,Ayam Hidup,Telur Komersial,Produk RPA'),
					],
					'subtitle' => [
						'en' => 'PT Janu Putra Sejahtera Tbk provides',
						'id' => 'PT Janu Putra Sejahtera Tbk menyediakan',
					],
					'title' => [
						'en' => 'Integrated Poultry Company',
						'id' => 'Perusahaan Peternakan Ayam Terintegrasi',
					],
				],

				// Produk
				'product' => [
					'cta' => [
						'link' => '/hubungi-kami',
						'text' => [
							'en' => 'More information Products',
							'id' => 'Informasi Produk Lebih Lanjut',
						],
					],
					'data' => ProductResource::collection(
						Product::active()->orderBy('sort_order')->take(5)->get(),
					),
					'subtitle' => [
						'en' => 'PT Janu Putra Sejahtera Tbk provides various high-quality and affordable poultry products',
						'id' => 'PT Janu Putra Sejahtera Tbk menyediakan berbagai jenis produk ayam berkualitas dan terjangkau',
					],
					'title' => [
						'en' => 'Our Commercial Poultry Products',
						'id' => 'Lini Produk Komersial Kami',
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

				// Standard
				'standard' => [
					'data' => StandardResource::collection(
						Standard::orderBy('sort_order')->take(6)->get(),
					),
					'featured' => '/images/hero.jpg',
					'subtitle' => [
						'en' => 'PT Janu Putra Sejahtera Tbk is committed to providing superior and high-quality products',
						'id' => 'PT Janu Putra Sejahtera Tbk berkomitmen untuk menyediakan produk yang unggul dan berkualitas',
					],
					'title' => [
						'en' => 'Standards We Apply',
						'id' => 'Standar yang Kami Terapkan',
					],
				],

				// Testimonial
				'testimonial' => [
					'background' => '/images/hero.jpg',
					'data' => TestimonialResource::collection(
						Testimonial::inRandomOrder()->take(5)->get(),
					),
					'title' => [
						'en' => 'Customer Words',
						'id' => 'Kata Konsumen',
					],
				],
			];
		});

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $data,
		);
	}

	/**
	 * Get Tentang Perusahaan Landing Page
	 *
	 * @return JsonResponse
	 */
	public function tentangPerusahaan()
	{
		$seo = new SEOData(
			title: 'PT Janu Putra Sejahtera - Perusahaan Peternakan Terintegrasi',
			description: 'Perusahaan peternakan ayam terintegrasi terkemuka di Indonesia yang menyediakan produk berkualitas dan terjangkau.',
			url: config('app.frontend_url'),
			type: 'website',
			site_name: 'PT Janu Putra Sejahtera',
			locale: 'id_ID',
			robots: 'index, follow',
			canonical_url: config('app.frontend_url'),
		);

		$data = Cache::remember('landing:tentangPerusahaan', 3600, function () use ($seo) {
			return [
				// Dewan
				'dewan' => [
					'data' => [
						[
							'key' => 'komisaris',
							'label' => [
								'en' => 'Board of Commissioners',
								'id' => 'Dewan Komisaris',
							],
							'people' => [
								[
									'name' => 'Singgih Januatmoko',
									'position' => [
										'en' => 'President Commissioner',
										'id' => 'Komisaris Utama',
									],
									'photo' => '/images/hero.jpg',
								],
								[
									'name' => 'Fadhl Muhammad Firdaus',
									'position' => [
										'en' => 'Commissioner',
										'id' => 'Komisaris',
									],
									'photo' => '/images/hero.jpg',
								],
							],
						],
						[
							'key' => 'direksi',
							'label' => [
								'en' => 'Board of Directors',
								'id' => 'Dewan Direksi',
							],
							'people' => [
								[
									'name' => 'Sri Mulyani',
									'position' => [
										'en' => 'President Director',
										'id' => 'Direktur Utama',
									],
									'photo' => '/images/hero.jpg',
								],
							],
						],
					],
					'title' => [
						'en' => 'Board of Commissioners',
						'id' => 'Dewan Komisaris',
					],
				],

				// Hero
				'hero' => [
					'background' => '/images/hero.jpg',
					'stat' => [
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
					'subtitle' => [
						'en' => 'PT Janu Putra Sejahtera Tbk is spread across Yogyakarta and Central Java',
						'id' => 'PT Janu Putra Sejahtera Tbk tersebar di daerah Yogyakarta dan Jawa Tengah',
					],
					'title' => [
						'en' => 'Integrated Poultry Company',
						'id' => 'Perusahaan Ayam Terintegrasi',
					],
				],

				// History Timeline
				'history_timeline' => [
					'data' => HistoricalTimelineResource::collection(
						HistoricalTimeline::orderBy('year')->get(),
					),
				],

				// Lokasi
				'location' => [
					'data' => BusinessLineResource::collection(
						BusinessLine::with(['locations'])->orderBy('sort_order')->get(),
					),
					'title' => [
						'en' => 'Organization Structure',
						'id' => 'Struktur Organisasi',
					],
				],

				// Organisasi
				'organization' => [
					'featured' => '/images/hero.jpg',
					'title' => [
						'en' => 'Business Locations',
						'id' => 'Lokasi Usaha',
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
					'link' => 'https://www.youtube.com/embed/qNaC8V1wBDo',
					'title' => [
						'en' => 'PT Janu Putra Sejahtera Tbk in 3 Minutes',
						'id' => 'PT Janu Putra Sejahtera Tbk dalam 3 Menit',
					],
				],

				// Visi & Misi
				'visi_misi' => [
					'data' => VisionMissionResource::collection(
						VisionMission::active()->orderBy('sort_order')->take(6)->get(),
					),
					'featured' => '/images/hero.jpg',
					'subtitle' => [
						'en' => 'To become the leading poultry integrator company in Indonesia that contributes positively to poultry farming and society',
						'id' => 'Menjadi perusahaan ayam integrator terkemuka di Indonesia yang memberikan kontribusi positif bagi peternakan ayam dan masyarakat',
					],
					'title' => [
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
