<?php

namespace App\Http\Controllers\Customer;

use App\DBAccess\CustomerDAO;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    public function index(CustomerDAO $customerDao): Response
    {
        return Inertia::render('Customer/List', [
            'title' => 'Clientes',
            'customers' => $customerDao->getAllPaginated()
        ]);
    }

    public function toggleStatus(Request $request, CustomerDAO $customerDao): JsonResponse
    {
        $request->validate([
            'id' => ['required', 'numeric', 'exists:users']
        ]);

        return response()->json([
            'status' => $customerDao->updateStatus($request->input('id'))
        ]);
    }
}
