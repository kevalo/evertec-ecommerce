<?php

namespace App\Http\Controllers\Api\Admin;

use App\Definitions\GeneralStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ToggleStatusRequest;
use App\Http\Traits\ApiController;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiController;
    public function index(Request $request)
    {
        $filtered = $request->has('filter');
        $filter = $request->get('filter');

        $products = Product::when($filtered && $filter, static function ($q) use ($filter) {
            $q->where('name', 'like', '%' . $filter . '%');
        })->latest('id')->paginate(5);

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
