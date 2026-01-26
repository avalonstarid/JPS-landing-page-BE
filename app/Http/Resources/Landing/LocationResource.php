<?php

namespace App\Http\Resources\Landing;

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
			'address' => $this->address,
			'lat' => $this->lat,
			'lng' => $this->lng,
			'phone' => $this->phone,
		];
	}
}
