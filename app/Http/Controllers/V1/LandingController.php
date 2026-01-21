<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Landing\Berita\PostResource;
use App\Http\Resources\Landing\Investor\DocumentInvsResource;
use App\Http\Resources\Landing\Investor\FinancialReportResource;
use App\Http\Resources\Landing\Keberlanjutan\TinjauanResource;
use App\Models\Investor\FinancialReport;
use App\Models\Master\Category;
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
