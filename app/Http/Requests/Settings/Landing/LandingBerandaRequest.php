<?php

namespace App\Http\Requests\Settings\Landing;

use App\Http\Requests\BaseApiRequest;

class LandingBerandaRequest extends BaseApiRequest
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
			// FAQ
			'faq_cta_lead.en' => 'Faq CTA Lead (Bahasa Inggris)',
			'faq_cta_lead.id' => 'Faq CTA Lead (Bahasa Indonesia)',
			'faq_cta_text.en' => 'Faq CTA Teks (Bahasa Inggris)',
			'faq_cta_text.id' => 'Faq CTA Teks (Bahasa Indonesia)',
			'faq_title.en' => 'Faq Title (Bahasa Inggris)',
			'faq_title.id' => 'Faq Title (Bahasa Indonesia)',

			// Hero
			'hero_cta_text.en' => 'Hero CTA Teks (Bahasa Inggris)',
			'hero_cta_text.id' => 'Hero CTA Teks (Bahasa Indonesia)',
			'hero_rotation_words.en' => 'Hero Rotation Words (Bahasa Inggris)',
			'hero_rotation_words.id' => 'Hero Rotation Words (Bahasa Indonesia)',
			'hero_subtitle.en' => 'Hero Subtitle (Bahasa Inggris)',
			'hero_subtitle.id' => 'Hero Subtitle (Bahasa Indonesia)',
			'hero_title.en' => 'Hero Title (Bahasa Inggris)',
			'hero_title.id' => 'Hero Title (Bahasa Indonesia)',

			// Produk
			'product_cta_text.en' => 'Produk CTA Teks (Bahasa Inggris)',
			'product_cta_text.id' => 'Produk CTA Teks (Bahasa Indonesia)',
			'product_subtitle.en' => 'Produk Subtitle (Bahasa Inggris)',
			'product_subtitle.id' => 'Produk Subtitle (Bahasa Indonesia)',
			'product_title.en' => 'Produk Title (Bahasa Inggris)',
			'product_title.id' => 'Produk Title (Bahasa Indonesia)',

			// Standard
			'standard_subtitle.en' => 'Standard Subtitle (Bahasa Inggris)',
			'standard_subtitle.id' => 'Standard Subtitle (Bahasa Indonesia)',
			'standard_title.en' => 'Standard Title (Bahasa Inggris)',
			'standard_title.id' => 'Standard Title (Bahasa Indonesia)',

			// Testimonial
			'testimonial_title.en' => 'Standard Title (Bahasa Inggris)',
			'testimonial_title.id' => 'Standard Title (Bahasa Indonesia)',
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
			// FAQ
			'faq_cta_lead' => ['required', 'array'],
			'faq_cta_lead.en' => ['required', 'string', 'max:100'],
			'faq_cta_lead.id' => ['required', 'string', 'max:100'],
			'faq_cta_link' => ['required', 'string'],
			'faq_cta_text' => ['required', 'array'],
			'faq_cta_text.en' => ['required', 'string', 'max:100'],
			'faq_cta_text.id' => ['required', 'string', 'max:100'],
			'faq_featured' => ['nullable', 'file', 'image', 'max:5120'],
			'faq_title' => ['required', 'array'],
			'faq_title.en' => ['required', 'string', 'max:100'],
			'faq_title.id' => ['required', 'string', 'max:100'],

			// Hero
			'hero_background' => ['nullable', 'file', 'image', 'max:5120'],
			'hero_cta_link' => ['required', 'string'],
			'hero_cta_text' => ['required', 'array'],
			'hero_cta_text.en' => ['required', 'string', 'max:100'],
			'hero_cta_text.id' => ['required', 'string', 'max:100'],
			'hero_rotation_words' => ['required', 'array'], // Belum
			'hero_rotation_words.en' => ['required', 'array'],
			'hero_rotation_words.en.*' => ['required', 'string', 'max:50'],
			'hero_rotation_words.id' => ['required', 'array'],
			'hero_rotation_words.id.*' => ['required', 'string', 'max:50'],
			'hero_subtitle' => ['required', 'array'],
			'hero_subtitle.en' => ['required', 'string', 'max:100'],
			'hero_subtitle.id' => ['required', 'string', 'max:100'],
			'hero_title' => ['required', 'array'],
			'hero_title.en' => ['required', 'string', 'max:100'],
			'hero_title.id' => ['required', 'string', 'max:100'],

			// Produk
			'product_cta_link' => ['required', 'string'],
			'product_cta_text' => ['required', 'array'],
			'product_cta_text.en' => ['required', 'string', 'max:100'],
			'product_cta_text.id' => ['required', 'string', 'max:100'],
			'product_subtitle' => ['required', 'array'],
			'product_subtitle.en' => ['required', 'string', 'max:100'],
			'product_subtitle.id' => ['required', 'string', 'max:100'],
			'product_title' => ['required', 'array'],
			'product_title.en' => ['required', 'string', 'max:100'],
			'product_title.id' => ['required', 'string', 'max:100'],

			// SEO
			'seo_title' => ['required', 'string', 'max:100'],
			'seo_description' => ['required', 'string'],

			// Standard
			'standard_featured' => ['nullable', 'file', 'image', 'max:5120'],
			'standard_subtitle' => ['required', 'array'],
			'standard_subtitle.en' => ['required', 'string', 'max:100'],
			'standard_subtitle.id' => ['required', 'string', 'max:100'],
			'standard_title' => ['required', 'array'],
			'standard_title.en' => ['required', 'string', 'max:100'],
			'standard_title.id' => ['required', 'string', 'max:100'],

			// Testimonial
			'testimonial_background' => ['nullable', 'file', 'image', 'max:5120'],
			'testimonial_title' => ['required', 'array'],
			'testimonial_title.en' => ['required', 'string', 'max:100'],
			'testimonial_title.id' => ['required', 'string', 'max:100'],
		];
	}
}
