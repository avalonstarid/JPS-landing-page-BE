<?php

namespace App\Http\Resources\Landing;

use App\Http\Resources\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'full_desc' => $this->getTranslations('full_desc'),
			'images' => MediaResource::collection($this->whenLoaded('images')),
			'title' => $this->getTranslations('title'),
		];
	}
}
