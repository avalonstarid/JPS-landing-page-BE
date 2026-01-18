<?php

namespace App\Http\Resources\Landing\Keberlanjutan;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TinjauanResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'content' => $this->getTranslations('content'),
			'title' => $this->getTranslations('title'),
		];
	}
}
