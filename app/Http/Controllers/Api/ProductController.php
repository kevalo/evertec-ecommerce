<?php

namespace App\Http\Controllers\Api;

use App\Definitions\GeneralStatus;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\ApiController;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiController;

    public function index(Request $request): array
    {
        $filter = $request->get('filter');
        $category = $request->get('category');

        $customersList = Product::select(
            'products.name',
            'description',
            'image',
            'price',
            'categories.name as category'
        )
            ->where('products.status', GeneralStatus::ACTIVE->value)
            ->when($filter, static function ($q) use ($filter) {
                $q->where('products.name', 'like', '%' . $filter . '%');
            })
            ->when($category, static function ($q) use ($category) {
                $q->where('category_id', $category);
            })
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->latest('products.id')->paginate(5);

        return $this->response($customersList);
    }
}
