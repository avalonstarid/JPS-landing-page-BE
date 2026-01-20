<?php

namespace App\Http\Resources\Landing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DewanResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'avatar' => $this->avatar_thumb,
			'name' => $this->name,
			'jabatan' => $this->getTranslations('jabatan'),
		];
	}
}
