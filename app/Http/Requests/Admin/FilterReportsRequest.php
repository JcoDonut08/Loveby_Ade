<?php

namespace App\Http\Requests\Admin;

use App\Services\AdminReportService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterReportsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'preview' => ['nullable', 'string', Rule::in(AdminReportService::reportTypes())],
            'format' => ['nullable', 'string', Rule::in(AdminReportService::formats())],
            'search' => ['nullable', 'string', 'max:80'],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $preview = (string) $this->input('preview', '');

        if ($preview !== '') {
            $this->merge([
                'format' => $this->input($preview.'_format', $this->input('format')),
            ]);
        }
    }

    /**
     * @return array{search: string, from: string|null, to: string|null}
     */
    public function filters(): array
    {
        $validated = $this->validated();

        return [
            'search' => trim((string) ($validated['search'] ?? '')),
            'from' => $validated['from'] ?? null,
            'to' => $validated['to'] ?? null,
        ];
    }

    public function previewReport(): ?string
    {
        $preview = $this->validated('preview');

        return is_string($preview) ? $preview : null;
    }

    public function previewFormat(): string
    {
        $format = $this->validated('format');

        return is_string($format) && $format !== ''
            ? $format
            : AdminReportService::FORMAT_PDF;
    }
}
