<?php

namespace App\Http\Controllers\Customer;

use App\DBAccess\CustomerDAO;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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

    public function edit(User $user): Response
    {
        return Inertia::render('Customer/Edit', [
            'title' => 'Editar cliente',
            'customer' => $user
        ]);
    }

    public function update(User $user, UpdateCustomerRequest $request, CustomerDAO $customerDAO): RedirectResponse
    {
        $params = $request->validated();

        if ($customerDAO->updateBasicData($user, $params)) {
            session()->flash('success', 'Cliente actualizado correctamente!');
        } else {
            session()->flash('error', 'Error al actualizar el cliente');
        }

        return redirect()->route('customers');
    }
}
