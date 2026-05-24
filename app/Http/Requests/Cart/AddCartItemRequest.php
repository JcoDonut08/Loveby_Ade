<?php

namespace App\Http\Requests\Cart;

use App\Services\ProductCatalog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function rules(ProductCatalog $catalog): array
    {
        return [
            'slug' => [
                'required',
                'string',
                Rule::in($catalog->all()->pluck('slug')->all()),
                function (string $attribute, mixed $value, \Closure $fail) use ($catalog): void {
                    $product = $catalog->find((string) $value);

                    if ($product !== null && (int) $product['stock'] <= 0) {
                        $fail('This product is currently out of stock.');
                    }
                },
            ],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:65535'],
        ];
    }
}
