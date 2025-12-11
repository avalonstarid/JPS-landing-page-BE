<?php

namespace App\Http\Requests\Master;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductRequest extends BaseApiRequest
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
		if ($this->missing('slug') || empty($this->input('slug'))) {
			$this->merge([
				'slug' => Str::slug($this->input('title')),
			]);
		} else {
			$this->merge([
				'slug' => Str::slug($this->input('slug')),
			]);
		}

		$this->merge([
			'active' => (bool)$this->input('active', true),
		]);
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
			'featured' => ['nullable', 'file', 'image', 'max:5120'],
			'featured_remove' => ['nullable', 'boolean'],
			'full_desc' => ['nullable', 'string'],
			'images' => ['nullable', 'array'],
			'images.*' => ['file', 'image', 'max:5120'],
			'images_remove' => ['nullable', 'array'],
			'images_remove.*' => ['nullable', Rule::exists('media', 'id')],
			'short_desc' => ['nullable', 'string'],
			'slug' => ['nullable', 'string', 'max:255', Rule::unique('products')->ignore($this->product)],
			'sort_order' => ['required', 'numeric'],
			'title' => ['required', 'string', 'max:100'],
		];
	}
}
