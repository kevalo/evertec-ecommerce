<?php

namespace App\Support\Definitions;

enum OrderStatus: string
{
    case CREATED = 'created';
    case CANCELED = 'canceled';
    case COMPLETED = 'completed';
}
