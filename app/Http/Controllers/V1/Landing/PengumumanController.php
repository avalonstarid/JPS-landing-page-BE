<?php

namespace App\Http\Controllers\V1\Landing;

use App\Http\Controllers\Controller;
use App\Http\Resources\Landing\Berita\PostDetailResource;
use App\Http\Resources\Landing\Berita\PostResource;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PengumumanController extends Controller
{
	/**
	 * Get Berita Landing Page
	 *
	 * @return JsonResponse
	 */
	public function index()
	{
		$data = Cache::remember('landing:pengumuman', 3600, function () {
			$settings = Cache::rememberForever('settings:landing_pengumuman', function () {
				return Setting::where('group', 'landing_pengumuman')->get();
			})->mapWithKeys(function ($item) {
				if ($item->type === 'image') {
					return [$item->key => $item->getFirstMediaUrl($item->key, 'thumb')];
				}

				return [$item->key => $item->value];
			});

			$seo = new SEOData(
				title: $settings['seo_title'] ?? 'Pengumuman - ' . config('app.name'),
				description: $settings['seo_description'] ?? 'Pengumuman terbaru dari PT Janu Putra Sejahtera.',
				url: config('app.landing_url') . '/' . request()->path(),
				type: 'website',
				site_name: config('app.name'),
				locale: 'id_ID',
				robots: 'index, follow',
				canonical_url: config('app.landing_url') . '/' . request()->path(),
			);

			return [
				// Hero
				'hero' => [
					'background' => $settings['hero_background'] ?? null,
					'subtitle' => $settings['hero_subtitle'] ?? [
							'en' => 'Announcement',
							'id' => 'Pengumuman',
						],
					'title' => $settings['hero_title'] ?? [
							'en' => 'Latest information from PT Janu Putra Sejahtera',
							'id' => 'Informasi terbaru dari PT Janu Putra Sejahtera',
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
	public function detail(string $slug)
	{
		$data = Cache::remember("landing:pengumumanDetail:$slug", 3600, function () use ($slug) {
			$post = Post::announcement()->with(['author', 'featured', 'seo'])->where('slug', $slug)->firstOrFail();

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
	public function list(Request $request)
	{
		$query = QueryBuilder::for(
			subject: Post::announcement()->select(['id', 'created_by_id', 'published_at', 'slug', 'title']),
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
}
