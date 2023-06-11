<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Categories\Models\Category;
use App\Domain\Products\Actions\DisableProductsByCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Category\ToggleStatusRequest;
use App\Http\Resources\Api\StandardResource;
use App\Support\Definitions\GeneralStatus;
use App\Support\Exceptions\UnsupportedStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(new StandardResource(Category::latest('id')->paginate(5)));
    }

    public function toggleStatus(ToggleStatusRequest $request): JsonResponse
    {
        $params = $request->validated();

        $category = Category::find($params['id']);

        try {
            $newStatus = match ($category->status) {
                GeneralStatus::ACTIVE => GeneralStatus::INACTIVE->value,
                GeneralStatus::INACTIVE => GeneralStatus::ACTIVE->value,
                default => throw new UnsupportedStatus(__('categories.error_status_update'))
            };

            $category->status = $newStatus;
            $category->save();

            $responseData =__('categories.success_update');

            if ($newStatus === GeneralStatus::INACTIVE->value) {
                DisableProductsByCategory::execute(['category_id' => $category->id]);
            }
        } catch (UnsupportedStatus $e) {
            $responseData = $e->getMessage();
            Log::error($e->getMessage(), ['context' => 'Updating category status', 'value' => $category->status]);
        }

        return response()->json(new StandardResource([$responseData]));
    }
}
