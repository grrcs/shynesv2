<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerContract extends Model
{
    protected $fillable = [
        'user_id',
        'business_name',
        'business_description',
        'phone',
        'status',
        'default_markup_percentage',
        'admin_notes',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'default_markup_percentage' => 'decimal:2',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(SellerProduct::class);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
