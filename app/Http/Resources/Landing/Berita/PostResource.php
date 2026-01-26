<?php

namespace App\Http\Resources\Landing\Berita;

use App\Http\Resources\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'author' => $this->formatAuthor($this->author),
			'content' => $this->whenHas('content', fn() => $this->getTranslations('content')),
			'headline' => $this->whenHas('headline', fn() => $this->getTranslations('headline')),
			'featured' => new MediaResource($this->whenLoaded('featured')),
			'published_at' => $this->published_at,
			'seo' => $this->formatSeo($this->seo),
			'slug' => $this->slug,
			'title' => $this->getTranslations('title'),
		];
	}

	private function formatAuthor($author): ?array
	{
		if (!$author) return null;

		return [
			'avatar' => $author->avatar,
			'name' => $author->name,
		];
	}

	private function formatSeo($seo): ?array
	{
		if (!$seo) return null;

		return [
			'author' => $seo->author,
			'canonical_url' => $seo->canonical_url,
			'description' => $seo->description,
			'image' => $seo->image,
			'robots' => $seo->robots,
			'title' => $seo->title,
		];
	}
}
