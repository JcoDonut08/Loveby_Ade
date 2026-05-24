<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExportReportRequest;
use App\Services\AdminReportPdfViewData;
use App\Services\AdminReportService;
use App\Services\AdminReportWorkbookExporter;
use App\Services\UserAuditLogger;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

class ReportExportController extends Controller
{
    public function __construct(
        private AdminReportService $reports,
        private AdminReportWorkbookExporter $workbooks,
        private AdminReportPdfViewData $pdfViewData,
        private UserAuditLogger $auditLogger,
    ) {}

    public function __invoke(ExportReportRequest $request): Response
    {
        $report = $this->reports->report($request->report(), $request->filters());
        $filename = $this->reports->filename($report, $request->exportFormat());

        if (! $request->isPreview()) {
            $this->auditLogger->record(
                $request->user(),
                'Report Downloaded',
                'Reports',
                str($report['title'])->headline().' was generated as '.str($request->exportFormat())->upper().'.',
                metadata: [
                    'report' => $request->report(),
                    'format' => $request->exportFormat(),
                    'filename' => $filename,
                    'filters' => $request->filters(),
                    'row_count' => count($report['rows'] ?? []),
                ],
            );
        }

        if ($request->exportFormat() === AdminReportService::FORMAT_PDF) {
            $pdf = Pdf::loadView('pages.admin.reports_pdf', $this->pdfViewData->forReport($report))
                ->setPaper('a4', 'landscape');

            return $request->isPreview()
                ? $pdf->stream($filename, ['Attachment' => false])
                : $pdf->download($filename);
        }

        if ($request->isPreview()) {
            return response($this->workbooks->export($report), 200, [
                'Content-Disposition' => 'inline; filename="'.$filename.'"',
                'Content-Type' => 'text/xml; charset=UTF-8',
            ]);
        }

        return response()->streamDownload(
            fn (): int => print $this->workbooks->export($report),
            $filename,
            ['Content-Type' => 'application/vnd.ms-excel; charset=UTF-8'],
        );
    }
}
