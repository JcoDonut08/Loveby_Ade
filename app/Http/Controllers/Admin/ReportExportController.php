<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExportReportRequest;
use App\Services\AdminReportPdfViewData;
use App\Services\AdminReportService;
use App\Services\AdminReportWorkbookExporter;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

class ReportExportController extends Controller
{
    public function __construct(
        private AdminReportService $reports,
        private AdminReportWorkbookExporter $workbooks,
        private AdminReportPdfViewData $pdfViewData,
    ) {}

    public function __invoke(ExportReportRequest $request): Response
    {
        $report = $this->reports->report($request->report(), $request->filters());
        $filename = $this->reports->filename($report, $request->exportFormat());

        if ($request->exportFormat() === AdminReportService::FORMAT_PDF) {
            return Pdf::loadView('pages.admin.reports_pdf', $this->pdfViewData->forReport($report))
                ->setPaper('a4', 'landscape')
                ->download($filename);
        }

        return response()->streamDownload(
            fn (): int => print $this->workbooks->export($report),
            $filename,
            ['Content-Type' => 'application/vnd.ms-excel; charset=UTF-8'],
        );
    }
}
