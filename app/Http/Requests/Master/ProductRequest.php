<?php

namespace App\Http\Requests\Master;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends BaseApiRequest
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
			'full_desc.en' => 'Deskripsi Lengkap (Bahasa Inggris)',
			'full_desc.id' => 'Deskripsi Lengkap (Bahasa Indonesia)',
			'short_desc.en' => 'Deskripsi Singkat (Bahasa Inggris)',
			'short_desc.id' => 'Deskripsi Singkat (Bahasa Indonesia)',
		];
	}

	/**
	 * Prepare the data for validation.
	 */
	protected function prepareForValidation(): void
	{
		$this->merge([
			'active' => (bool)$this->input('active', true),
		]);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'active' => ['required', 'boolean'],
			'featured' => ['nullable', 'file', 'image', 'max:5120'],
			'featured_remove' => ['nullable', 'boolean'],
			'full_desc' => ['nullable', 'array'],
			'full_desc.en' => ['required', 'string'],
			'full_desc.id' => ['required', 'string'],
			'images' => ['nullable', 'array'],
			'images.*' => ['file', 'image', 'max:5120'],
			'images_remove' => ['nullable', 'array'],
			'images_remove.*' => ['nullable', Rule::exists('media', 'id')],
			'short_desc' => ['nullable', 'array'],
			'short_desc.en' => ['required', 'string'],
			'short_desc.id' => ['required', 'string'],
			'slug' => ['nullable', 'string', 'max:255', Rule::unique('products')->ignore($this->product)],
			'sort_order' => ['required', 'numeric'],
			'title' => ['required', 'array'],
			'title.en' => ['required', 'string', 'max:100'],
			'title.id' => ['required', 'string', 'max:100'],
		];
	}
}
