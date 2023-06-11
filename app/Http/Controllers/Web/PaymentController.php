<?php

namespace App\Http\Controllers\Web;

use App\Domain\Orders\Actions\ValidateOrderStatus;
use App\Domain\Payments\Actions\StorePayment;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Payment\CreatePaymentRequest;
use App\Support\Services\PaymentFactory;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Application as ApplicationB;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    /**
     * @throws Exception
     */
    public function create(
        CreatePaymentRequest $request,
        PaymentFactory $paymentFactory
    ): Application|RedirectResponse|ApplicationB {
        if (!ValidateOrderStatus::execute($request->validated())) {
            redirect()->route('orders.index');
        }

        $processor = $paymentFactory->initializePayment($request->get('payment_type'));
        $data = $processor->setUpPayment($request)->pay();
        $data['order_id'] = $request->validated('order_id');
        $data['payment_type'] = $request->validated('payment_type');
        StorePayment::execute($data);

        return redirect($data['processUrl']);
    }
}
