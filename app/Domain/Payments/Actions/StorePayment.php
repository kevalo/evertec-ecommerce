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
        $p = new Payment();
        $p->request_id = $params['requestId'];
        $p->process_url = $params['processUrl'];
        $p->payment_type = $params['payment_type'];
        $p->status = PaymentStatus::CREATED->value;
        $p->order_id = $params['order_id'];
        return $p->save();
    }
}
