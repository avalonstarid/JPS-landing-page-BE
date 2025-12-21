<?php

namespace App\Http\Requests;

class ContactUsRequest extends BaseApiRequest
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
			'email' => ['required', 'string', 'email', 'max:100'],
			'location' => ['required', 'string', 'max:100'],
			'message' => ['required', 'string'],
			'name' => ['required', 'string', 'max:100'],
			'phone' => ['required', 'string', 'max:20'],
		];
	}
}
