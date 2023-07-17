<?php

namespace App\Contracts;

abstract class PaymentInterface
{
    abstract public function pay(): bool|array;
    abstract public function getPaymentStatus(string $requestId): string;

    abstract public function setUpPayment(int $orderId): static;
}
