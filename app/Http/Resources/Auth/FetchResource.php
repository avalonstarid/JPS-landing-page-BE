<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FetchResource extends JsonResource
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
			'avatar' => $this->avatar,
			'email' => $this->email,
			'email_verified_at' => $this->email_verified_at,
			'name' => $this->name,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
			'role' => $this->roles->first()?->name,
			'scope' => $this->getRoleNames()->merge($this->getAllPermissions()->pluck('name')),
		];
	}
}
