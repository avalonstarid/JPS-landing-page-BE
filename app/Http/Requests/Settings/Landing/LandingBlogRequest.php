<?php

namespace App\Http\Requests\Settings\Landing;

use App\Http\Requests\BaseApiRequest;

class LandingBlogRequest extends BaseApiRequest
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
			// News
			'news_subtitle.en' => 'Terbaru Subtitle (Bahasa Inggris)',
			'news_subtitle.id' => 'Terbaru Subtitle (Bahasa Indonesia)',
			'news_title.en' => 'Terbaru Title (Bahasa Inggris)',
			'news_title.id' => 'Terbaru Title (Bahasa Indonesia)',

			// Popular
			'popular_subtitle.en' => 'Popular Subtitle (Bahasa Inggris)',
			'popular_subtitle.id' => 'Popular Subtitle (Bahasa Indonesia)',
			'popular_title.en' => 'Popular Title (Bahasa Inggris)',
			'popular_title.id' => 'Popular Title (Bahasa Indonesia)',
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
			// News
			'news_subtitle' => ['required', 'array'],
			'news_subtitle.en' => ['required', 'string', 'max:100'],
			'news_subtitle.id' => ['required', 'string', 'max:100'],
			'news_title' => ['required', 'array'],
			'news_title.en' => ['required', 'string', 'max:100'],
			'news_title.id' => ['required', 'string', 'max:100'],

			// Popular
			'popular_subtitle' => ['required', 'array'],
			'popular_subtitle.en' => ['required', 'string', 'max:100'],
			'popular_subtitle.id' => ['required', 'string', 'max:100'],
			'popular_title' => ['required', 'array'],
			'popular_title.en' => ['required', 'string', 'max:100'],
			'popular_title.id' => ['required', 'string', 'max:100'],

			// SEO
			'seo_description' => ['required', 'string'],
			'seo_title' => ['required', 'string', 'max:100'],
		];
	}
}
