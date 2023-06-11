<?php

namespace App\Support\Services;

use App\Contracts\PaymentInterface;
use App\Support\Definitions\PaymentMethods;
use App\Support\Exceptions\UnsupportedPaymentMethod;
use Exception;

class PaymentFactory
{
    /**
     * @throws Exception
     */
    public function initializePayment(string $type): PaymentInterface
    {
        return match ($type) {
            PaymentMethods::PLACE_TO_PAY->value => new PlaceToPayPayment(),
            default => throw new UnsupportedPaymentMethod("The $type payment method is not supported")
        };
    }
}
