<?php

namespace App\Http\Requests\Investor;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rule;

class DocumentInvsRequest extends BaseApiRequest
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
			'document' => [Rule::requiredIf($this->method() == 'POST'), 'file', 'mimes:pdf', 'max:102400'],
			'featured' => [Rule::requiredIf($this->method() == 'POST'), 'image', 'max:5120'],
			'title' => ['required', 'array'],
			'title.en' => ['required', 'string', 'max:100'],
			'title.id' => ['required', 'string', 'max:100'],
		];
	}
}
