<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $otp = $this->input('otp');

        if (is_array($otp)) {
            $this->merge([
                'otp_code' => collect($otp)->implode(''),
            ]);
        }
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'otp_code' => ['required', 'digits:6'],
        ];
    }
}
