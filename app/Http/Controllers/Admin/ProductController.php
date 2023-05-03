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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        $product->image = Storage::disk('public')->putFile('products_images', $request->file('image'));
        $product->slug = Str::slug($params['name'], '-', 'es');
        $product->price = $params['price'];
        $product->quantity = $params['quantity'];
        $product->category_id = $params['category_id'];
        $product->status = $params['status'];

        if ($product->save()) {
            session()->flash('success', 'Producto creado correctamente!');
        } else {
            session()->flash('error', 'Error al crear el producto');
        }

        return redirect()->route('products.index');
    }

    public function show(Product $product): Response
    {
        return Inertia::render('Admin/Product/Edit', new EditViewModel($product));
    }

    public function update(Product $product, UpdateRequest $request): RedirectResponse
    {
        $params = $request->validated();

        $params['image'] = $this->setImage($params, $product);

        if ($product->update($params)) {
            session()->flash('success', 'Producto actualizado correctamente!');
        } else {
            session()->flash('error', 'Error al actualizar el producto');
        }

        return redirect()->route('products.index');
    }

    private function setImage(array $requestParams, Product $product): string
    {
        if ($requestParams['image'] !== null) {
            Storage::disk('public')->delete($product->image);
            return Storage::disk('public')->putFile('products_images', $requestParams['image']);
        }
        return $product->image;
    }
}
