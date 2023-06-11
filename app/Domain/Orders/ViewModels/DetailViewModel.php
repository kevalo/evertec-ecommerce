<?php

namespace App\Domain\Orders\ViewModels;

use App\Domain\Orders\Models\Payment;
use App\Support\Definitions\OrderStatus;
use App\Support\Definitions\PaymentStatus;
use App\Support\ViewModels\ViewModel;

class DetailViewModel extends ViewModel
{
    public function toArray(): array
    {
        //permite generar pago: orden creada
        // no hay pago
        // rechazado
        // cancelado
        $newPayment = false;
        $currentPaymentUrl = false;

        if ($this->model->status === OrderStatus::CREATED->value) {
            $payment = Payment::select('id', 'status', 'process_url')
                ->where('order_id', $this->model->id)
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
            'order' => $this->model,
            'products' => $this->model->products,
            'status' => OrderStatus::toArray(),
            'newPayment' => $newPayment,
            'currentPaymentUrl' => $currentPaymentUrl
        ];
    }
}
