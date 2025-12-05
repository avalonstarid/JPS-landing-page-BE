<?php

namespace App\Http\Requests\UserManagement;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rule;

class PermissionRequest extends BaseApiRequest
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
			'desc' => ['nullable', 'string'],
			'label' => ['nullable', 'string', 'max:255'],
			'name' => ['required', 'string', 'max:255', Rule::unique('permissions')->ignore($this->permission)],
		];
	}
}
