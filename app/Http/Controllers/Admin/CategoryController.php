<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CreateRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Models\Category;
use http\Client\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Category/List', ['title' => 'Categorías']);
    }

    public function create(): Response
    {
        return Inertia::render('Category/Create', [
            'title' => 'Crear categoría'
        ]);
    }

    public function store(CreateRequest $request): RedirectResponse
    {
        $params = $request->validated();

        $res = false;

        try {
            $category = new Category();
            $category->name = $params['name'];
            $category->status = $params['status'];
            $res = $category->save();
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['context' => 'Creating category']);
        }

        if ($res) {
            session()->flash('success', 'Categoría creada correctamente!');
        } else {
            session()->flash('error', 'Error al crear la categoría');
        }

        return redirect()->route('categories');
    }

    public function edit(Category $category): Response
    {
        return Inertia::render('Category/Edit', [
            'title' => 'Editar categoría',
            'category' => $category,
        ]);
    }

    public function update(Category $category, UpdateRequest $request): RedirectResponse
    {
        $params = $request->validated();

        $res = false;

        try {
            $res = $category->update($params);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['context' => 'Updating category information']);
        }

        if ($res) {
            session()->flash('success', 'Categoría actualizada correctamente!');
        } else {
            session()->flash('error', 'Error al actualizar la categoría');
        }

        return redirect()->route('categories');
    }
}
