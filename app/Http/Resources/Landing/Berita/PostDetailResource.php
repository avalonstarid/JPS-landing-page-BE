<?php

namespace App\Http\Resources\Landing\Berita;

use App\Http\Resources\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostDetailResource extends JsonResource
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
			'content' => $this->getTranslations('content'),
			'featured' => new MediaResource($this->whenLoaded('featured')),
			'published_at' => $this->published_at,
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
}
