<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Users\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\User\UpdateRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Customer/List');
    }

    public function edit(User $user): Response
    {
        return Inertia::render('Admin/Customer/Edit', ['customer' => $user]);
    }

    public function update(UpdateRequest $request, User $user): RedirectResponse
    {
        $user->update($request->validated());
        session()->flash('success', __('customer.success_update'));

        return redirect()->route('customers');
    }
}
