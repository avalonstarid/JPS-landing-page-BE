<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Landing\Berita\PostDetailResource;
use App\Http\Resources\Landing\Berita\PostResource;
use App\Http\Resources\Landing\BusinessLineDetailResource;
use App\Http\Resources\Landing\BusinessLineResource;
use App\Http\Resources\Landing\FaqResource;
use App\Http\Resources\Landing\HistoricalTimelineResource;
use App\Http\Resources\Landing\Investor\DocumentInvsResource;
use App\Http\Resources\Landing\Investor\FinancialReportResource;
use App\Http\Resources\Landing\Karir\CategoryResource;
use App\Http\Resources\Landing\Karir\KarirResource;
use App\Http\Resources\Landing\ProductDetailResource;
use App\Http\Resources\Landing\ProductResource;
use App\Http\Resources\Landing\StandardResource;
use App\Http\Resources\Landing\TestimonialResource;
use App\Http\Resources\Landing\VisionMissionResource;
use App\Models\BusinessLine;
use App\Models\Faq;
use App\Models\HistoricalTimeline;
use App\Models\Investor\FinancialReport;
use App\Models\JobPosting;
use App\Models\Master\Category;
use App\Models\Master\Product;
use App\Models\Master\Standard;
use App\Models\Master\VisionMission;
use App\Models\Post;
use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

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
	 * Get Berita Landing Page
	 *
	 * @return JsonResponse
	 */
	public function berita()
	{
		$data = Cache::remember('landing:berita', 3600, function () {
			$seo = new SEOData(
				title: 'Berita - ' . config('app.name'),
				description: 'Kabar terbaru seputar aktivitas, inovasi, dan pembaruan dari PT Janu Putra Sejahtera.',
				url: config('app.landing_url') . '/' . request()->path(),
				type: 'website',
				site_name: config('app.name'),
				locale: 'id_ID',
				robots: 'index, follow',
				canonical_url: config('app.landing_url') . '/' . request()->path(),
			);

			$beritaPopular = Post::news()->with(['author', 'featured', 'seo'])
				->select(['id', 'created_by_id', 'published_at', 'slug', 'title'])
				->inRandomOrder()
				->take(4)
				->get();

			$beritaFeatured = Post::news()->with(['author', 'featured', 'seo'])
				->select(['id', 'created_by_id', 'published_at', 'slug', 'title'])
				->inRandomOrder()
				->take(4)
				->get();

			return [
				// Featured
				'featured' => [
					'data' => PostResource::collection($beritaFeatured),
				],

				// News
				'news' => [
					'subtitle' => [
						'en' => 'Fresh updates curated for you.',
						'id' => 'Kumpulan update terbaru yang dirangkum untuk Anda.',
					],
					'title' => [
						'en' => 'Latest News',
						'id' => 'Berita Terbaru',
					],
				],

				// Popular
				'popular' => [
					'data' => PostResource::collection($beritaPopular),
					'subtitle' => [
						'en' => 'Most-read highlights from our partners and customers.',
						'id' => 'Sorotan kabar yang paling banyak dibaca oleh pelanggan dan mitra kami.',
					],
					'title' => [
						'en' => 'Popular News',
						'id' => 'Berita Terpopuler',
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
	 * Get Berita Detail Landing Page
	 *
	 * @param string $slug
	 *
	 * @return JsonResponse
	 */
	public function beritaDetail(string $slug)
	{
		$data = Cache::remember("landing:beritaDetail:$slug", 3600, function () use ($slug) {
			$post = Post::news()->with(['author', 'featured', 'seo'])->where('slug', $slug)->firstOrFail();

			$seo = new SEOData(
				title: $post->seo?->title ?? $post->title . ' - ' . config('app.name'),
				description: $post->seo?->description ?? Str::limit(strip_tags($post->content), 160, ''),
				author: $post->seo?->author ?? null,
				image: $post->seo?->image,
				url: config('app.landing_url') . '/' . request()->path(),
				published_time: Carbon::parse($post->published_at),
				type: 'website',
				site_name: config('app.name'),
				locale: 'id_ID',
				robots: 'index, follow',
				canonical_url: config('app.landing_url') . '/' . request()->path(),
			);

			return [
				'post' => new PostDetailResource($post),

				// SEO
				'seo' => [
					'title' => $seo->title,
					'description' => $seo->description,
					'author' => $seo->author,
					'image' => $seo->image,
					'url' => $seo->url,
					'published_time' => $seo->published_time,
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
	 * Get Berita List Landing Page
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function beritaList(Request $request)
	{
		$query = QueryBuilder::for(
			subject: Post::news()->select(['id', 'created_by_id', 'published_at', 'slug', 'title']),
		)->allowedSorts(
			sorts: [
				'published_at',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$searchTerm = '%' . strtolower($value) . '%';

					$q->where(function ($subQuery) use ($searchTerm) {
						$subQuery->orWhereRaw('LOWER(title) LIKE ?', [$searchTerm]);
					});
				}),
			],
		)->with(['author', 'featured', 'seo'])->defaultSort('-published_at');

		$data = $query->fastPaginate(10)->withQueryString();

		return $this->responseNew(
			message: 'Berhasil mengambil data.',
			data: PostResource::collection($data),
		);
	}

	/**
	 * Get Berita Landing Page
	 *
	 * @return JsonResponse
	 */
	public function blog()
	{
		$data = Cache::remember('landing:blog', 1, function () {
			$seo = new SEOData(
				title: 'Blog - ' . config('app.name'),
				description: 'Artikel blog terbaru dari PT Janu Putra Sejahtera.',
				url: config('app.landing_url') . '/' . request()->path(),
				type: 'website',
				site_name: config('app.name'),
				locale: 'id_ID',
				robots: 'index, follow',
				canonical_url: config('app.landing_url') . '/' . request()->path(),
			);

			$blogPopular = Post::blog()->with(['author', 'featured', 'seo'])
				->select(['id', 'created_by_id', 'published_at', 'slug', 'title'])
				->inRandomOrder()
				->take(5)
				->get();

			$blogFeatured = Post::blog()->with(['author', 'featured', 'seo'])
				->select(['id', 'created_by_id', 'published_at', 'slug', 'title'])
				->inRandomOrder()
				->take(4)
				->get();

			return [
				// Featured
				'featured' => [
					'data' => PostResource::collection($blogFeatured),
				],

				// News
				'news' => [
					'subtitle' => [
						'en' => 'Fresh updates curated for you.',
						'id' => 'Kumpulan update terbaru yang dirangkum untuk Anda.',
					],
					'title' => [
						'en' => 'Latest Blog',
						'id' => 'Blog Terbaru',
					],
				],

				// Popular
				'popular' => [
					'data' => PostResource::collection($blogPopular),
					'subtitle' => [
						'en' => 'Most-read highlights from our partners and customers.',
						'id' => 'Sorotan kabar yang paling banyak dibaca oleh pelanggan dan mitra kami.',
					],
					'title' => [
						'en' => 'Popular Blog',
						'id' => 'Blog Terpopuler',
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
	 * Get Berita Detail Landing Page
	 *
	 * @param string $slug
	 *
	 * @return JsonResponse
	 */
	public function blogDetail(string $slug)
	{
		$data = Cache::remember("landing:blogDetail:$slug", 1, function () use ($slug) {
			$post = Post::blog()->with(['author', 'featured', 'seo'])->where('slug', $slug)->firstOrFail();

			$seo = new SEOData(
				title: $post->seo?->title ?? $post->title . ' - ' . config('app.name'),
				description: $post->seo?->description ?? Str::limit(strip_tags($post->content), 160, ''),
				author: $post->seo?->author ?? null,
				image: $post->seo?->image,
				url: config('app.landing_url') . '/' . request()->path(),
				published_time: Carbon::parse($post->published_at),
				type: 'website',
				site_name: config('app.name'),
				locale: 'id_ID',
				robots: 'index, follow',
				canonical_url: config('app.landing_url') . '/' . request()->path(),
			);

			return [
				'post' => new PostDetailResource($post),

				// SEO
				'seo' => [
					'title' => $seo->title,
					'description' => $seo->description,
					'author' => $seo->author,
					'image' => $seo->image,
					'url' => $seo->url,
					'published_time' => $seo->published_time,
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
	 * Get Berita List Landing Page
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function blogList(Request $request)
	{
		$query = QueryBuilder::for(
			subject: Post::blog()->select(['id', 'created_by_id', 'published_at', 'slug', 'title']),
		)->allowedSorts(
			sorts: [
				'published_at',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$searchTerm = '%' . strtolower($value) . '%';

					$q->where(function ($subQuery) use ($searchTerm) {
						$subQuery->orWhereRaw('LOWER(title) LIKE ?', [$searchTerm]);
					});
				}),
			],
		)->with(['author', 'featured', 'seo'])->defaultSort('-published_at');

		$data = $query->fastPaginate(10)->withQueryString();

		return $this->responseNew(
			message: 'Berhasil mengambil data.',
			data: PostResource::collection($data),
		);
	}

	/**
	 * Get Hubungi Kami Landing Page
	 *
	 * @return JsonResponse
	 */
	public function hubungiKami()
	{
		$data = Cache::remember('landing:karir', 3600, function () {
			$seo = new SEOData(
				title: 'Hubungi Kami - ' . config('app.name'),
				description: 'Untuk informasi lebih lanjut, kemitraan, pertanyaan seputar produk.',
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
					'data' => [
						[
							'icon' => 'email',
							'icon_custom' => true,
							'key' => 'email',
							'link' => 'mailto:marketing@jpsejahtera.co.id',
							'value' => 'marketing@jpsejahtera.co.id',
						],
						[
							'icon' => 'instagram',
							'icon_custom' => true,
							'key' => 'instagram',
							'link' => 'https://www.instagram.com/januputrasejahtera',
							'value' => 'januputrasejahtera',
						],
						[
							'icon' => 'linkedin',
							'icon_custom' => true,
							'key' => 'linkedin',
							'link' => 'https://www.linkedin.com/company/janu-putra-group/',
							'value' => 'PT Janu Putra Sejahtera',
						],
						[
							'icon' => 'whatsapp',
							'icon_custom' => true,
							'key' => 'whatsapp',
							'link' => 'https://wa.me/6287885483781',
							'value' => '0878 8548 3781',
						],
					],
					'title' => [
						'en' => 'More Contact',
						'id' => 'Kontak Lebih Lanjut',
					],
				],

				// Hero
				'hero' => [
					'background' => '/images/hero.jpg',
					'subtitle' => [
						'en' => 'For further information, partnerships, product inquiries',
						'id' => 'Untuk informasi lebih lanjut, kemitraan, pertanyaan seputar produk',
					],
					'title' => [
						'en' => 'Contact Us',
						'id' => 'Hubungi Kami',
					],
				],

				// Map
				'map' => [
					'address' => 'Ruko Casa Grande No.35, Jl. Ringroad Utara, Maguwoharjo, Depok, Sleman, Daerah Istimewa Yogyakarta',
					'link' => 'https://www.google.com/maps?q=-7.763563,110.421265&z=17&output=embed',
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
				'title' => [
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

	/**
	 * Get Karir Landing Page
	 *
	 * @return JsonResponse
	 */
	public function karir()
	{
		$data = Cache::remember('landing:karir', 3600, function () {
			$categories = Category::withCount(['jobPostings'])->whereHas('parent', function ($query) {
				$query->where('slug', 'karir');
			})->get();

			$seo = new SEOData(
				title: 'Karir - ' . config('app.name'),
				description: 'Bergabunglah bersama PT Janu Putra Sejahtera. Temukan peluang karir dan kembangkan potensimu bersama kami.',
				url: config('app.landing_url') . '/' . request()->path(),
				type: 'website',
				site_name: config('app.name'),
				locale: 'id_ID',
				robots: 'index, follow',
				canonical_url: config('app.landing_url') . '/' . request()->path(),
			);

			return [
				// Categories
				'categories' => [
					'data' => CategoryResource::collection($categories),
				],

				// Header
				'header' => [
					'desc' => [
						'en' => ['At PT Janu Putra Sejahtera Tbk, we believe the company?s success is built by professional individuals who uphold integrity, work responsibly, and are committed to continuously growing with the company. Joining us means being part of a company that continues to grow, is long-term oriented, and contributes meaningfully to supporting national food security through a sustainable poultry industry.'],
						'id' => 'Di PT Janu Putra Sejahtera Tbk, kami percaya bahwa keberhasilan perusahaan dibangun oleh individu-individu profesional yang menjunjung tinggi integritas, bekerja dengan tanggung jawab, serta memiliki komitmen untuk terus berkembang bersama perusahaan. Bergabung bersama kami berarti menjadi bagian dari perusahaan yang terus bertumbuh, berorientasi jangka panjang, dan berkontribusi nyata dalam mendukung ketahanan pangan nasional melalui industri perunggasan yang berkelanjutan.',
					],
					'title' => [
						'en' => 'Why Join Us?',
						'id' => 'Mengapa Bergabung Bersama Kami?',
					],
				],

				// Hero
				'hero' => [
					'background' => '/images/hero.jpg',
					'title' => [
						'en' => 'Careers',
						'id' => 'Karir',
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
	 * Get Karir Detail Landing Page
	 *
	 * @param string $slug
	 *
	 * @return JsonResponse
	 */
	public function karirDetail(string $slug)
	{
		$data = Cache::remember("landing:karirDetail:$slug", 3600, function () use ($slug) {
			$jobPosting = JobPosting::with(['category'])->where('slug', $slug)->firstOrFail();

			return new KarirResource($jobPosting);
		});

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $data,
		);
	}

	/**
	 * Get Karir List Landing Page
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function karirList(Request $request)
	{
		$query = QueryBuilder::for(
			subject: JobPosting::select(['id', 'address', 'desc_short', 'location', 'published_at', 'slug', 'title',
				'category_id']),
		)->allowedSorts(
			sorts: [
				'published_at',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$searchTerm = '%' . strtolower($value) . '%';

					$q->where(function ($subQuery) use ($searchTerm) {
						$subQuery->orWhereRaw('LOWER(address) LIKE ?', [$searchTerm]);
						$subQuery->orWhereRaw('LOWER(location) LIKE ?', [$searchTerm]);
						$subQuery->orWhereRaw('LOWER(title) LIKE ?', [$searchTerm]);
					});
				}),
			],
		)->with(['category'])->defaultSort('-published_at');

		$data = $query->fastPaginate(10)->withQueryString();

		return $this->responseNew(
			message: 'Berhasil mengambil data.',
			data: KarirResource::collection($data),
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
	 * Get Lini Bisnis Landing Page
	 *
	 * @return JsonResponse
	 */
	public function relasiInvestor(string $slug)
	{
		$data = Cache::remember("landing:relasiInvestor:$slug", 3600, function () use ($slug) {
			$category = Category::whereHas('parent', function ($query) {
				$query->where('slug', 'relasi-investor');
			})->where('slug', $slug)->firstOrFail();

			$seo = new SEOData(
				title: $category->name . ' - ' . config('app.name'),
				description: 'PT Janu Putra Sejahtera - Perusahaan peternakan ayam terintegrasi terkemuka di Indonesia yang menyediakan produk berkualitas dan terjangkau.',
				url: config('app.landing_url') . '/' . request()->path(),
				type: 'website',
				site_name: config('app.name'),
				locale: 'id_ID',
				robots: 'index, follow',
				canonical_url: config('app.landing_url') . '/' . request()->path(),
			);

			return [
				// Detail
				'detail' => [
					'title' => $category->getTranslations('name'),
				],

				// Financial Report
				'financial_report' => ($slug == 'laporan-keuangan') ? [
					'data' => FinancialReportResource::collection(
						FinancialReport::orderByDesc('tahun')->take(5)->get(),
					),
				] : null,

				// Hero
				'hero' => [
					'background' => '/images/hero.jpg',
					'title' => [
						'en' => 'Our Relations with Investors',
						'id' => 'Relasi Investor',
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
	 * Get Relasi Investor List Landing Page
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function relasiInvestorLapKeu(Request $request)
	{
		$query = QueryBuilder::for(
			subject: FinancialReport::class,
		)->allowedSorts(
			sorts: [
				'tahun',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$searchTerm = '%' . strtolower($value) . '%';

					$q->where(function ($subQuery) use ($searchTerm) {
						$subQuery->orWhereRaw('LOWER(title) LIKE ?', [$searchTerm]);
					});
				}),
			],
		)->with(['document'])->defaultSort('-tahun');

		$data = $query->fastPaginate(10)->withQueryString();

		return $this->responseNew(
			message: 'Berhasil mengambil data.',
			data: FinancialReportResource::collection($data),
		);
	}

	/**
	 * Get Relasi Investor List Landing Page
	 *
	 * @param Request $request
	 * @param string  $slug
	 *
	 * @return JsonResponse
	 */
	public function relasiInvestorList(Request $request, string $slug)
	{
		$query = QueryBuilder::for(
			subject: Category::whereHas('parent', function ($query) {
				$query->where('slug', 'relasi-investor');
			})->where('slug', $slug)->firstOrFail()->documentInvs(),
		)->allowedSorts(
			sorts: [
				'created_at',
			],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$searchTerm = '%' . strtolower($value) . '%';

					$q->where(function ($subQuery) use ($searchTerm) {
						$subQuery->orWhereRaw('LOWER(title) LIKE ?', [$searchTerm]);
					});
				}),
			],
		)->with(['document', 'featured'])->defaultSort('-created_at');

		$data = $query->fastPaginate(10)->withQueryString();

		return $this->responseNew(
			message: 'Berhasil mengambil data.',
			data: DocumentInvsResource::collection($data),
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
