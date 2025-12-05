<?php

namespace App\Http\Requests;

use App\Models\Master\Category;
use Illuminate\Validation\Rule;

class RepeatTransactionRequest extends BaseApiRequest
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
			'repeat.periode' => 'periode',
			'repeat.month' => 'bulan',
			'repeat.date' => 'tanggal',
			'repeat.days' => 'hari',
		];
	}

	/**
	 * Prepare the data for validation.
	 */
	protected function prepareForValidation(): void
	{
		if ($this->input('type') == 'T') {
			$category = Category::where('type', 'T')->first();

			$this->merge([
				'category_id' => $category->id,
			]);
		}

		if ($this->input('method') == 'ET') {
			$this->merge([
				'max_transaction' => 0,
			]);
		} else if ($this->input('method') == 'MT') {
			$this->merge([
				'expired_date' => null,
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
			'expired_date' => ['nullable', 'date'],
			'max_transaction' => ['nullable', 'numeric'],
			'method' => ['required', Rule::in(['MT', 'ET'])],
			'repeat' => ['nullable', 'array'],
			'repeat.date' => [Rule::requiredIf($this->input('repeat.periode') == 'MONTHLY'), 'numeric', 'min:1',
				'max:31'],
			'repeat.days' => [Rule::requiredIf($this->input('repeat.periode') == 'DAILY'), 'array', 'max:7'],
			'repeat.month' => [Rule::requiredIf($this->input('repeat.periode') == 'YEARLY'), 'numeric', 'min:1',
				'max:12'],
			'repeat.periode' => ['required', Rule::in(['DAILY', 'MONTHLY', 'YEARLY'])],
			'type' => ['required', Rule::in(['I', 'E', 'T'])],
			'category_id' => ['required', Rule::exists('categories', 'id')],
			'from_account_id' => ['required', Rule::exists('accounts', 'id')],
			'to_account_id' => ['nullable', Rule::exists('accounts', 'id')],
		];
	}
}
