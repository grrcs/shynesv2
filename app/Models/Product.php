<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
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

    /**
     * Relasi: Setiap produk punya satu kategori.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function media()
    {
        return $this->hasMany(ProductMedia::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function getActiveVariantsAttribute()
    {
        return $this->variants()->where('is_active', true)->get();
    }
}
