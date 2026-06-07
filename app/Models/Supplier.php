<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'contact_person',
        'email',
        'phone',
        'address',
        'status',
        'tenant_id',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('tenant', function ($query) {
            $tenantId = \App\Services\TenantContext::get();
            if ($tenantId && $tenantId !== 'admin') {
                $query->where('tenant_id', $tenantId);
            }
        });
    }

    public function distributorContracts()
    {
        return $this->hasMany(DistributorContract::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
