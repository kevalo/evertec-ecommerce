<?php

namespace App\Domain\Categories\Models;

use App\Support\Definitions\GeneralStatus;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property \App\Support\Definitions\GeneralStatus|null $status
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];

    protected static function newFactory(): Factory
    {
        return CategoryFactory::new();
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => match ($value) {
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
