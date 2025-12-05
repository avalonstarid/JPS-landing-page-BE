<?php

namespace App\Http\Requests\UserManagement;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends BaseApiRequest
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
			'active' => (bool)$this->input('active', false),
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
			'active' => ['nullable', 'boolean'],
			'email' => ['required', 'email', 'max:100', Rule::unique('users')->ignore($this->user)],
			'avatar' => ['nullable', 'file', 'image', 'max:5120'],
			'avatar_remove' => ['nullable', 'boolean'],
			'name' => ['required', 'string', 'max:100'],
			'password' => [Rule::when(request()->isMethod('POST'), 'required', 'nullable'),
				'confirmed',
				Password::default(),
			],
			'permissions' => ['nullable', 'array'],
			'permissions.*' => [Rule::exists('permissions', 'id')],
			'roles' => ['required', Rule::exists('roles', 'id')],
		];
	}
}
