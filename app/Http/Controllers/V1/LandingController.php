<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Landing\BusinessLineDetailResource;
use App\Http\Resources\Landing\BusinessLineResource;
use App\Http\Resources\Landing\FaqResource;
use App\Http\Resources\Landing\HistoricalTimelineResource;
use App\Http\Resources\Landing\ProductDetailResource;
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
		$data = Cache::remember('landing:index', 3600, function () {
			$seo = new SEOData(
				title: config('app.name') . ' - Perusahaan Peternakan Terintegrasi',
				description: 'Perusahaan peternakan ayam terintegrasi terkemuka di Indonesia yang menyediakan produk berkualitas dan terjangkau.',
				url: config('app.landing_url'),
				type: 'website',
				site_name: config('app.name'),
				locale: 'id_ID',
				robots: 'index, follow',
				canonical_url: config('app.landing_url'),
			);

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
	 * Get Lini Bisnis Landing Page
	 *
	 * @return JsonResponse
	 */
	public function liniBisnis(string $slug)
	{
		$data = Cache::remember("landing:liniBisnis:$slug", 3600, function () use ($slug) {
			$businessLine = BusinessLine::with(['images'])->where('slug', $slug)->firstOrFail();

			$seo = new SEOData(
				title: $businessLine->title . ' - ' . config('app.name'),
				description: 'Perusahaan peternakan ayam terintegrasi terkemuka di Indonesia yang menyediakan produk berkualitas dan terjangkau.',
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
					'title' => [
						'en' => 'Business Lines',
						'id' => 'Lini Bisnis',
					],
				],

				// CTA
				'cta' => [
					'text' => [
						'en' => 'Farm Information',
						'id' => 'Informasi Peternakan',
					],
				],

				// Hero
				'hero' => [
					'background' => '/images/hero.jpg',
					'title' => [
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

	/**
	 * Get Produk Landing Page
	 *
	 * @return JsonResponse
	 */
	public function produk()
	{
		$data = Cache::remember('landing:produk', 3600, function () {
			$seo = new SEOData(
				title: 'Produk - ' . config('app.name'),
				description: 'Rangkaian produk unggas kami memenuhi standar kualitas, keamanan, dan konsistensi.',
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
							'en' => 'Last updated 29 November 2025',
							'id' => 'Terakhir diupdate 29 November 2025',
						],
						'products' => [
							'day-old-chick-doc-parent-stock' => [
								'stat' => 1000,
								'title' => [
									'en' => 'DOC Parent Stock',
									'id' => 'DOC Parent Stock',
								],
							],
							'day-old-chick-doc-final-stock' => [
								'stat' => 1500,
								'title' => [
									'en' => 'DOC Final Stock',
									'id' => 'DOC Final Stock',
								],
							],
							'ayam-hidup-broiler-komersial' => [
								'stat' => 900,
								'title' => [
									'en' => 'Ayam Hidup Broiler Komersial',
									'id' => 'Ayam Hidup Broiler Komersial',
								],
							],
							'telur-komersial' => [
								'stat' => 2300,
								'title' => [
									'en' => 'Commercial Eggs',
									'id' => 'Telur Komersial',
								],
							],
							'produk-rpa' => [
								'stat' => 1800,
								'title' => [
									'en' => 'RPA Products',
									'id' => 'Produk RPA',
								],
							],
						],
						'title' => [
							'en' => 'Stock Update',
							'id' => 'Stock Update',
						],
					],
					'title' => [
						'en' => 'Our Commercial Products',
						'id' => 'Produk Komersial Kami',
					],
				],

				// Hero
				'hero' => [
					'background' => '/images/hero.jpg',
					'subtitle' => [
						'en' => 'We provide a range of poultry products that meet quality, safety, and consistency standards',
						'id' => 'Kami menyediakan rangkaian produk unggas yang memenuhi standar kualitas, keamanan, dan konsisten',
					],
					'title' => [
						'en' => 'Integrated Poultry Solutions',
						'id' => 'Solusi Perunggasan Terintegrasi',
					],
				],

				// Produk
				'product' => [
					'data' => ProductDetailResource::collection(
						Product::with(['images'])->active()->orderBy('sort_order')->get(),
					),
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

	/**
	 * Get Tentang Perusahaan Landing Page
	 *
	 * @return JsonResponse
	 */
	public function tentangPerusahaan()
	{
		$data = Cache::remember('landing:tentangPerusahaan', 3600, function () {
			$seo = new SEOData(
				title: 'Tentang Perusahaan - ' . config('app.name'),
				description: 'PT Janu Putra Sejahtera Tbk tersebar di daerah Yogyakarta dan Jawa Tengah. Perusahaan ayam terintegrasi.',
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
