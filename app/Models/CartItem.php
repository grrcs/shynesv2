<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'product_variant_id', 'quantity'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function getDisplayNameAttribute()
    {
        $name = $this->product->title;
        
        if ($this->variant) {
            $name .= ' (' . $this->variant->display_name . ')';
        }
        
        return $name;
    }

    public function getFinalPriceAttribute()
    {
        // Use variant price if available
        if ($this->variant) {
            return $this->variant->final_price;
        }

        // Otherwise use product price
        $product = $this->product;
        if ($product->is_discount_active && $product->discount_price) {
            return $product->discount_price;
        }

        return $product->price;
    }

    public function getTotalPriceAttribute()
    {
        return $this->final_price * $this->quantity;
    }

    public function isInStock()
    {
        // Check variant stock if variant exists
        if ($this->variant) {
            return $this->variant->isInStock() && $this->variant->stock >= $this->quantity;
        }

        // Otherwise check product stock
        $product = $this->product;
        return $product->stock >= $this->quantity;
    }
}
