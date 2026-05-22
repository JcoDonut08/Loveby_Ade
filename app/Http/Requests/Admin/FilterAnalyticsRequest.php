<?php

namespace App\Http\Requests\Admin;

use App\Services\AdminAnalyticsReport;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterAnalyticsRequest extends FormRequest
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
            'period' => ['required', 'string', Rule::in(AdminAnalyticsReport::periods())],
            'search' => ['nullable', 'string', 'max:80'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'period' => $this->filled('period')
                ? trim((string) $this->query('period'))
                : AdminAnalyticsReport::PERIOD_WEEK,
            'search' => $this->filled('search') ? trim((string) $this->query('search')) : null,
        ]);
    }
}
