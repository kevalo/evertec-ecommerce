<?php

namespace App\Domain\Orders\ViewModels;

use App\Domain\Orders\Models\Order;
use App\Domain\Payments\Models\Payment;
use App\Support\Definitions\OrderStatus;
use App\Support\Definitions\PaymentStatus;
use App\Support\ViewModels\ViewModel;

class DetailViewModel extends ViewModel
{
    public function toArray(): array
    {
        $newPayment = false;
        $currentPaymentUrl = false;

        /**
         * @var Order $model
         */
        $model = $this->model;

        if ($model->status === OrderStatus::CREATED->value) {
            $payment = Payment::select('id', 'status', 'process_url')
                ->where('order_id', $model->id)
                ->orderBy('id', 'desc')->first();

            if ($payment) {
                $newPayment = match ($payment->status) {
                    PaymentStatus::REJECTED->value, PaymentStatus::FAILED->value => true,
                    default => false
                };

                if ($payment->status === PaymentStatus::CREATED->value) {
                    $currentPaymentUrl = $payment->process_url;
                }
            } else {
                $newPayment = true;
            }
        }

        // usar enlace existente cuando orden creada y pago creado

        return [
            'order' => $model,
            'products' => $model->products,
            'status' => OrderStatus::toArray(),
            'newPayment' => $newPayment,
            'currentPaymentUrl' => $currentPaymentUrl
        ];
    }
}
