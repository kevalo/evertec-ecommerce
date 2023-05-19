<?php

namespace App\Http\Controllers\Api\Admin;

use App\Definitions\GeneralStatus;
use App\Exceptions\UnsupportedStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ToggleStatusRequest;
use App\Http\Resources\ApiResource;
use App\Models\Product;
use App\Traits\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    use ApiController;

    public function index(Request $request): JsonResponse
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

        return response()->json(new ApiResource($products));
    }


    public function toggleStatus(ToggleStatusRequest $request): JsonResponse
    {
        $params = $request->validated();

        try {
            $product = Product::find($params['id']);

            $newStatus = match ($product->status) {
                GeneralStatus::ACTIVE => GeneralStatus::INACTIVE->value,
                GeneralStatus::INACTIVE => GeneralStatus::ACTIVE->value,
                default => throw new UnsupportedStatus('Estado del producto no soportado: ' . $product->status)
            };

            $product->status = $newStatus;
            $product->save();
            $responseData = 'Producto actualizado';
        } catch (UnsupportedStatus $e) {
            $responseData = 'Error al actualizar el producto';
            Log::error($e->getMessage(), ['context' => 'Updating customer status']);
        }

        return response()->json(new ApiResource($responseData));
    }
}
