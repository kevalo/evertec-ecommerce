<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Products\Actions\StoreProduct;
use App\Domain\Products\Actions\UpdateProduct;
use App\Domain\Products\Models\Product;
use App\Domain\Products\ViewModels\CreateViewModel;
use App\Domain\Products\ViewModels\EditViewModel;
use App\Domain\Products\ViewModels\ListViewModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Product\CreateRequest;
use App\Http\Requests\Web\Product\UpdateRequest;
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

    public function update(UpdateRequest $request, Product $product): RedirectResponse
    {
        UpdateProduct::execute(['fields' => $request->validated(), 'product' => $product]);
        session()->flash('success', __('products.success_update'));

        return redirect()->route('products.index');
    }
}
