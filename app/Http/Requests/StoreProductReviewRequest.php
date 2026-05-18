<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'display_name' => ['nullable', 'string', 'max:80'],
            'rating' => ['required', 'integer', Rule::in([1, 2, 3, 4, 5])],
            'review' => ['required', 'string', 'min:10', 'max:2000'],
            'is_anonymous' => ['sometimes', 'boolean'],
            'media' => ['sometimes', 'array', 'max:3'],
            'media.*' => ['image', 'max:2048'],
        ];
    }
}
