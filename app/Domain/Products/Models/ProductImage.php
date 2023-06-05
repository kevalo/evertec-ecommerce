<?php

namespace App\Domain\Products\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $original_name
 * @property string $mime_type
 * @property string $path
 * @property int $product_id
 */
class ProductImage extends Model
{
    use HasFactory;
}
