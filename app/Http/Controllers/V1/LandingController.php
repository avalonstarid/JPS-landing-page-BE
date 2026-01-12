<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Landing\FaqResource;
use App\Http\Resources\Landing\ProductResource;
use App\Http\Resources\Landing\StandardResource;
use App\Http\Resources\Landing\TestimonialResource;
use App\Models\Faq;
use App\Models\Master\Product;
use App\Models\Master\Standard;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class LandingController extends Controller
{
	public function index()
	{
		$data = Cache::remember('landing_index', 3600, function () {
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
				'seo' => new SEOData(
					title: 'PT Janu Putra Sejahtera - Perusahaan Peternakan Terintegrasi',
					description: 'Perusahaan peternakan ayam terintegrasi terkemuka di Indonesia yang menyediakan produk berkualitas dan terjangkau.',
					url: config('app.frontend_url'),
					type: 'website',
					site_name: 'PT Janu Putra Sejahtera',
					locale: 'id_ID',
					robots: 'index, follow',
					canonical_url: config('app.frontend_url'),
				),

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
}
