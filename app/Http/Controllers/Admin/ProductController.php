<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Models\Product;
use App\ViewModels\Admin\Product\CreateViewModel;
use App\ViewModels\Admin\Product\EditViewModel;
use App\ViewModels\Admin\Product\ListViewModel;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Product/List', new ListViewModel());
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Product/Create', new CreateViewModel());
    }

    public function store(CreateRequest $request): RedirectResponse
    {
        $params = $request->validated();

        $product = new Product();
        $product->name = $params['name'];
        $product->status = $params['status'];

        if ($product->save()) {
            session()->flash('success', 'Producto creado correctamente!');
        } else {
            session()->flash('error', 'Error al crear el producto');
        }

        return redirect()->route('categories');
    }

    public function show(Product $product): Response
    {
        return Inertia::render('Admin/Product/Edit', new EditViewModel($product));
    }

    public function update(Product $product, UpdateRequest $request): RedirectResponse
    {
        $params = $request->validated();

        if ($product->update($params)) {
            session()->flash('success', 'Producto actualizado correctamente!');
        } else {
            session()->flash('error', 'Error al actualizar el producto');
        }

        return redirect()->route('products');
    }
}
