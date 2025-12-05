<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MenuRequest extends FormRequest
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
			'active' => ['required', 'boolean'],
			'icon' => ['nullable', 'string', 'max:50'],
			'order' => ['required', 'numeric'],
			'title' => ['nullable', 'string', 'max:50'],
			'to' => ['nullable', 'string', 'max:50'],
			'parent_id' => ['nullable', Rule::exists('menus', 'id')],
			'permissions' => ['nullable', 'array'],
			'permissions.*' => [Rule::exists('permissions', 'id')],
			'roles' => ['nullable', 'array'],
			'roles.*' => [Rule::exists('roles', 'id')],
		];
	}
}
