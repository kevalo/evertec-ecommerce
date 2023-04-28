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
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Category/List', new ListViewModel());
    }

    public function create(): Response
    {
        return Inertia::render('Category/Create', new CreateViewModel());
    }

    public function store(CreateRequest $request): RedirectResponse
    {
        $params = $request->validated();

        $category = new Category();
        $category->name = $params['name'];
        $category->status = $params['status'];

        if ($category->save()) {
            session()->flash('success', 'Categoría creada correctamente!');
        } else {
            session()->flash('error', 'Error al crear la categoría');
        }

        return redirect()->route('categories');
    }

    public function edit(Category $category): Response
    {
        return Inertia::render('Category/Edit', new EditViewModel($category));
    }

    public function update(Category $category, UpdateRequest $request): RedirectResponse
    {
        $params = $request->validated();

        if ($category->update($params)) {
            session()->flash('success', 'Categoría actualizada correctamente!');
        } else {
            session()->flash('error', 'Error al actualizar la categoría');
        }

        return redirect()->route('categories');
    }
}
