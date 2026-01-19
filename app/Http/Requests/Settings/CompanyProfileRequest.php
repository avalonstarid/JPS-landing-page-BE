<?php

namespace App\Http\Requests\Settings;

use App\Http\Requests\BaseApiRequest;

class CompanyProfileRequest extends BaseApiRequest
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
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'company_address' => ['nullable', 'string'],
			'company_desc' => ['nullable', 'string'],
			'company_name' => ['required', 'string', 'max:100'],
			'company_phone' => ['nullable', 'string', 'max:20'],
			'company_social' => ['nullable', 'array'],
			'company_social.*.icon' => ['required', 'string', 'max:100'],
			'company_social.*.icon_custom' => ['required', 'boolean'],
			'company_social.*.key' => ['required', 'string', 'max:100'],
			'company_social.*.link' => ['required', 'string'],
			'company_social.*.value' => ['required', 'string', 'max:100'],
		];
	}
}
