<?php

namespace App\Http\Controllers\Web;

use App\Domain\Products\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Application as ApplicationB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function show(string $slug): Application|ApplicationB|RedirectResponse|Redirector|Response
    {
        $product = Product::select(
            'products.id',
            'products.name',
            'description',
            'slug',
            'image',
            'price',
            'categories.name as category'
        )->where('slug', $slug)
            ->join('categories', 'products.category_id', '=', 'categories.id')->first();
        if (!$product) {
            return redirect(route('welcome'));
        }
        return Inertia::render('ProductDetail', ['product' => $product]);
    }
}
