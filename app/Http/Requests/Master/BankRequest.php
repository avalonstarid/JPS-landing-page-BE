<?php

namespace App\Http\Requests\Master;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rule;

class BankRequest extends BaseApiRequest
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
			'code' => ['required', 'string', 'max:10', Rule::unique('banks')->ignore($this->bank)],
			'logo' => ['nullable', 'file', 'image', 'max:120'],
			'logo_remove' => ['nullable', 'boolean'],
			'name' => ['required', 'string', 'max:100'],
			'short_name' => ['nullable', 'string', 'max:100'],
		];
	}
}
