<?php

namespace App\Console\Commands;

use App\Models\JobPosting;
use App\Models\Post;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'sitemap:generate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate the sitemap.xml file';

	/**
	 * Execute the console command.
	 */
	public function handle(): void
	{
		$this->info('Starting sitemap generation...');

		if (!is_dir(public_path('sitemaps'))) {
			mkdir(public_path('sitemaps'), 0755, true);
		}

		$this->info('Generating static pages...');
		Sitemap::create()
			->add($this->frontendUrl('/')->setPriority(1.0))
			->add($this->frontendUrl('/tentang-perusahaan')->setPriority(0.8))
			->add($this->frontendUrl('/hubungi-kami')->setPriority(0.8))
			->add($this->frontendUrl('/produk')->setPriority(0.7))
			->add($this->frontendUrl('/berita')->setPriority(0.7)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY))
			->add($this->frontendUrl('/blog')->setPriority(0.7)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY))
			->add($this->frontendUrl('/pengumuman')->setPriority(0.7)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY))
			->add($this->frontendUrl('/karir')->setPriority(0.7)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY))
			->writeToFile(public_path('sitemaps/sitemap_page.xml'));

		$this->info('Generating berita...');
		$beritaSitemap = Sitemap::create();
		Post::with(['type'])->news()->published()->orderByDesc('published_at')->cursor()
			->each(function ($post) use ($beritaSitemap) {
				$beritaSitemap->add(
					$this->frontendUrl("/berita/detail/{$post->slug}")
						->setLastModificationDate($post->updated_at)
						->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY),
				);
			});
		$beritaSitemap->writeToFile(public_path('sitemaps/sitemap_berita.xml'));

		$this->info('Generating blog...');
		$blogSitemap = Sitemap::create();
		Post::with(['type'])->blog()->published()->orderByDesc('published_at')->cursor()
			->each(function ($post) use ($blogSitemap) {
				$blogSitemap->add(
					$this->frontendUrl("/blog/detail/{$post->slug}")
						->setLastModificationDate($post->updated_at)
						->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY),
				);
			});
		$blogSitemap->writeToFile(public_path('sitemaps/sitemap_blog.xml'));

		$this->info('Generating job posting...');
		$jobSitemap = Sitemap::create();
		JobPosting::orderByDesc('published_at')->cursor()->each(function ($job) use ($jobSitemap) {
			$jobSitemap->add(
				$this->frontendUrl("/karir/detail/{$job->slug}")
					->setLastModificationDate($job->updated_at)
					->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY),
			);
		});
		$jobSitemap->writeToFile(public_path('sitemaps/sitemap_job.xml'));

		$this->info('Generating pengumuman...');
		$pengumumanSitemap = Sitemap::create();
		Post::with(['type'])->announcement()->published()->orderByDesc('published_at')->cursor()
			->each(function ($post) use ($pengumumanSitemap) {
				$pengumumanSitemap->add(
					$this->frontendUrl("/pengumuman/detail/{$post->slug}")
						->setLastModificationDate($post->updated_at)
						->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY),
				);
			});
		$pengumumanSitemap->writeToFile(public_path('sitemaps/sitemap_pengumuman.xml'));

		$this->info('Generating index...');
		SitemapIndex::create()
			->add('/sitemaps/sitemap_page.xml')
			->add('/sitemaps/sitemap_job.xml')
			->add('/sitemaps/sitemap_berita.xml')
			->add('/sitemaps/sitemap_blog.xml')
			->add('/sitemaps/sitemap_pengumuman.xml')
			->writeToFile(public_path('sitemaps/sitemap.xml'));

		$this->info('All sitemaps generated successfully!');
	}

	/**
	 * Helper: Menggabungkan Domain Frontend + Path
	 */
	private function frontendUrl(string $path): Url
	{
		// Ambil URL dari config, hapus slash di akhir jika ada
		$baseUrl = rtrim(config('app.landing_url'), '/');

		// Pastikan path diawali slash
		$path = '/' . ltrim($path, '/');

		return Url::create($baseUrl . $path);
	}
}
