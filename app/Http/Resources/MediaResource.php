<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
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
			'file_name' => $this->file_name,
			'mime_type' => $this->mime_type,
			'name' => $this->name,
			'original_url' => $this->original_url,
			'size' => $this->size,
			'thumb_url' => $this->when(
				$this->hasGeneratedConversion('thumb'),
				fn() => $this->getUrl('thumb'),
			),
		];
	}
}
