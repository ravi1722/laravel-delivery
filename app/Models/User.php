<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'role',
        'is_active',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at'     => 'datetime',
            'is_active'         => 'boolean',
        ];
    }

    // relationships
    // public function restaurant(): HasOne
    // {
    //     return $this->hasOne(Restaurant::class, "owner_id");
    // }

    // public function addresses(): HasMany
    // {
    //     return $this->hasMany(Address::class);
    // }

    // public function orders(): HasMany
    // {
    //     return $this->hasMany(Order::class);
    // }

    // public function wallet(): HasMany
    // {
    //     return $this->hasMany(Wallet::class);
    // }

    // public function deliveryAgent(): HasOne
    // {
    //     return $this->hasOne(DeliveryAgent::class);
    // }

    // public function reviews(): HasMany
    // {
    //     return $this->hasMany(Review::class);
    // }

    // Helper methods 
    // public function isAdmin(): bool
    // {
    //     return $this->role === 'admin';
    // }

    // public function isRestaurantOwner(): bool
    // {
    //     return $this->role === 'restaurant_owner';
    // }

    // public function isDeliveryAgent(): bool
    // {
    //     return $this->role === 'delivery_agent';
    // }

    // public function isCustomer(): bool
    // {
    //     return $this->role === 'customer';
    // }

    // attribute
    public function getAvatarAttiribute() : string
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : asset('images/default-avatar.png');
    }
}
