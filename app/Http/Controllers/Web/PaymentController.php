<?php

namespace App\Http\Controllers\Web;

use App\Domain\Orders\Models\Payment;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Payment\CreatePaymentRequest;
use App\Support\Definitions\PaymentStatus;
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
        //TODO: Validar estado de la orden antes de pagar

        session()->put('payment_type', $request->validated('payment_type'));
        $processor = $paymentFactory->initializePayment($request->get('payment_type'));
        $data = $processor->pay($request);

        $p = new Payment();
        $p->request_id = $data['requestId'];
        $p->process_url = $data['processUrl'];
        $p->status = PaymentStatus::CREATED->value;
        $p->order_id = $request->validated('order_id');
        $p->save();
        return redirect($p->process_url);
    }
}
