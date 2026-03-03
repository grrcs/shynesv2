<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type',
        'discount_value',
        'minimum_order_amount',
        'usage_limit',
        'usage_limit_per_user',
        'usage_count',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_limit_per_user' => 'integer',
        'usage_count' => 'integer',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        
        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }
        
        if ($this->valid_until && $now->gt($this->valid_until)) {
            return false;
        }

        if ($this->usage_limit !== null && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function isValidForUser($userId): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        if ($this->usage_limit_per_user !== null) {
            $userUsageCount = $this->usages()
                ->where('user_id', $userId)
                ->count();
                
            if ($userUsageCount >= $this->usage_limit_per_user) {
                return false;
            }
        }

        return true;
    }

    public function isValidForOrder($orderTotal): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        if ($this->minimum_order_amount !== null && $orderTotal < $this->minimum_order_amount) {
            return false;
        }

        return true;
    }

    public function calculateDiscount($orderTotal): float
    {
        if (!$this->isValidForOrder($orderTotal)) {
            return 0;
        }

        if ($this->discount_type === 'percentage') {
            return ($orderTotal * $this->discount_value) / 100;
        } else {
            return min($this->discount_value, $orderTotal);
        }
    }

    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    public function markAsUsedBy($userId, $orderId): void
    {
        $this->incrementUsage();
        
        $this->usages()->create([
            'user_id' => $userId,
            'order_id' => $orderId,
        ]);
    }
}
