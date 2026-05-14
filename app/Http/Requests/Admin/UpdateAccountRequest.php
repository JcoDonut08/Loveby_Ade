<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class UpdateAccountRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $digits = $this->phoneDigits($this->input('contact_number_digits', $this->input('contact_number')));

        $this->merge([
            'contact_number_digits' => $digits,
            'contact_number' => $digits === '' ? null : '+63-'.$digits,
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
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user()),
            ],
            'contact_number_digits' => ['nullable', 'digits:10'],
            'contact_number' => ['nullable', 'regex:/^\+63-\d{10}$/'],
            'address' => ['nullable', 'string', 'max:500'],
            'profile_photo' => [
                'nullable',
                File::image()
                    ->types(['jpg', 'jpeg', 'png', 'webp'])
                    ->max('25mb'),
            ],
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
