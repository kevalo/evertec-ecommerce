<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CreateRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Models\Category;
use App\ViewModels\Admin\Category\CreateViewModel;
use App\ViewModels\Admin\Category\EditViewModel;
use App\ViewModels\Admin\Category\ListViewModel;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Category/List', new ListViewModel());
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Category/Create', new CreateViewModel());
    }

    public function store(CreateRequest $request): RedirectResponse
    {
        $params = $request->validated();

        if (Category::create($params)) {
            session()->flash('success', 'Categoría creada correctamente!');
        } else {
            session()->flash('error', 'Error al crear la categoría');
        }

        return redirect()->route('categories.index');
    }

    public function show(Category $category): Response
    {
        return Inertia::render('Admin/Category/Edit', new EditViewModel($category));
    }

    public function update(Category $category, UpdateRequest $request): RedirectResponse
    {
        $params = $request->validated();

        if ($category->update($params)) {
            session()->flash('success', 'Categoría actualizada correctamente!');
        } else {
            session()->flash('error', 'Error al actualizar la categoría');
        }

        return redirect()->route('categories.index');
    }
}
