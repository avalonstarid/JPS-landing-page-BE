<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Landing\Berita\PostResource;
use App\Http\Resources\Landing\Investor\DocumentInvsResource;
use App\Http\Resources\Landing\Keberlanjutan\TinjauanResource;
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
}
