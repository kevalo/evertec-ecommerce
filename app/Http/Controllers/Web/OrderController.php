<?php

namespace App\Http\Controllers\Web;

use App\Domain\Orders\Actions\StoreOrder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Order\CreateRequest;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    public function store(CreateRequest $request): RedirectResponse
    {
        StoreOrder::execute($request->validated()['products']);

        return redirect()->route('home');
    }
}
