<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerProduct extends Model
{
    protected $fillable = [
        'seller_contract_id',
        'category_id',
        'title',
        'slug',
        'description',
        'image',
        'base_price',
        'markup_percentage',
        'final_price',
        'stock',
        'weight',
        'status',
        'admin_notes',
        'product_id',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'markup_percentage' => 'decimal:2',
        'final_price' => 'decimal:2',
        'stock' => 'integer',
        'weight' => 'integer',
    ];

    public function sellerContract()
    {
        return $this->belongsTo(SellerContract::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function calculateFinalPrice(): float
    {
        return $this->base_price * (1 + $this->markup_percentage / 100);
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('images/campaign/shyness_vol_1.png');
        }
        $storagePath = 'products/' . $this->image;
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($storagePath)) {
            return \Illuminate\Support\Facades\Storage::url($storagePath);
        }
        return asset('images/products/' . $this->image);
    }
}
