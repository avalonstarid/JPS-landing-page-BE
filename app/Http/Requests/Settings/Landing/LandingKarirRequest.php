<?php

namespace App\Http\Requests\Settings\Landing;

use App\Http\Requests\BaseApiRequest;

class LandingKarirRequest extends BaseApiRequest
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
			// Header
			'header_desc.en' => 'Header Deskripsi (Bahasa Inggris)',
			'header_desc.id' => 'Header Deskripsi (Bahasa Indonesia)',
			'header_title.en' => 'Header Title (Bahasa Inggris)',
			'header_title.id' => 'Header Title (Bahasa Indonesia)',

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
			// Header
			'header_desc' => ['required', 'array'],
			'header_desc.en' => ['required', 'string'],
			'header_desc.id' => ['required', 'string'],
			'header_title' => ['required', 'array'],
			'header_title.en' => ['required', 'string', 'max:100'],
			'header_title.id' => ['required', 'string', 'max:100'],

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
