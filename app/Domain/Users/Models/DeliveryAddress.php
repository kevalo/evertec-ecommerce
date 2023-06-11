<?php

namespace App\Domain\Users\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property float $address
 * @property int $user_id
 */
class DeliveryAddress extends Model
{
    use HasFactory;
}
