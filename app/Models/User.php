<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is pembeli.
     */
    public function isPembeli(): bool
    {
        return $this->role === 'pembeli';
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function loyaltyPoints()
    {
        return $this->hasMany(LoyaltyPoint::class);
    }

    public function getTotalPointsAttribute()
    {
        return $this->loyaltyPoints()->active()->sum('points');
    }

    public function getPointsValueAttribute()
    {
        return LoyaltyPoint::getPointsValue($this->total_points);
    }

    public function addPoints($points, $orderId = null, $description = null)
    {
        return $this->loyaltyPoints()->create([
            'order_id' => $orderId,
            'points' => $points,
            'type' => 'earned',
            'description' => $description,
            'expires_at' => now()->addYear(), // Points expire in 1 year
        ]);
    }

    public function redeemPoints($points, $description = null)
    {
        if ($this->total_points < $points) {
            throw new \Exception('Poin tidak cukup!');
        }

        return $this->loyaltyPoints()->create([
            'points' => -$points,
            'type' => 'redeemed',
            'description' => $description ?? 'Penukaran poin',
        ]);
    }

    public function hasEnoughPoints($points)
    {
        return $this->total_points >= $points;
    }

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
        ];
    }
}
