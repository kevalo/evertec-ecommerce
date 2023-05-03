<?php

namespace App\Http\Controllers\Api\Admin;

use App\Definitions\GeneralStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\ToggleStatusRequest;
use App\Http\Traits\ApiController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{

    use ApiController;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest('id')->paginate(5);
        return $this->response($categories);
    }

    public function toggleStatus(ToggleStatusRequest $request): array
    {
        $params = $request->validated();

        $responseStatus = false;

        try {
            $category = Category::find($params['id']);

            if (!$category) {
                return $this->response('No se encontró la categoría', false);
            }

            $newStatus = match ($category->status) {
                GeneralStatus::ACTIVE => GeneralStatus::INACTIVE->value,
                GeneralStatus::INACTIVE => GeneralStatus::ACTIVE->value,
                default => throw new \Exception('Estado de la categoría no soportado')
            };

            $category->status = $newStatus;
            $responseStatus = $category->save();
            $responseData = 'Categoría actualizada';
        } catch (\Exception $e) {
            $responseData = 'Error al actualizar el usuario';
            Log::error($e->getMessage(), ['context' => 'Updating category status']);
        }

        return $this->response($responseData, $responseStatus);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
