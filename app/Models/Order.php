<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 
        'payment_option_id',
        'total_price', 
        'tax_amount',
        'status', 
        'invoice_number',
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
}
