<?php

namespace App\Http\Requests;

class TestimonialRequest extends BaseApiRequest
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
			'client_name' => ['required', 'string', 'max:100'],
			'client_role' => ['required', 'string', 'max:100'],
			'desc' => ['required', 'array'],
			'desc.id' => ['required', 'string', 'max:250'],
			'desc.en' => ['required', 'string', 'max:250'],
			'title' => ['required', 'array'],
			'title.id' => ['required', 'string', 'max:100'],
			'title.en' => ['required', 'string', 'max:100'],
		];
	}
}
