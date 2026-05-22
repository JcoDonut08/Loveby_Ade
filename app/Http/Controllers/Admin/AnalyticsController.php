<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FilterAnalyticsRequest;
use App\Services\AdminAnalyticsReport;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function __construct(private AdminAnalyticsReport $analytics) {}

    public function __invoke(FilterAnalyticsRequest $request): View
    {
        return view('pages.admin.analytics', $this->analytics->data($request->validated()));
    }
}
