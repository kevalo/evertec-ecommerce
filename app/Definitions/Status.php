<?php

namespace App\Definitions;

enum Status: string
{
    case PENDING = 'pending';
    case INACTIVE = 'inactive';
    case ACTIVE = 'active';
}
