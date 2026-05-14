<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminCustomerDirectory;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function __construct(private AdminCustomerDirectory $customers) {}

    public function index(): View
    {
        return view('pages.admin.customers', [
            'customers' => $this->customers->customers(),
        ]);
    }
}
