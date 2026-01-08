<?php

namespace App\Http\Resources\Investor;

use App\Http\Resources\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinancialReportSource extends JsonResource
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
			'arus_kas_bersih' => $this->arus_kas_bersih,
			'ekuitas' => $this->ekuitas,
			'laba_bersih' => $this->laba_bersih,
			'liabilitas' => $this->liabilitas,
			'name' => $this->getTranslations('name'),
			'penjualan' => $this->penjualan,
			'tahun' => $this->tahun,
			'created_at' => $this->created_at,
			'created_by_id' => $this->created_by_id,
			'document' => new MediaResource($this->whenLoaded('document')),
			'featured' => new MediaResource($this->whenLoaded('featured')),
			'updated_at' => $this->updated_at,
			'updated_by_id' => $this->updated_by_id,
		];
	}
}
