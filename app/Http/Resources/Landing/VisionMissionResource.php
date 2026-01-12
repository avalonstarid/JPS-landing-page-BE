<?php

namespace App\Http\Resources\Landing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisionMissionResource extends JsonResource
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
			'icon' => $this->icon,
			'icon_custom' => $this->icon_custom,
		];
	}
}
