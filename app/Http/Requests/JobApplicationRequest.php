<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class JobApplicationRequest extends BaseApiRequest
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
			'age' => ['required', 'numeric'],
			'email' => ['required', 'string', 'email', 'max:100'],
			'jurusan' => ['required', 'string', 'max:100'],
			'name' => ['required', 'string', 'max:100'],
			'phone' => ['required', 'string', 'max:20'],
			'school_name' => ['required', 'string', 'max:100'],
			'reason' => ['required', 'string'],
			'resume' => ['required', 'file', 'max:5120', 'mimes:pdf,jpeg,png'],
			'gender_id' => ['required', Rule::exists('enums', 'code')],
			'job_posting_id' => ['required', Rule::exists('job_postings', 'id')],
			'status_kawin_id' => ['required', Rule::exists('enums', 'id')],
		];
	}
}
