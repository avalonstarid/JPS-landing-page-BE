<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class PostRequest extends BaseApiRequest
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
			'content.en' => 'Isi Konten (Bahasa Inggris)',
			'content.id' => 'Isi Konten (Bahasa Indonesia)',
			'title.en' => 'Judul (Bahasa Inggris)',
			'title.id' => 'Judul (Bahasa Indonesia)',
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
			'content' => ['nullable', 'array'],
			'content.en' => ['required', 'string'],
			'content.id' => ['required', 'string'],
			'featured' => ['nullable', 'file', 'image', 'max:5120'],
			'featured_remove' => ['nullable', 'boolean'],
			'is_published' => ['required', 'boolean'],
			'published_at' => ['nullable', 'date'],
			'seo' => ['nullable', 'array'],
			'seo.desc' => ['nullable', 'string', 'max:160'],
			'seo.title' => ['nullable', 'string'],
			'slug' => ['nullable', 'string', 'max:255', Rule::unique('posts')->ignore($this->post)],
			'title' => ['required', 'array'],
			'title.en' => ['required', 'string'],
			'title.id' => ['required', 'string'],
			'type' => ['required', 'string', Rule::in(['announcement', 'blog', 'news'])],
		];
	}
}
