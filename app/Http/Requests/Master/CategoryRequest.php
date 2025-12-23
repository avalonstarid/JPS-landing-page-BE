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
			'name.en' => 'Nama (Bahasa Inggris)',
			'name.id' => 'Nama (Bahasa Indonesia)',
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
			'name' => ['required', 'array'],
			'name.en' => ['required', 'string'],
			'name.id' => ['required', 'string'],
			'parent_id' => ['nullable', Rule::exists('categories', 'id')],
		];
	}
}
