<?php

namespace App\Http\Controllers\Api;

use App\Domain\Products\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Product\CheckStockRequest;
use App\Http\Requests\Api\Product\GetCartProductsRequest;
use App\Http\Resources\Api\StandardResource;
use App\Support\Definitions\GeneralStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $filter = $request->get('filter');
        $category = $request->get('category');

        $products = Product::select(
            'products.name',
            'description',
            'slug',
            'image',
            'price',
            'categories.name as category'
        )
            ->where('products.status', GeneralStatus::ACTIVE->value)
            ->where('products.quantity', '>', 0)
            ->when($filter, static function ($q) use ($filter) {
                $q->where('products.name', 'like', '%' . $filter . '%')
                    ->orWhere('products.description', 'like', '%' . $filter . '%');
            })
            ->when($category, static function ($q) use ($category) {
                $q->where('category_id', $category);
            })
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->latest('products.id')->paginate(8);

        return response()->json(new StandardResource($products));
    }

    public function getProductsForCart(GetCartProductsRequest $request): JsonResponse
    {
        $ids = $request->post('ids');

        $products = Product::select('id', 'name', 'slug', 'image', 'price', 'quantity')->whereIn('id', $ids)->get();

        return response()->json(new StandardResource($products));
    }

    public function checkStock(CheckStockRequest $request): JsonResponse
    {
        $params = $request->validated();
        $product = Product::find($params['id']);

        return response()->json(new StandardResource(['stock' => $params['amount'] <= $product->quantity]));
    }
}
