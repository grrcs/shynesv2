<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'payment_option_id',
        'coupon_id',
        'total_price',
        'tax_amount',
        'discount_amount',
        'status',
        'invoice_number',
        'payment_reference',
        'payment_channel',
        'payment_token',
        'payment_url',
        'shipping_recipient_name',
        'shipping_phone_number',
        'shipping_address',
        'shipping_city',
        'shipping_province',
        'shipping_postal_code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function paymentOption()
    {
        return $this->belongsTo(PaymentOption::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('changed_at', 'desc');
    }

    public function shippingDetail()
    {
        return $this->hasOne(ShippingDetail::class);
    }

    public function getCurrentStatusAttribute()
    {
        $latestStatus = $this->statusHistory()->first();
        return $latestStatus ? $latestStatus->status : $this->status;
    }

    public function updateStatus(string $newStatus, string $notes = null)
    {
        $this->update(['status' => $newStatus]);
        
        return $this->statusHistory()->create([
            'status' => $newStatus,
            'notes' => $notes,
            'changed_at' => now(),
        ]);
    }
}
