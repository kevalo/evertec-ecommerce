<?php

namespace App\Models;

use App\Definitions\GeneralStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'precio',
        'quantity',
        'status',
        'category_id'
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
}
