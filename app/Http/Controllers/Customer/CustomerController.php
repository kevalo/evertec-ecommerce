<?php

namespace App\Http\Controllers\Customer;

use App\DBAccess\CustomerDAO;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index(Request $request, CustomerDAO $customerDao)
    {
        $customers = $customerDao->getAll();
        return Inertia::render('Customer/List', ['customers' => $customers]);
    }
}
