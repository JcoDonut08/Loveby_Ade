<?php

namespace App\Http\Requests\Admin;

use App\Services\AdminReportService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExportReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'report' => ['required', Rule::in(AdminReportService::reportTypes())],
            'format' => ['required', Rule::in(AdminReportService::formats())],
            'search' => ['nullable', 'string', 'max:80'],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $report = (string) $this->route('report');

        $this->merge([
            'report' => $report,
            'format' => $this->input($report.'_format', $this->input('format')),
        ]);
    }

    public function report(): string
    {
        return (string) $this->validated('report');
    }

    public function exportFormat(): string
    {
        return (string) $this->validated('format');
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
}
