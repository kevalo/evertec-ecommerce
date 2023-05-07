<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\Admin\Product\DisableProductsByCategory;
use App\Definitions\GeneralStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\ToggleStatusRequest;
use App\Models\Category;
use App\Traits\ApiController;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{

    use ApiController;

    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        return $this->response(Category::latest('id')->paginate(5));
    }

    public function toggleStatus(ToggleStatusRequest $request): array
    {
        $params = $request->validated();

        $responseStatus = false;

        try {
            $category = Category::find($params['id']);

            $newStatus = match ($category->status) {
                GeneralStatus::ACTIVE => GeneralStatus::INACTIVE->value,
                GeneralStatus::INACTIVE => GeneralStatus::ACTIVE->value,
                default => throw new \Exception('Estado de la categoría no soportado')
            };

            $category->status = $newStatus;
            $responseStatus = $category->save();
            $responseData = 'Categoría actualizada';

            if ($newStatus === GeneralStatus::INACTIVE->value) {
                DisableProductsByCategory::execute(['category_id' => $category->id]);
            }

        } catch (\Exception $e) {
            $responseData = 'Error al actualizar el usuario';
            Log::error($e->getMessage(), ['context' => 'Updating category status']);
        }

        return $this->response($responseData, $responseStatus);
    }

}
