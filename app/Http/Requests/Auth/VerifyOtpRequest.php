<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VerifyOtpRequest extends FormRequest
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
	 * Prepare the data for validation.
	 */
	protected function prepareForValidation(): void
	{
		$this->merge([
			'type' => $this->route('type'),
		]);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		$otp_length = config('one-time-passwords.password_length');

		return [
			'email' => ['required', 'email', Rule::exists('users')],
			'otp' => ['required', 'string', sprintf('min:%d', $otp_length), sprintf('max:%d', $otp_length),
				'regex:/^[0-9]+$/'],
			'type' => ['required', 'string', Rule::in(['cookie', 'token', 'verif'])],
		];
	}
}
