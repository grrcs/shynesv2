<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DistributorContract extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'contract_code',
        'supplier_id',
        'distributor_company',
        'distributor_contact',
        'contract_start_date',
        'contract_end_date',
        'contract_value',
        'status',
        'file_path',
        'encryption_key_hash',
        'tenant_id',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('tenant', function ($query) {
            if ($tenantId = app(\App\Services\TenantContext::class)->get()) {
                $query->where('tenant_id', $tenantId);
            }
        });
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($contract) {
            if (!$contract->contract_code) {
                $last = static::withTrashed()
                    ->where('supplier_id', $contract->supplier_id)
                    ->orderBy('id', 'desc')
                    ->first();

                $number = $last ? ((int) substr($last->contract_code, 2)) + 1 : 1;
                $contract->contract_code = 'DK' . str_pad($number, 2, '0', STR_PAD_LEFT);
            }
        });
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
