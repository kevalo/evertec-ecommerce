<?php

namespace App\Domain\Payments\Actions;

use App\Domain\Payments\Models\Payment;
use App\Support\Actions\Action;
use App\Support\Definitions\PaymentStatus;

class StorePayment implements Action
{
    /**
     * @param array $params
     * @return bool
     */
    public static function execute(array $params): bool
    {
        $payment = new Payment();
        $payment->request_id = $params['requestId'];
        $payment->process_url = $params['processUrl'];
        $payment->payment_type = $params['payment_type'];
        $payment->status = PaymentStatus::CREATED->value;
        $payment->order_id = $params['order_id'];
        return $payment->save();
    }
}
