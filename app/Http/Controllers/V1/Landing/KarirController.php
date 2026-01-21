<?php

namespace App\Http\Controllers\V1\Landing;

use App\Http\Controllers\Controller;
use App\Http\Resources\Landing\Karir\CategoryResource;
use App\Http\Resources\Landing\Karir\KarirResource;
use App\Models\JobPosting;
use App\Models\Master\Category;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class KarirController extends Controller
{
	/**
	 * Get Karir Landing Page
	 *
	 * @return JsonResponse
	 */
	public function index()
	{
		$data = Cache::remember('landing:karir', 3600, function () {
			$categories = Category::withCount(['jobPostings'])->whereHas('parent', function ($query) {
				$query->where('slug', 'karir');
			})->get();

			$settings = Cache::rememberForever('settings:landing_karir', function () {
				return Setting::where('group', 'landing_karir')->get();
			})->mapWithKeys(function ($item) {
				if ($item->type === 'image') {
					return [$item->key => $item->getFirstMediaUrl($item->key, 'thumb')];
				}

				return [$item->key => $item->value];
			});

			$seo = new SEOData(
				title: $settings['seo_title'] ?? 'Karir - ' . config('app.name'),
				description: $settings['seo_description'] ??
				'Bergabunglah bersama PT Janu Putra Sejahtera. Temukan peluang karir dan kembangkan potensimu bersama kami.',
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
					'desc' => $settings['header_desc'] ?? [
							'en' => 'At PT Janu Putra Sejahtera Tbk, we believe the company?s success is built by professional individuals who uphold integrity, work responsibly, and are committed to continuously growing with the company. Joining us means being part of a company that continues to grow, is long-term oriented, and contributes meaningfully to supporting national food security through a sustainable poultry industry.',
							'id' => 'Di PT Janu Putra Sejahtera Tbk, kami percaya bahwa keberhasilan perusahaan dibangun oleh individu-individu profesional yang menjunjung tinggi integritas, bekerja dengan tanggung jawab, serta memiliki komitmen untuk terus berkembang bersama perusahaan. Bergabung bersama kami berarti menjadi bagian dari perusahaan yang terus bertumbuh, berorientasi jangka panjang, dan berkontribusi nyata dalam mendukung ketahanan pangan nasional melalui industri perunggasan yang berkelanjutan.',
						],
					'title' => $settings['header_title'] ?? [
							'en' => 'Why Join Us?',
							'id' => 'Mengapa Bergabung Bersama Kami?',
						],
				],

				// Hero
				'hero' => [
					'background' => $settings['hero_background'] ?? null,
					'subtitle' => $settings['hero_subtitle'] ?? [
							'en' => 'Careers',
							'id' => 'Karir',
						],
					'title' => $settings['hero_title'] ?? [
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
	public function detail(string $slug)
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
	public function list(Request $request)
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
}
