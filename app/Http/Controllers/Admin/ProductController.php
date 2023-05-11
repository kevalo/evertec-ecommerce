<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\Product\StoreProduct;
use App\Actions\Admin\Product\UpdateProduct;
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
        StoreProduct::execute($request->validated());
        session()->flash('success', __('products.success_create'));

        return redirect()->route('products.index');
    }

    public function show(Product $product): Response
    {
        return Inertia::render('Admin/Product/Edit', new EditViewModel($product));
    }

    public function update(Product $product, UpdateRequest $request): RedirectResponse
    {
        UpdateProduct::execute(['fields' => $request->validated(), 'product' => $product]);
        session()->flash('success', __('products.success_update'));

        return redirect()->route('products.index');
    }
}
