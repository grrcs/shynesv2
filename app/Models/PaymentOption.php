<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentOption extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'tax_percentage',
        'is_active',
    ];

    protected $casts = [
        'tax_percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
