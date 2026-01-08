<?php

namespace App\Http\Resources\Investor;

use App\Http\Resources\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentInvsSource extends JsonResource
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
			'slug' => $this->slug,
			'title' => $this->getTranslations('title'),
			'category' => $this->whenLoaded('category'),
			'category_id' => $this->category_id,
			'created_at' => $this->created_at,
			'created_by_id' => $this->created_by_id,
			'document' => new MediaResource($this->whenLoaded('document')),
			'featured' => new MediaResource($this->whenLoaded('featured')),
			'updated_at' => $this->updated_at,
			'updated_by_id' => $this->updated_by_id,
		];
	}
}
