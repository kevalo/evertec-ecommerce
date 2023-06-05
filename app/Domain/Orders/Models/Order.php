<?php

namespace App\Domain\Orders\Models;

use App\Support\Definitions\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $code
 * @property OrderStatus|null $status
 * @property float $total_price
 * @property string $delivery_address
 * @property int $user_id
 */
class Order extends Model
{
    use HasFactory;
}
