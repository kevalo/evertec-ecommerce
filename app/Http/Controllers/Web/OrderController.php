<?php

namespace App\Http\Controllers\Web;

use App\Domain\Orders\Models\Order;
use App\Domain\Orders\ViewModels\DetailViewModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function show(Order $order): Response|RedirectResponse
    {
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('home');
        }

        return Inertia::render('Order/Detail', new DetailViewModel($order));
    }
}
