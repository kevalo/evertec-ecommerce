<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Categories\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Category\CreateRequest;
use App\Http\Requests\Web\Category\UpdateRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Category/List');
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Category/Create');
    }

    public function store(CreateRequest $request): RedirectResponse
    {
        Category::create($request->validated());
        session()->flash('success', __('categories.success_create'));

        return redirect()->route('categories.index');
    }

    public function show(Category $category): Response
    {
        return Inertia::render('Admin/Category/Edit', ['category' => $category]);
    }

    public function update(UpdateRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());
        session()->flash('success', __('categories.success_update'));

        return redirect()->route('categories.index');
    }
}
