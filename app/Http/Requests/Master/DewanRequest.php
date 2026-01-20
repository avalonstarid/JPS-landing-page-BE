<?php

namespace App\Http\Requests\Master;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rule;

class DewanRequest extends BaseApiRequest
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
			'jabatan.en' => 'Jabatan (Bahasa Inggris)',
			'jabatan.id' => 'Jabatan (Bahasa Indonesia)',
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
			'avatar' => ['nullable', 'file', 'image', 'max:5120'],
			'avatar_remove' => ['nullable', 'boolean'],
			'jabatan' => ['required', 'array'],
			'jabatan.id' => ['required', 'string', 'max:100'],
			'jabatan.en' => ['required', 'string', 'max:100'],
			'name' => ['required', 'string', 'max:100'],
			'organisasi_id' => ['required', Rule::exists('organisasi', 'id')],
		];
	}
}
