<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\UpdateRequest;
use App\Models\User;
use App\ViewModels\Admin\Customer\EditViewModel;
use App\ViewModels\Admin\Customer\ListViewModel;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Customer/List', new ListViewModel());
    }

    public function edit(User $user): Response
    {
        return Inertia::render('Admin/Customer/Edit', new EditViewModel($user));
    }

    public function update(User $user, UpdateRequest $request): RedirectResponse
    {
        $params = $request->validated();

        if ($user->update($params)) {
            session()->flash('success', 'Cliente actualizado correctamente!');
        } else {
            session()->flash('error', 'Error al actualizar el cliente');
        }

        return redirect()->route('customers');
    }
}
