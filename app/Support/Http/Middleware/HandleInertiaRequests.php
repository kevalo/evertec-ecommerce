<?php

namespace App\Support\Http\Middleware;

use App\Support\Definitions\GeneralStatus;
use App\Support\Definitions\Roles;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
                'admin' => Roles::ADMIN->value,
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy())->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'flash' => [
                'success' => session('success') ?: '',
                'error' => session('error') ?: '',
            ],
            'GeneralStatus' => GeneralStatus::toArray(),
            '$t' => [
                'labels' => __('labels'),
                'fields' => __('fields'),
                'products' => __('products'),
                'customers' => __('customers'),
                'categories' => __('categories'),
                'cart' => __('cart'),
                'orders' => __('orders'),
                'auth' => __('auth')
            ]
        ]);
    }
}
