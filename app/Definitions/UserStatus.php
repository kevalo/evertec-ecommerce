<?php

namespace App\Definitions;

enum UserStatus: string
{
    case PENDING = 'pending';
    case INACTIVE = 'inactive';
    case ACTIVE = 'active';
}
