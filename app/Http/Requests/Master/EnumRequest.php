<?php

namespace App\Http\Requests\Master;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rule;

class EnumRequest extends BaseApiRequest
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
			'code' => ['required', 'string', 'max:20', Rule::unique('enums')->ignore($this->enum)],
			'desc' => ['nullable', 'string'],
			'name' => ['required', 'string', 'max:100'],
			'type_id' => ['required', Rule::exists('enum_types', 'id')],
		];
	}
}
