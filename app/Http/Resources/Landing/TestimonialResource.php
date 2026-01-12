<?php

namespace App\Http\Resources\Landing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestimonialResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'client_name' => $this->client_name,
			'client_role' => $this->client_role,
			'desc' => $this->getTranslations('desc'),
			'title' => $this->getTranslations('title'),
		];
	}
}
