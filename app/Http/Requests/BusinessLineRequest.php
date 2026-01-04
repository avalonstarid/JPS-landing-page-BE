<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class BusinessLineRequest extends BaseApiRequest
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
			'desc.id' => 'Deskripsi (Bahasa Indonesia)',
			'desc.en' => 'Deskripsi (Bahasa Inggris)',
			'title.id' => 'Judul (Bahasa Indonesia)',
			'title.en' => 'Judul (Bahasa Inggris)',
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
			'desc' => ['required', 'array'],
			'desc.id' => ['required', 'string'],
			'desc.en' => ['required', 'string'],
			'featured' => ['nullable', 'file', 'image', 'max:5120'],
			'featured_remove' => ['nullable', 'boolean'],
			'images' => ['nullable', 'array'],
			'images.*' => ['file', 'image', 'max:5120'],
			'images_remove' => ['nullable', 'array'],
			'images_remove.*' => ['nullable', Rule::exists('media', 'id')],
			'sort_order' => ['required', 'string', 'numeric'],
			'title' => ['required', 'array'],
			'title.id' => ['required', 'string', 'max:100'],
			'title.en' => ['required', 'string', 'max:100'],
		];
	}
}
