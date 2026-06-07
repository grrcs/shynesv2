<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'image',
        'title',
        'slug',
        'description',
        'price',
        'stock',
        'weight',
        'link_shopee',
        'status',
        'discount_price',
        'discount_limit',
        'is_discount_active',
    ];

    protected $casts = [
        'price' => 'integer',
        'stock' => 'integer',
        'weight' => 'integer',
        'discount_price' => 'decimal:2',
        'discount_limit' => 'integer',
        'is_discount_active' => 'boolean',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the product media.
     */
    public function media()
    {
        return $this->hasMany(ProductMedia::class);
    }

    /**
     * Get the product variants.
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Get the cart items for the product.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include products with stock.
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Get the final price (considering discount).
     */
    public function getFinalPriceAttribute()
    {
        if ($this->is_discount_active && $this->discount_price) {
            return $this->discount_price;
        }
        return $this->price;
    }

    /**
     * Check if product has active discount.
     */
    public function hasDiscount()
    {
        return $this->is_discount_active && $this->discount_price && $this->discount_price < $this->price;
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
