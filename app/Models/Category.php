<?php

namespace App\Models;

use App\Definitions\GeneralStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $name
 * @property GeneralStatus|null $status
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn(int $value) => match ($value) {
                GeneralStatus::ACTIVE->value => GeneralStatus::ACTIVE,
                GeneralStatus::INACTIVE->value => GeneralStatus::INACTIVE,
                default => null
            },
            set: static function ($value) {
                return $value ? GeneralStatus::ACTIVE->value : GeneralStatus::INACTIVE->value;
            }
        );
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
