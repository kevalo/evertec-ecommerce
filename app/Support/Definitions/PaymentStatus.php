<?php

namespace App\Support\Definitions;

enum PaymentStatus: string
{
    case CREATED = 'created';
    case CANCELED = 'canceled';
    case REJECTED = 'rejected';
    case APPROVED = 'approved';
}
