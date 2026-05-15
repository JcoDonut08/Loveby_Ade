<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminDashboardOverview;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private AdminDashboardOverview $overview) {}

    public function __invoke(): View
    {
        return view('pages.admin.dashboard', $this->overview->data());
    }
}
