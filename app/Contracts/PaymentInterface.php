<?php

namespace App\Contracts;

use App\Http\Requests\Web\Payment\CreatePaymentRequest;

interface PaymentInterface
{
    public function pay(CreatePaymentRequest $request): bool|array;
}
