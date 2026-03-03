<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingDetail extends Model
{
    protected $fillable = [
        'order_id',
        'courier_name',
        'service_type',
        'tracking_number',
        'shipping_cost',
        'sender_name',
        'sender_phone',
        'sender_address',
        'receiver_name',
        'receiver_phone',
        'receiver_address',
        'receiver_city',
        'receiver_province',
        'receiver_postal_code',
        'shipped_at',
        'estimated_delivery_at',
        'delivered_at',
    ];

    protected $casts = [
        'shipping_cost' => 'decimal:2',
        'shipped_at' => 'datetime',
        'estimated_delivery_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getTrackingUrlAttribute()
    {
        if (!$this->courier_name || !$this->tracking_number) {
            return null;
        }

        $courier = strtolower($this->courier_name);
        $trackingNumber = $this->tracking_number;

        // Generate tracking URL based on courier
        switch ($courier) {
            case 'jne':
                return "https://www.jne.co.id/id/tracking/tracking/{$trackingNumber}";
            case 'j&t':
            case 'jnt':
                return "https://www.jtexpress.co.id/index/query/gzquery/{$trackingNumber}";
            case 'sicepat':
                return "https://www.sicepat.com/check-awb/{$trackingNumber}";
            case 'tiki':
                return "https://tiki.id/id/tracking/{$trackingNumber}";
            case 'pos':
            case 'pos indonesia':
                return "https://kirim.posindonesia.co.id/tracking/{$trackingNumber}";
            default:
                return null;
        }
    }
}
