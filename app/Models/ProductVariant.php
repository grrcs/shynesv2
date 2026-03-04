<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'size',
        'color',
        'price',
        'stock',
        'sku',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getDisplayNameAttribute()
    {
        $parts = [];
        if ($this->size) {
            $parts[] = "Size: {$this->size}";
        }
        if ($this->color) {
            $parts[] = "Color: {$this->color}";
        }
        
        return implode(', ', $parts) ?: 'Default';
    }

    public function getFinalPriceAttribute()
    {
        // Use variant price if set, otherwise use product price with discount
        if ($this->price !== null) {
            return $this->price;
        }

        $product = $this->product;
        if ($product->is_discount_active && $product->discount_price) {
            return $product->discount_price;
        }

        return $product->price;
    }

    public function isInStock()
    {
        return $this->stock > 0 && $this->is_active;
    }

    public function decrementStock($quantity)
    {
        if ($this->stock < $quantity) {
            throw new \Exception("Stok varian {$this->display_name} tidak mencukupi!");
        }

        $this->decrement('stock', $quantity);
        
        // Also decrement main product stock
        $this->product->decrement('stock', $quantity);
    }
}
