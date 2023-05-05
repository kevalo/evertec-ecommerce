<?php

namespace App\Http\Controllers\Api;

use App\Definitions\GeneralStatus;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiController;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiController;

    public function index(Request $request): array
    {
        $filtered = $request->has('filter');
        $filter = $request->get('filter');

        $customersList = Product::select('products.name', 'image', 'price', 'categories.name as category')
            ->where('products.status', GeneralStatus::ACTIVE->value)
            ->when($filtered && $filter, static function ($q) use ($filter) {
                $q->where('products.name', 'like', '%' . $filter . '%');
            })
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->latest('products.id')->paginate(5);

        return $this->response($customersList);
    }
}
