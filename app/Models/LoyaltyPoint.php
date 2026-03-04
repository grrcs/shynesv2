<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyPoint extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'points',
        'type',
        'description',
        'expires_at',
        'redeemed_at',
    ];

    protected $casts = [
        'points' => 'integer',
        'expires_at' => 'datetime',
        'redeemed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeEarned($query)
    {
        return $query->where('type', 'earned');
    }

    public function scopeRedeemed($query)
    {
        return $query->where('type', 'redeemed');
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        })->whereNull('redeemed_at');
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now())
                     ->whereNull('redeemed_at');
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isActive()
    {
        return !$this->isExpired() && !$this->redeemed_at;
    }

    public function markAsRedeemed()
    {
        $this->update([
            'redeemed_at' => now(),
            'type' => 'redeemed',
        ]);
    }

    public static function calculatePoints($orderTotal)
    {
        // 1 point for every Rp 10,000 spent
        return intval(floor($orderTotal / 10000));
    }

    public static function getPointsValue($points)
    {
        // 1 point = Rp 100 value
        return $points * 100;
    }
}
