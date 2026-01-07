<?php

namespace App\Http\Resources\Master;

use App\Http\Resources\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
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
			'active' => $this->active,
			'address' => $this->address,
			'business_line' => $this->whenLoaded('businessLine'),
			'business_line_id' => $this->business_line_id,
			'created_at' => $this->created_at,
			'created_by_id' => $this->created_by_id,
			'desc' => $this->desc,
			'lat' => $this->lat,
			'lng' => $this->lng,
			'media' => MediaResource::collection($this->whenLoaded('media')),
			'phone' => $this->phone,
			'updated_at' => $this->updated_at,
			'updated_by_id' => $this->updated_by_id,
		];
	}
}
