<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class JobPostingRequest extends BaseApiRequest
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
			'desc.en' => 'Deskripsi Lengkap (Bahasa Inggris)',
			'desc.id' => 'Deskripsi Lengkap (Bahasa Indonesia)',
			'desc_short.en' => 'Deskripsi Singkat (Bahasa Inggris)',
			'desc_short.id' => 'Deskripsi Singkat (Bahasa Indonesia)',
			'title.en' => 'Judul (Bahasa Inggris)',
			'title.id' => 'Judul (Bahasa Indonesia)',
		];
	}

	/**
	 * Prepare the data for validation.
	 */
	protected function prepareForValidation(): void
	{
		if ($this->method() === 'POST') {
			$this->merge([
				'published_at' => now(),
			]);
		}
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'address' => ['required', 'string'],
			'closed_at' => ['nullable', 'date'],
			'desc' => ['required', 'array'],
			'desc.en' => ['required', 'string'],
			'desc.id' => ['required', 'string'],
			'desc_short' => ['required', 'array'],
			'desc_short.en' => ['required', 'string'],
			'desc_short.id' => ['required', 'string'],
			'location' => ['required', 'string', 'max:100'],
			'published_at' => ['nullable', 'date'],
			'title' => ['required', 'array'],
			'title.en' => ['required', 'string', 'max:100'],
			'title.id' => ['required', 'string', 'max:100'],
			'category_id' => ['required', Rule::exists('categories', 'id')],
		];
	}
}
