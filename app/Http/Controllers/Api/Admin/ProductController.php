<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Products\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Product\ToggleStatusRequest;
use App\Http\Resources\Api\StandardResource;
use App\Support\Definitions\GeneralStatus;
use App\Support\Exceptions\UnsupportedStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
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

        return response()->json(new StandardResource($products));
    }

    public function toggleStatus(ToggleStatusRequest $request): JsonResponse
    {
        $params = $request->validated();
        $product = Product::find($params['id']);

        try {
            $newStatus = match ($product->status) {
                GeneralStatus::ACTIVE => GeneralStatus::INACTIVE->value,
                GeneralStatus::INACTIVE => GeneralStatus::ACTIVE->value,
                default => throw new UnsupportedStatus(__('products.error_status_update'))
            };

            $product->status = $newStatus;
            $product->save();
            $responseData = __('products.success_update');
        } catch (UnsupportedStatus $e) {
            $responseData = $e->getMessage();
            Log::error($e->getMessage(), ['context' => 'Updating customer status', 'value' => $product->status]);
        }

        return response()->json(new StandardResource([$responseData]));
    }
}
