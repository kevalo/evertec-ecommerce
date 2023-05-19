<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\AddQuantityRequest;
use App\Models\Product;
use App\ViewModels\Admin\Product\AddQuantityViewModel;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProductQuantityController extends Controller
{
    public function show(Product $product): Response
    {
        return Inertia::render('Admin/Product/AddQuantity', new AddQuantityViewModel($product));
    }

    public function update(AddQuantityRequest $request, Product $product): RedirectResponse
    {
        $params = $request->validated();

        $params['quantity'] += $product->quantity;

        $product->update($params);
        session()->flash('success', __('products.success_update'));

        return redirect()->route('products.index');
    }
}
