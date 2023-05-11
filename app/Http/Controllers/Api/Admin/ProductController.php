<?php

namespace App\Http\Controllers\Api\Admin;

use App\Definitions\GeneralStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ToggleStatusRequest;
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

        $products = Product::select(
            'products.id',
            'products.name',
            'products.status',
            'price',
            'quantity',
            'categories.name as category'
        )
            ->when($filter, static function ($q) use ($filter) {
                $q->where('products.name', 'like', '%' . $filter . '%');
            })
            ->when($category, static function ($q) use ($category) {
                $q->where('category_id', $category);
            })
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->latest('products.id')->paginate(5);

        return $this->response($products);
    }

    /**
     * @throws \Exception
     */
    public function toggleStatus(ToggleStatusRequest $request): array
    {
        $params = $request->validated();

        $product = Product::find($params['id']);

        $newStatus = match ($product->status) {
            GeneralStatus::ACTIVE => GeneralStatus::INACTIVE->value,
            GeneralStatus::INACTIVE => GeneralStatus::ACTIVE->value,
            default => throw new \Exception('Estado del producto no soportado')
        };

        $product->status = $newStatus;
        $responseStatus = $product->save();

        return $this->response('Producto actualizado', $responseStatus);
    }
}
