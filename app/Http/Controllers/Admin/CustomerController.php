<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Customer/List', ['title' => 'Clientes']);
    }

    public function edit(User $user): Response
    {
        return Inertia::render('Customer/Edit', [
            'title' => 'Editar cliente',
            'customer' => $user,
        ]);
    }

    public function update(User $user, UpdateCustomerRequest $request): RedirectResponse
    {
        $params = $request->validated();

        $res = false;

        try {
            $res = $user->update($params);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['context' => 'Updating user information']);
        }

        if ($res) {
            session()->flash('success', 'Cliente actualizado correctamente!');
        } else {
            session()->flash('error', 'Error al actualizar el cliente');
        }

        return redirect()->route('customers');
    }
}
