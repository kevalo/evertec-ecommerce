<?php

namespace App\Http\Controllers\Customer;

use App\DBAccess\CustomerDAO;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index(CustomerDAO $customerDao)
    {
        return Inertia::render('Customer/List', [
            'title' => 'Clientes',
            'customers' => $customerDao->getAll()
        ]);
    }

    public function toggleStatus(Request $request, CustomerDAO $customerDao)
    {
        $request->validate([
            'id' => ['required', 'numeric', 'exists:users']
        ]);

        return response()->json([
            'status' => $customerDao->updateStatus($request->input('id'))
        ]);
    }
}
