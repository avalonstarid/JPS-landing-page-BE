<?php

namespace App\Http\Requests\Settings\Landing;

use App\Http\Requests\BaseApiRequest;

class LandingLiniBisnisRequest extends BaseApiRequest
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
			// Business Line
			'business_line_title.en' => 'Lini Bisnis Title (Bahasa Inggris)',
			'business_line_title.id' => 'Lini Bisnis Title (Bahasa Indonesia)',

			// CTA
			'cta_text.en' => 'CTA Text (Bahasa Inggris)',
			'cta_text.id' => 'CTA Text (Bahasa Indonesia)',

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
			// Business Line
			'business_line_title' => ['required', 'array'],
			'business_line_title.en' => ['required', 'string', 'max:100'],
			'business_line_title.id' => ['required', 'string', 'max:100'],

			// CTA
			'cta_text' => ['required', 'array'],
			'cta_text.en' => ['required', 'string', 'max:100'],
			'cta_text.id' => ['required', 'string', 'max:100'],

			// Hero
			'hero_background' => ['nullable', 'file', 'image', 'max:5120'],
			'hero_subtitle' => ['required', 'array'],
			'hero_subtitle.en' => ['required', 'string', 'max:100'],
			'hero_subtitle.id' => ['required', 'string', 'max:100'],
			'hero_title' => ['required', 'array'],
			'hero_title.en' => ['required', 'string', 'max:100'],
			'hero_title.id' => ['required', 'string', 'max:100'],
		];
	}
}
