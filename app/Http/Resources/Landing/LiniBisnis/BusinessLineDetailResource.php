<?php

namespace App\Http\Resources\Landing\LiniBisnis;

use App\Http\Resources\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusinessLineDetailResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'desc' => $this->getTranslations('desc'),
			'images' => MediaResource::collection($this->whenLoaded('images')),
			'title' => $this->getTranslations('title'),
		];
	}
}
