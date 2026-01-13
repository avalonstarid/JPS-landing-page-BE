<?php

namespace App\Http\Resources\Landing\Investor;

use App\Http\Resources\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinancialReportResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'arus_kas_bersih' => $this->arus_kas_bersih,
			'document' => new MediaResource($this->whenLoaded('document')),
			'ekuitas' => $this->ekuitas,
			'laba_bersih' => $this->laba_bersih,
			'liabilitas' => $this->liabilitas,
			'name' => $this->getTranslations('name'),
			'penjualan' => $this->penjualan,
			'tahun' => $this->tahun,
		];
	}
}
