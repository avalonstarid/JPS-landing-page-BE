<?php

namespace App\Http\Requests;

use App\Models\Master\Category;
use Illuminate\Validation\Rule;

class TransactionRequest extends BaseApiRequest
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
		if ($this->type == 'T') {
			$category = Category::where('type', 'T')->first();

			$this->merge([
				'category_id' => $category->id,
			]);
		}
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'amount' => ['required', 'numeric'],
			'desc' => ['nullable', 'string'],
			'tanggal' => ['required', 'date'],
			'type' => ['required', Rule::in(['I', 'E', 'T'])],
			'category_id' => ['required', Rule::exists('categories', 'id')],
			'from_account_id' => ['required', Rule::exists('accounts', 'id')],
			'to_account_id' => ['nullable', Rule::exists('accounts', 'id')],
		];
	}
}
