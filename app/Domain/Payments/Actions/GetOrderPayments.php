<?php

namespace App\Domain\Payments\Actions;

use App\Domain\Payments\Models\Payment;
use App\Support\Actions\Action;
use App\Support\Definitions\PaymentStatus;
use Illuminate\Database\Eloquent\Model;

class GetOrderPayments implements Action
{
    /**
     * @param array $params
     * @return bool|int|Model
     */
    public static function execute(array $params): bool|int|Model
    {
        $orderID = $params['order_id'];

        $payment = Payment::select('id', 'request_id', 'payment_type')
            ->where('order_id', $orderID)
            ->whereIn('status', [
                PaymentStatus::CREATED->value,
                PaymentStatus::PENDING->value,
                PaymentStatus::APPROVED_PARTIAL->value,
                PaymentStatus::PARTIAL_EXPIRED->value,
                PaymentStatus::REJECTED->value,
                PaymentStatus::FAILED->value
            ])
            ->orderBy('id', 'desc')
            ->first();

        return $payment ?: false;
    }
}
