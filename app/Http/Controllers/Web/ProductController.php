<?php

namespace App\Http\Controllers\Web;

use App\Domain\Products\Models\Product;
use App\Domain\Products\ViewModels\ProductDetailViewModel;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function show(Product $product): Response
    {
        return Inertia::render('ProductDetail', new ProductDetailViewModel($product));
    }
}
