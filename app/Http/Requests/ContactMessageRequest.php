<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactMessageRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'concern' => [
                'required',
                'string',
                Rule::in([
                    'Order follow-up',
                    'Product question',
                    'Custom dessert request',
                    'Payment or delivery help',
                ]),
            ],
            'order_number' => ['nullable', 'string', 'max:50'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $user = $this->user();

        if ($user === null) {
            return;
        }

        $this->merge([
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }
}
