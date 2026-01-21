<?php

namespace App\Http\Requests\Master;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends BaseApiRequest
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
			'desc.en' => 'Deskripsi (Bahasa Inggris)',
			'desc.id' => 'Deskripsi (Bahasa Indonesia)',
			'name.en' => 'Nama Kategori (Bahasa Inggris)',
			'name.id' => 'Nama Kategori (Bahasa Indonesia)',
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
			'desc' => ['nullable', 'array'],
			'desc.en' => ['nullable', 'string'],
			'desc.id' => ['nullable', 'string'],
			'name' => ['required', 'array'],
			'name.en' => ['required', 'string', 'max:100'],
			'name.id' => ['required', 'string', 'max:100'],
			'parent_id' => ['nullable', Rule::exists('categories', 'id')],
		];
	}
}
