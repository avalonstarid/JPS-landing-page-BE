<?php

namespace App\Http\Resources\Landing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusinessLineResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'featured_thumb' => $this->featured_thumb,
			'locations' => LocationResource::collection($this->whenLoaded('locations')),
			'slug' => $this->slug,
			'title' => $this->getTranslations('title'),
		];
	}
}
