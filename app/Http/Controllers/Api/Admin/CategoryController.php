<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\Admin\Product\DisableProductsByCategory;
use App\Definitions\GeneralStatus;
use App\Exceptions\UnsupportedStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\ToggleStatusRequest;
use App\Http\Resources\ApiResource;
use App\Models\Category;
use App\Traits\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    use ApiController;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(new ApiResource(Category::latest('id')->paginate(5)));
    }

    public function toggleStatus(ToggleStatusRequest $request): JsonResponse
    {
        $params = $request->validated();

        try {
            $category = Category::find($params['id']);

            $newStatus = match ($category->status) {
                GeneralStatus::ACTIVE => GeneralStatus::INACTIVE->value,
                GeneralStatus::INACTIVE => GeneralStatus::ACTIVE->value,
                default => throw new UnsupportedStatus('Estado de la categoría no soportado: ' . $category->status)
            };

            $category->status = $newStatus;
            $category->save();

            $responseData = 'Categoría actualizada';

            if ($newStatus === GeneralStatus::INACTIVE->value) {
                DisableProductsByCategory::execute(['category_id' => $category->id]);
            }
        } catch (UnsupportedStatus $e) {
            $responseData = 'Error al actualizarla categoría';
            Log::error($e->getMessage(), ['context' => 'Updating category status']);
        }

        return response()->json(new ApiResource([$responseData]));
    }
}
