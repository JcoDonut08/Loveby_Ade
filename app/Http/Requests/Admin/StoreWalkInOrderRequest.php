<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWalkInOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_number' => ['required', 'string', 'regex:/^LBA-[0-9]{6}$/', Rule::unique('orders', 'order_number')],
            'customer_name' => ['required', 'string', 'max:160'],
            'date_ordered' => ['required', 'date'],
            'promo_code' => ['nullable', 'string', 'max:40'],
            'products' => ['required', 'array', 'min:1', 'max:10'],
            'products.*' => ['required', 'array:product_id,quantity'],
            'products.*.product_id' => [
                'required',
                'integer',
                Rule::exists(Product::class, 'id')->where('is_active', true),
            ],
            'products.*.quantity' => ['required', 'integer', 'min:1', 'max:999'],
        ];
    }
}
