<?php

namespace App\Http\Resources\Landing\Investor;

use App\Http\Resources\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentInvsResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'document' => new MediaResource($this->whenLoaded('document')),
			'featured' => new MediaResource($this->whenLoaded('featured')),
			'title' => $this->getTranslations('title'),
		];
	}
}
