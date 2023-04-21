<?php

namespace App\Http\Controllers\Api\Admin;

use App\Definitions\GeneralStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Category::latest('id')->paginate(5);
    }

    public function toggleStatus(Request $request): array
    {
        $params = $request->validate(['id' => ['required', 'numeric', 'exists:users']]);

        $response = ['status' => false];

        try {
            $category = Category::find($params['id']);

            if (!$category) {
                return $response;
            }

            $newStatus = match ($category->status) {
                GeneralStatus::ACTIVE => GeneralStatus::INACTIVE->value,
                GeneralStatus::INACTIVE => GeneralStatus::ACTIVE->value,
                default => throw new \Exception('Estado de la categoría no soportado')
            };

            $category->status = $newStatus;
            $response['status'] = $category->save();
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['context' => 'Updating category status']);
        }

        return $response;
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
