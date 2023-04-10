<?php

namespace App\Models;

use App\Definitions\UserStatus;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

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

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => match ($value) {
                UserStatus::PENDING->value => UserStatus::PENDING,
                UserStatus::ACTIVE->value => UserStatus::ACTIVE,
                UserStatus::INACTIVE->value => UserStatus::INACTIVE
            }
        );
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Roles::class, 'role_id', 'id');
    }
}
