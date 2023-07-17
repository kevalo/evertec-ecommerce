<?php

namespace App\Http\Controllers\Web;

use App\Domain\Orders\Actions\UpdateOrderStatus;
use App\Domain\Orders\Models\Order;
use App\Domain\Orders\ViewModels\DetailViewModel;
use App\Domain\Payments\Actions\GetOrderPayments;
use App\Domain\Payments\Actions\UpdatePaymentStatus;
use App\Domain\Payments\Models\Payment;
use App\Http\Controllers\Controller;
use App\Support\Definitions\OrderStatus;
use App\Support\Definitions\PaymentStatus;
use App\Support\Definitions\Roles;
use App\Support\Services\PaymentFactory;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    /**
     * @throws Exception
     */
    public function show(Order $order, PaymentFactory $paymentFactory): Response|RedirectResponse
    {
        $isAdmin = Auth::user()->role_id === Roles::ADMIN->value;

        if (!$isAdmin && $order->user_id !== Auth::id()) {
            return redirect()->route('home');
        }

        /**
         * @var Payment $payment
         */
        $payment = GetOrderPayments::execute(['order_id' => $order->id]);

        if (!$isAdmin && $payment) {
            $processor = $paymentFactory->initializePayment($payment->payment_type);
            $status = $processor->getPaymentStatus((string)$payment->request_id);
            UpdatePaymentStatus::execute(['id' => $payment->id, 'status' => $status]);

            if ($status === PaymentStatus::APPROVED->value) {
                UpdateOrderStatus::execute(['id' => $order->id, 'status' => OrderStatus::COMPLETED->value]);
                $order->refresh();
            }
        }

        return Inertia::render('Order/Detail', new DetailViewModel($order));
    }
}
