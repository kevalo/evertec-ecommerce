<?php

namespace App\Http\Controllers\Api;

use App\Domain\Products\Actions\StoreProduct;
use App\Domain\Products\Actions\UpdateProduct;
use App\Domain\Products\Models\Product;
use App\Domain\Users\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Product\CheckStockRequest;
use App\Http\Requests\Api\Product\GetCartProductsRequest;
use App\Http\Requests\Api\Product\ShowProductRequest;
use App\Http\Requests\Web\Product\CreateRequest;
use App\Http\Requests\Web\Product\UpdateRequest;
use App\Http\Resources\Api\StandardResource;
use App\Support\Definitions\GeneralStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function getToken(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        return response()->json(new StandardResource(['token' => $user->createToken("API TOKEN")->plainTextToken]));
    }

    public function index(Request $request): JsonResponse
    {
        $filter = $request->get('filter');
        $category = $request->get('category');

        $products = Product::select(
            'products.id',
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

    public function show(ShowProductRequest $request): JsonResponse
    {
        $id = $request->validated('id');

        $product = Product::select(
            'products.id',
            'products.name',
            'products.status',
            'description',
            'quantity',
            'slug',
            'image',
            'price',
            'categories.name as category'
        )
            ->where('products.id', $id)
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->first();

        return response()->json(new StandardResource($product));
    }

    public function store(CreateRequest $request): JsonResponse
    {
        if (StoreProduct::execute($request->validated())) {
            return response()->json(new StandardResource(['status' => true, 'msg' => 'Product created']));
        }

        return response()->json(new StandardResource(['status' => false, 'msg' => 'Error']));
    }

    public function update(UpdateRequest $request): JsonResponse
    {
        $id = $request->validated('id');
        $fields = $request->validated();
        unset($fields['id']);
        $params = [
            'fields' => $fields,
            'product' => Product::find($id)
        ];

        if (UpdateProduct::execute($params)) {
            return response()->json(new StandardResource(['status' => true, 'msg' => 'Product updated']));
        }

        return response()->json(new StandardResource(['status' => false, 'msg' => 'Error']));
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
