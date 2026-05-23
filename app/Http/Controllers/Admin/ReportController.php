<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FilterReportsRequest;
use App\Services\AdminReportService;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(private AdminReportService $reports) {}

    public function __invoke(FilterReportsRequest $request): View
    {
        return view('pages.admin.reports', $this->reports->overview($request->filters()));
    }
}
