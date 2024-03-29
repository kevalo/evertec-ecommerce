<?php

namespace App\Domain\Users\Models;

use App\Domain\Orders\Models\Order;
use App\Support\Definitions\UserStatus;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string $name
 * @property string $last_name
 * @property string $phone
 * @property UserStatus|null $status
 * @property string $email
 * @property int $role_id
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'phone',
        'status',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => match ($value) {
                UserStatus::PENDING->value => UserStatus::PENDING,
                UserStatus::ACTIVE->value => UserStatus::ACTIVE,
                UserStatus::INACTIVE->value => UserStatus::INACTIVE,
                default => null
            }
        );
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Roles::class, 'role_id', 'id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }
}
