<?php

namespace App\Http\Requests\Settings\Landing;

use App\Http\Requests\BaseApiRequest;

class LandingTentangPerusahaanRequest extends BaseApiRequest
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
			// Dewan
			'dewan_title.en' => 'Dewan Title (Bahasa Inggris)',
			'dewan_title.id' => 'Dewan Title (Bahasa Indonesia)',

			// Hero
			'hero_subtitle.en' => 'Hero Subtitle (Bahasa Inggris)',
			'hero_subtitle.id' => 'Hero Subtitle (Bahasa Indonesia)',
			'hero_title.en' => 'Hero Title (Bahasa Inggris)',
			'hero_title.id' => 'Hero Title (Bahasa Indonesia)',

			// History Timeline
			'history_timeline_title.en' => 'Lokasi Title (Bahasa Inggris)',
			'history_timeline_title.id' => 'Lokasi Title (Bahasa Indonesia)',

			// Lokasi
			'location_title.en' => 'Lokasi Title (Bahasa Inggris)',
			'location_title.id' => 'Lokasi Title (Bahasa Indonesia)',

			// Organisasi
			'organization_title.en' => 'Organisasi Title (Bahasa Inggris)',
			'organization_title.id' => 'Organisasi Title (Bahasa Indonesia)',

			// Video
			'video_title.en' => 'Video Title (Bahasa Inggris)',
			'video_title.id' => 'Video Title (Bahasa Indonesia)',

			// Visi & Misi
			'visi_misi_subtitle.en' => 'Visi & Misi Subtitle (Bahasa Inggris)',
			'visi_misi_subtitle.id' => 'Visi & Misi Subtitle (Bahasa Indonesia)',
			'visi_misi_title.en' => 'Visi & Misi Title (Bahasa Inggris)',
			'visi_misi_title.id' => 'Visi & Misi Title (Bahasa Indonesia)',
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
			// Dewan
			'dewan_title' => ['required', 'array'],
			'dewan_title.en' => ['required', 'string', 'max:100'],
			'dewan_title.id' => ['required', 'string', 'max:100'],

			// Hero
			'hero_background' => ['nullable', 'file', 'image', 'max:5120'],
			'hero_stat' => ['nullable', 'array'],
			'hero_stat.*.icon' => ['required', 'string', 'max:100'],
			'hero_stat.*.icon_custom' => ['required', 'boolean'],
			'hero_stat.*.label' => ['required', 'array'],
			'hero_stat.*.label.en' => ['required', 'string', 'max:100'],
			'hero_stat.*.label.id' => ['required', 'string', 'max:100'],
			'hero_stat.*.value' => ['required', 'string', 'max:100'],
			'hero_subtitle' => ['required', 'array'],
			'hero_subtitle.en' => ['required', 'string', 'max:100'],
			'hero_subtitle.id' => ['required', 'string', 'max:100'],
			'hero_title' => ['required', 'array'],
			'hero_title.en' => ['required', 'string', 'max:100'],
			'hero_title.id' => ['required', 'string', 'max:100'],

			// History Timeline
			'history_timeline_title' => ['required', 'array'],
			'history_timeline_title.en' => ['required', 'string', 'max:100'],
			'history_timeline_title.id' => ['required', 'string', 'max:100'],

			// Lokasi
			'location_title' => ['required', 'array'],
			'location_title.en' => ['required', 'string', 'max:100'],
			'location_title.id' => ['required', 'string', 'max:100'],

			// Organisasi
			'organization_featured' => ['nullable', 'file', 'image', 'max:5120'],
			'organization_title' => ['required', 'array'],
			'organization_title.en' => ['required', 'string', 'max:100'],
			'organization_title.id' => ['required', 'string', 'max:100'],

			// SEO
			'seo_description' => ['required', 'string'],
			'seo_title' => ['required', 'string', 'max:100'],

			// Video
			'video_link' => ['required', 'string', 'url'],
			'video_title' => ['required', 'array'],
			'video_title.en' => ['required', 'string', 'max:100'],
			'video_title.id' => ['required', 'string', 'max:100'],

			// Visi & Misi
			'visi_misi_featured' => ['nullable', 'file', 'image', 'max:5120'],
			'visi_misi_subtitle' => ['required', 'array'],
			'visi_misi_subtitle.en' => ['required', 'string'],
			'visi_misi_subtitle.id' => ['required', 'string'],
			'visi_misi_title' => ['required', 'array'],
			'visi_misi_title.en' => ['required', 'string', 'max:100'],
			'visi_misi_title.id' => ['required', 'string', 'max:100'],
		];
	}
}
