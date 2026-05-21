<?php

namespace App\Http\Requests\Admin;

use App\Models\Promotion;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePromotionRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => str((string) $this->input('code'))->trim()->upper()->toString(),
            'discount_type' => str((string) $this->input('discount_type'))->trim()->lower()->toString(),
            'kind' => str((string) $this->input('kind', Promotion::KIND_DISCOUNT))->trim()->lower()->toString(),
        ]);
    }

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
            'kind' => ['required', Rule::in([Promotion::KIND_DISCOUNT, Promotion::KIND_AD])],
            'code' => [
                Rule::requiredIf($this->input('kind') === Promotion::KIND_DISCOUNT),
                'nullable',
                'string',
                'max:50',
                'alpha_dash:ascii',
                Rule::unique('promotions', 'code'),
            ],
            'discount_type' => [
                Rule::requiredIf($this->input('kind') === Promotion::KIND_DISCOUNT),
                'nullable',
                Rule::in([Promotion::DISCOUNT_PERCENTAGE, Promotion::DISCOUNT_FIXED]),
            ],
            'discount_value' => [
                Rule::requiredIf($this->input('kind') === Promotion::KIND_DISCOUNT),
                'nullable',
                'numeric',
                Rule::when($this->input('kind') === Promotion::KIND_DISCOUNT, ['gt:0']),
                Rule::when($this->input('discount_type') === Promotion::DISCOUNT_PERCENTAGE, ['lte:100']),
            ],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['nullable', 'boolean'],
            'announcement_title' => ['nullable', 'string', 'max:160'],
            'announcement_body' => ['nullable', 'string', 'max:1000'],
            'announcement_cta' => ['nullable', 'string', 'max:80'],
            'image' => [
                Rule::requiredIf($this->input('kind') === Promotion::KIND_AD),
                'nullable',
                'image',
                'max:10240',
            ],
        ];
    }
}
