<?php

namespace App\Support\Definitions;

enum PaymentStatus: string
{
    case CREATED = 'CREATED';
    case PENDING = 'PENDING';
    case REJECTED = 'REJECTED';
    case APPROVED_PARTIAL = 'APPROVED_PARTIAL';
    case PARTIAL_EXPIRED = 'PARTIAL_EXPIRED';
    case FAILED = 'FAILED';
    case APPROVED = 'APPROVED';
}
