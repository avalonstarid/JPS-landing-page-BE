<?php

namespace App\Http\Requests\Master;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rule;

class LocationRequest extends BaseApiRequest
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
			'address' => ['required', 'string'],
			'desc' => ['nullable', 'array'],
			'desc.en' => ['required', 'string'],
			'desc.id' => ['required', 'string'],
			'images' => ['nullable', 'array'],
			'images.*' => ['file', 'image', 'max:5120'],
			'images_remove' => ['nullable', 'array'],
			'images_remove.*' => ['nullable', Rule::exists('media', 'id')],
			'lat' => ['required', 'numeric', 'between:-90,90'],
			'lng' => ['required', 'numeric', 'between:-180,180'],
			'phone' => ['required', 'string', 'max:20'],
			'business_line_id' => ['required', Rule::exists('business_lines', 'id')],
		];
	}
}
