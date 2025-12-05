<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class BaseApiRequest extends FormRequest
{
	/**
	 * Handle a failed validation attempt.
	 */
	protected function failedValidation(Validator $validator)
	{
		throw new HttpResponseException(
			response()->json([
				'success' => false,
				'message' => 'Permintaan tidak valid atau tipe data tidak cocok.',
				'data' => null,
				'errors' => $validator->errors()->toArray(),
			], 422),
		);
	}
}
