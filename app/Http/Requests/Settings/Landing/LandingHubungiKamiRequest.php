<?php

namespace App\Http\Requests\Settings\Landing;

use App\Http\Requests\BaseApiRequest;

class LandingHubungiKamiRequest extends BaseApiRequest
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
			// Contact
			'contact_title.en' => 'Contact Title (Bahasa Inggris)',
			'contact_title.id' => 'Contact Title (Bahasa Indonesia)',

			// Hero
			'hero_subtitle.en' => 'Hero Subtitle (Bahasa Inggris)',
			'hero_subtitle.id' => 'Hero Subtitle (Bahasa Indonesia)',
			'hero_title.en' => 'Hero Title (Bahasa Inggris)',
			'hero_title.id' => 'Hero Title (Bahasa Indonesia)',

			// Title
			'title.en' => 'Title (Bahasa Inggris)',
			'title.id' => 'Title (Bahasa Indonesia)',
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
			// Contact
			'contact_title' => ['required', 'array'],
			'contact_title.en' => ['required', 'string', 'max:100'],
			'contact_title.id' => ['required', 'string', 'max:100'],

			// Hero
			'hero_background' => ['nullable', 'file', 'image', 'max:5120'],
			'hero_subtitle' => ['required', 'array'],
			'hero_subtitle.en' => ['required', 'string', 'max:100'],
			'hero_subtitle.id' => ['required', 'string', 'max:100'],
			'hero_title' => ['required', 'array'],
			'hero_title.en' => ['required', 'string', 'max:100'],
			'hero_title.id' => ['required', 'string', 'max:100'],

			// Map
			'map_address' => ['required', 'string'],
			'map_link' => ['required', 'string', 'url'],

			// SEO
			'seo_description' => ['required', 'string'],
			'seo_title' => ['required', 'string', 'max:100'],

			// Title
			'title' => ['required', 'array'],
			'title.en' => ['required', 'string', 'max:100'],
			'title.id' => ['required', 'string', 'max:100'],
		];
	}
}
