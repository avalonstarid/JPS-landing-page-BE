<?php

namespace App\Http\Requests\Settings;

use App\Http\Requests\BaseApiRequest;

class HistoricalTimelineRequest extends BaseApiRequest
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
			'icon' => ['required', 'string', 'max:100'],
			'icon_custom' => ['nullable', 'boolean'],
			'title' => ['required', 'array'],
			'title.id' => ['required', 'string', 'max:100'],
			'title.en' => ['required', 'string', 'max:100'],
			'year' => ['required', 'numeric'],
		];
	}
}
