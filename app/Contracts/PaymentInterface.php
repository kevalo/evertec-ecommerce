<?php

namespace App\Contracts;

use App\Http\Requests\Web\Payment\CreatePaymentRequest;

abstract class PaymentInterface
{
    private array $fields;

    abstract public function pay(): bool|array;
    abstract public function getPaymentStatus(string $requestId): string;

    abstract public function setUpPayment(CreatePaymentRequest $request): static;
}
