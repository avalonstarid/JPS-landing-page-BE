<?php

namespace App\Http\Requests;

class FaqRequest extends BaseApiRequest
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
			'answer.id' => 'Pertanyaan (Bahasa Indonesia)',
			'answer.en' => 'Pertanyaan (Bahasa Inggris)',
			'question.id' => 'Jawaban (Bahasa Indonesia)',
			'question.en' => 'Jawaban (Bahasa Inggris)',
		];
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
			'answer' => ['required', 'array'],
			'answer.id' => ['required', 'string'],
			'answer.en' => ['required', 'string'],
			'question' => ['required', 'array'],
			'question.id' => ['required', 'string', 'max:100'],
			'question.en' => ['required', 'string', 'max:100'],
			'sort_order' => ['required', 'numeric'],
		];
	}
}
