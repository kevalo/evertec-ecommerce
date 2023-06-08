<?php

namespace App\Domain\Orders\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $order_id
 * @property int $product_id
 * @property float $price
 * @property int $quantity
 * @property float $total
 */
class OrderProduct extends Model
{
    use HasFactory;
}
