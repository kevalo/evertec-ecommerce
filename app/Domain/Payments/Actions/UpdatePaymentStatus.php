<?php

namespace App\Domain\Payments\Actions;

use App\Domain\Payments\Models\Payment;
use App\Support\Actions\Action;

class UpdatePaymentStatus implements Action
{
    /**
     * @param array $params
     * @return bool|int
     */
    public static function execute(array $params): bool|int
    {
        return Payment::where('id', $params['id'])->update(['status' => $params['status']]);
    }
}
