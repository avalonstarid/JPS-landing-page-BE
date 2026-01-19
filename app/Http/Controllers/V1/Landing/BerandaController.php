<?php

namespace App\Http\Controllers\V1\Landing;

use App\Http\Controllers\Controller;
use App\Http\Resources\Landing\FaqResource;
use App\Http\Resources\Landing\ProductResource;
use App\Http\Resources\Landing\StandardResource;
use App\Http\Resources\Landing\TestimonialResource;
use App\Models\Faq;
use App\Models\Master\Product;
use App\Models\Master\Standard;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class BerandaController extends Controller
{
	/**
	 * Get Index Landing Page
	 *
	 * @return JsonResponse
	 */
	public function index()
	{
		$data = Cache::remember('landing:index', 3600, function () {
			$faq = Faq::active()->orderBy('sort_order')->take(4)->get();

			$products = Product::active()->orderBy('sort_order')->take(5)->get();

			$settings = Cache::rememberForever('settings:landing_beranda', function () {
				return Setting::where('group', 'landing_beranda')->get();
			})->mapWithKeys(function ($item) {
				if ($item->type === 'image') {
					return [$item->key => $item->getFirstMediaUrl($item->key, 'thumb')];
				}

				return [$item->key => $item->value];
			});

			$standards = Standard::orderBy('sort_order')->take(6)->get();

			$testimonials = Testimonial::inRandomOrder()->take(5)->get();

			$seo = new SEOData(
				title: $settings['seo_title'] ?? config('app.name') . ' - Perusahaan Peternakan Terintegrasi',
				description: $settings['seo_description'] ??
				'Perusahaan peternakan ayam terintegrasi terkemuka di Indonesia yang menyediakan produk berkualitas dan terjangkau.',
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
						'lead' => $settings['faq_cta_lead'] ?? [
								'en' => 'Still have questions?',
								'id' => 'Masih memiliki pertanyaan?',
							],
						'link' => $settings['faq_cta_link'] ?? '/hubungi-kami',
						'text' => $settings['faq_cta_text'] ?? [
								'en' => 'Contact Us',
								'id' => 'Hubungi Kami',
							],
					],
					'data' => FaqResource::collection($faq),
					'featured' => $settings['faq_featured'],
					'title' => $settings['faq_title'] ?? [
							'en' => 'Frequently Asked Questions',
							'id' => 'Pertanyaan yang sering diajukan',
						],
				],

				// Hero
				'hero' => [
					'background' => $settings['hero_background'],
					'cta' => [
						'link' => $settings['hero_cta_link'] ?? '/tentang-perusahaan',
						'text' => $settings['hero_cta_text'] ?? [
								'en' => 'Learn More',
								'id' => 'Selengkapnya',
							],
					],
					'rotation_words' => $settings['hero_rotation_words'] ?? [
							'en' => explode(',',
								'DOC Parent Stock,DOC Final Stock,Live Chicken,Commercial Eggs,RPA Products'),
							'id' => explode(',',
								'DOC Parent Stock,DOC Final Stock,Ayam Hidup,Telur Komersial,Produk RPA'),
						],
					'subtitle' => $settings['hero_subtitle'] ?? [
							'en' => 'PT Janu Putra Sejahtera Tbk provides',
							'id' => 'PT Janu Putra Sejahtera Tbk menyediakan',
						],
					'title' => $settings['hero_title'] ?? [
							'en' => 'Integrated Poultry Company',
							'id' => 'Perusahaan Peternakan Ayam Terintegrasi',
						],
				],

				// Produk
				'product' => [
					'cta' => [
						'link' => $settings['product_cta_link'] ?? '/hubungi-kami',
						'text' => $settings['product_cta_text'] ?? [
								'en' => 'More information Products',
								'id' => 'Informasi Produk Lebih Lanjut',
							],
					],
					'data' => ProductResource::collection($products),
					'subtitle' => $settings['product_subtitle'] ?? [
							'en' => 'PT Janu Putra Sejahtera Tbk provides various high-quality and affordable poultry products',
							'id' => 'PT Janu Putra Sejahtera Tbk menyediakan berbagai jenis produk ayam berkualitas dan terjangkau',
						],
					'title' => $settings['product_title'] ?? [
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
					'data' => StandardResource::collection($standards),
					'featured' => $settings['standard_featured'] ?? '/images/hero.jpg',
					'subtitle' => $settings['standard_subtitle'] ?? [
							'en' => 'PT Janu Putra Sejahtera Tbk is committed to providing superior and high-quality products',
							'id' => 'PT Janu Putra Sejahtera Tbk berkomitmen untuk menyediakan produk yang unggul dan berkualitas',
						],
					'title' => $settings['standard_title'] ?? [
							'en' => 'Standards We Apply',
							'id' => 'Standar yang Kami Terapkan',
						],
				],

				// Testimonial
				'testimonial' => [
					'background' => $settings['testimonial_background'] ?? '/images/hero.jpg',
					'data' => TestimonialResource::collection($testimonials),
					'title' => $settings['testimonial_title'] ?? [
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
}
