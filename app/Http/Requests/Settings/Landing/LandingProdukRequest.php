<?php

namespace App\Http\Requests\Settings\Landing;

use App\Http\Requests\BaseApiRequest;

class LandingProdukRequest extends BaseApiRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return true;
	}

	/**
	 * Get custom attributes for validator errors.
	 *
	 * @return array<string, string>
	 */
	public function attributes(): array
	{
		return [
			// Commercial
			'commercial_stock_title.en' => 'Stock Update Title (Bahasa Inggris)',
			'commercial_stock_title.id' => 'Stock Update Title (Bahasa Indonesia)',
			'commercial_title.en' => 'Produk Komersial Title (Bahasa Inggris)',
			'commercial_title.id' => 'Produk Komersial Title (Bahasa Indonesia)',

			// Hero
			'hero_subtitle.en' => 'Hero Subtitle (Bahasa Inggris)',
			'hero_subtitle.id' => 'Hero Subtitle (Bahasa Indonesia)',
			'hero_title.en' => 'Hero Title (Bahasa Inggris)',
			'hero_title.id' => 'Hero Title (Bahasa Indonesia)',
		];
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			// Commercial
			'commercial_stock_products' => ['required', 'array'],
			'commercial_stock_products.*.title' => ['required', 'array'],
			'commercial_stock_products.*.title.en' => ['required', 'string', 'max:100'],
			'commercial_stock_products.*.title.id' => ['required', 'string', 'max:100'],
			'commercial_stock_products.*.stat' => ['required', 'numeric'],
			'commercial_stock_title' => ['required', 'array'],
			'commercial_stock_title.en' => ['required', 'string', 'max:100'],
			'commercial_stock_title.id' => ['required', 'string', 'max:100'],
			'commercial_title' => ['required', 'array'],
			'commercial_title.en' => ['required', 'string', 'max:100'],
			'commercial_title.id' => ['required', 'string', 'max:100'],

			// Hero
			'hero_background' => ['nullable', 'file', 'image', 'max:5120'],
			'hero_subtitle' => ['required', 'array'],
			'hero_subtitle.en' => ['required', 'string', 'max:100'],
			'hero_subtitle.id' => ['required', 'string', 'max:100'],
			'hero_title' => ['required', 'array'],
			'hero_title.en' => ['required', 'string', 'max:100'],
			'hero_title.id' => ['required', 'string', 'max:100'],

			// SEO
			'seo_description' => ['required', 'string'],
			'seo_title' => ['required', 'string', 'max:100'],
		];
	}
}
