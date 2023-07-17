<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Dashboard\ReportsRequest;
use App\Http\Resources\Api\StandardResource;
use App\Support\Definitions\OrderStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function reports(ReportsRequest $request): JsonResponse
    {
        $startDate = date('Y-m-d 00:00:00', strtotime($request->validated('start_date')));
        $endDate = date('Y-m-d 23:59:59', strtotime($request->validated('end_date')));

        return response()->json(
            new StandardResource([
                'best_products' => $this->bestProducts($startDate, $endDate),
                'best_categories' => $this->bestCategories($startDate, $endDate),
                'desired_products' => $this->desiredProducts($startDate, $endDate),
                'best_customers' => $this->bestCustomers($startDate, $endDate),
                'orders_status' => $this->ordersByStatus($startDate, $endDate),
                'payments_status' => $this->paymentsByStatus($startDate, $endDate)
            ])
        );
    }

    private function bestProducts(string $startDate, string $endDate): array
    {
        return DB::table('products')
            ->selectRaw('products.name as label, sum(order_products.quantity) as value')
            ->join('order_products', 'products.id', '=', 'order_products.product_id')
            ->join('orders', 'order_products.order_id', '=', 'orders.id')
            ->where('orders.status', '=', OrderStatus::COMPLETED->value)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('products.name')
            ->orderByRaw('value desc')
            ->limit(10)
            ->get()->toArray();
    }

    private function bestCategories(string $startDate, string $endDate): array
    {
        return DB::table('categories')
            ->selectRaw('categories.name as label, count(products.id) as value')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_products', 'products.id', '=', 'order_products.product_id')
            ->join('orders', 'order_products.order_id', '=', 'orders.id')
            ->where('orders.status', '=', OrderStatus::COMPLETED->value)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('categories.name')
            ->orderByRaw('value desc')
            ->limit(10)
            ->get()->toArray();
    }

    private function desiredProducts(string $startDate, string $endDate): array
    {
        return DB::table('products')
            ->selectRaw('products.name as label, sum(order_products.quantity) as value')
            ->join('order_products', 'products.id', '=', 'order_products.product_id')
            ->join('orders', 'order_products.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('products.name')
            ->orderByRaw('value desc')
            ->limit(10)
            ->get()->toArray();
    }

    private function bestCustomers(string $startDate, string $endDate): array
    {
        return DB::table('orders')
            ->selectRaw('email as label, count(orders.id) as value')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->where('orders.status', '=', OrderStatus::COMPLETED->value)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupByRaw('orders.user_id, email')
            ->orderByRaw('value desc')
            ->limit(10)
            ->get()->toArray();
    }

    private function ordersByStatus(string $startDate, string $endDate): array
    {
        return DB::table('orders')
            ->selectRaw('status as label, count(id) as value')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupByRaw('orders.status')
            ->orderByRaw('value desc')
            ->get()->map(function ($item) {
                $item->label = __('orders.status')[$item->label];
                return $item;
            })->toArray();
    }

    private function paymentsByStatus(string $startDate, string $endDate): array
    {
        return DB::table('payments')
            ->selectRaw('status as label, count(id) as value')
            ->whereBetween('payments.created_at', [$startDate, $endDate])
            ->groupByRaw('payments.status')
            ->orderByRaw('value desc')
            ->get()->map(function ($item) {
                $item->label = __('payments.status')[$item->label];
                return $item;
            })->toArray();
    }
}
