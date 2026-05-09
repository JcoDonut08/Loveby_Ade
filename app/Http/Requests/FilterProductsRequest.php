<?php

namespace App\Http\Requests;

use App\Services\ProductCatalog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterProductsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:80'],
            'category' => ['nullable', 'string', Rule::in(ProductCatalog::availableCategories())],
            'min_price' => ['nullable', 'integer', 'min:0', 'max:10000'],
            'max_price' => ['nullable', 'integer', 'min:0', 'max:10000', 'gte:min_price'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'search' => $this->filled('search') ? trim((string) $this->query('search')) : null,
            'category' => $this->filled('category') ? trim((string) $this->query('category')) : null,
        ]);
    }
}
