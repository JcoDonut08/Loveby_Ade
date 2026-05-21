<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCheckoutRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $digits = $this->phoneDigits($this->input('contact_number_digits', $this->input('contact_number')));

        $this->merge([
            'contact_number_digits' => $digits,
            'contact_number' => '+63-'.$digits,
        ]);
    }

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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:120'],
            'contact_number_digits' => ['required', 'digits:10'],
            'contact_number' => ['required', 'regex:/^\+63-\d{10}$/'],
            'email_address' => ['required', 'email', 'max:255'],
            'complete_address' => ['required', 'string', 'max:1000'],
            'delivery_notes' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', Rule::in(['GCash', 'PayMaya', 'Cash on Delivery'])],
            'promo_code' => ['nullable', 'string', 'max:50', 'alpha_dash:ascii'],
        ];
    }

    private function phoneDigits(mixed $value): string
    {
        $digits = preg_replace('/\D/', '', (string) $value) ?: '';

        if (str_starts_with($digits, '63') && strlen($digits) === 12) {
            return substr($digits, 2);
        }

        if (str_starts_with($digits, '0') && strlen($digits) === 11) {
            return substr($digits, 1);
        }

        return $digits;
    }
}
