<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Landing\Berita\PostResource;
use App\Http\Resources\Landing\Investor\DocumentInvsResource;
use App\Http\Resources\Landing\Investor\FinancialReportResource;
use App\Http\Resources\Landing\Karir\CategoryResource;
use App\Http\Resources\Landing\Karir\KarirResource;
use App\Http\Resources\Landing\Keberlanjutan\TinjauanResource;
use App\Http\Resources\Landing\ProductDetailResource;
use App\Models\Investor\FinancialReport;
use App\Models\JobPosting;
use App\Models\Master\Category;
use App\Models\Master\Product;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class LandingController extends Controller
{
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
	 * Get Karir Landing Page
	 *
	 * @return JsonResponse
	 */
	public function keberlanjutan(string $slug)
	{
		$data = Cache::remember("landing:keberlanjutan:$slug", 1, function () use ($slug) {
			$category = Category::whereHas('parent', function ($query) {
				$query->where('slug', 'keberlanjutan');
			})->where('slug', $slug)->firstOrFail();

			$seo = new SEOData(
				title: $category->name . ' - ' . config('app.name'),
				description: $category->name . ' PT Janu Putra Sejahtera.',
				url: config('app.landing_url') . '/' . request()->path(),
				type: 'website',
				site_name: config('app.name'),
				locale: 'id_ID',
				robots: 'index, follow',
				canonical_url: config('app.landing_url') . '/' . request()->path(),
			);

			if ($slug == 'tinjauan') {
				$tinjauan = Post::select(['content', 'title', 'type_id'])->whereHas('type', function ($query) {
					$query->where('code', 'TPST5');
				})->first();
			} else if ($slug == 'pendekatan-dan-kinerja-manajemen') {
				$pendekatanKinerja = Post::pendekatanKinerja()
					->select(['id', 'content', 'created_by_id', 'published_at', 'slug', 'title'])
					->with(['author', 'featured'])->orderBy('published_at')->get();
			}

			return [
				// Detail
				'detail' => [
					'title' => $category->getTranslations('name'),
				],

				// Hero
				'hero' => [
					'background' => '/images/hero.jpg',
					'title' => [
						'en' => 'Sustainability',
						'id' => 'Keberlanjutan',
					],
				],

				// Pendekatan dan Kinerja
				...($slug == 'pendekatan-dan-kinerja-manajemen' ? [
					'pendekatan_kinerja' => PostResource::collection($pendekatanKinerja),
				] : []),

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

				// Tinjauan
				...($slug == 'tinjauan' ? [
					'tinjauan' => $tinjauan ? new TinjauanResource($tinjauan) : null,
				] : []),
			];
		});

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $data,
		);
	}

	/**
	 * Get Laporan Keberlanjutan List Landing Page
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function keberlanjutanLaporanList(Request $request)
	{
		$query = QueryBuilder::for(
			subject: Category::whereHas('parent', function ($query) {
				$query->where('slug', 'keberlanjutan');
			})->where('slug', 'laporan-keberlanjutan')->firstOrFail()->documentInvs(),
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
}
