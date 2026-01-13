<?php

namespace App\Http\Resources\Landing\Karir;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KarirResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'address' => $this->address,
			'category' => new CategoryResource($this->whenLoaded('category')),
			'desc' => $this->whenHas('desc', fn() => $this->getTranslations('desc')),
			'desc_short' => $this->whenHas('desc_short', fn() => $this->getTranslations('desc_short')),
			'location' => $this->location,
			'published_at' => $this->published_at,
			'slug' => $this->slug,
			'title' => $this->getTranslations('title'),
		];
	}
}
