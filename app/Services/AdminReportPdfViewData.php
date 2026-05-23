<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class AdminReportPdfViewData
{
    /**
     * @param  array<string, mixed>  $report
     * @return array{report: array<string, mixed>, logoDataUri: string|null, rowCount: int}
     */
    public function forReport(array $report): array
    {
        return [
            'report' => $report,
            'logoDataUri' => $this->logoDataUri(),
            'rowCount' => count($report['rows'] ?? []),
        ];
    }

    private function logoDataUri(): ?string
    {
        if (extension_loaded('gd')) {
            $path = public_path('images/lovebyadelogo.png');

            if (File::exists($path)) {
                return 'data:'.File::mimeType($path).';base64,'.base64_encode(File::get($path));
            }
        }

        $path = public_path('images/lovebyadelogo.jpg');

        if (File::exists($path)) {
            return 'data:'.File::mimeType($path).';base64,'.base64_encode(File::get($path));
        }

        $path = public_path('favicon.svg');

        if (! File::exists($path)) {
            return null;
        }

        return 'data:image/svg+xml;base64,'.base64_encode(File::get($path));
    }
}
