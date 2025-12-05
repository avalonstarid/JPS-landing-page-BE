<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class AccountRequest extends BaseApiRequest
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
		if ($this->isMethod('POST') && $this->input('saldo')) {
			$this->merge([
				'saldo_awal' => $this->input('saldo'),
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
			'desc' => ['nullable', 'string'],
			'name' => ['required', 'string', 'max:100'],
			'saldo' => ['nullable', 'numeric'],
			'saldo_awal' => ['nullable', 'numeric'],
			'saldo_batas' => ['nullable', 'numeric'],
			'saldo_mengendap' => ['nullable', 'numeric'],
			'tgl_transaksi' => ['nullable', 'date'],
			'bank_id' => ['nullable', Rule::exists('banks', 'id')],
			'type_id' => ['nullable', Rule::exists('enums', 'code')],
		];
	}
}
