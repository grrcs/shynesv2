<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SupplierApprovalController extends Controller
{
    public function pending()
    {
        $pendingSuppliers = Supplier::withoutGlobalScope('tenant')
            ->where('status', 'pending')
            ->with('user')
            ->latest()
            ->paginate(20);

        $approvedSuppliers = Supplier::withoutGlobalScope('tenant')
            ->where('status', 'active')
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('admin.suppliers.pending', compact('pendingSuppliers', 'approvedSuppliers'));
    }

    public function approve(Supplier $supplier)
    {
        if ($supplier->status !== 'pending') {
            return redirect()->back()->with('error', 'Supplier sudah diproses sebelumnya.');
        }

        $tenantId = (string) Str::uuid();

        $supplier->update([
            'status' => 'active',
            'tenant_id' => $tenantId,
            'approved_at' => now(),
        ]);

        $supplier->user->update([
            'role' => 'supplier',
            'tenant_id' => $tenantId,
        ]);

        return redirect()->route('admin.suppliers.pending')
            ->with('success', "Supplier {$supplier->company_name} berhasil disetujui.");
    }

    public function reject(Supplier $supplier)
    {
        if ($supplier->status !== 'pending') {
            return redirect()->back()->with('error', 'Supplier sudah diproses sebelumnya.');
        }

        $supplier->update([
            'status' => 'rejected',
            'rejected_at' => now(),
        ]);

        return redirect()->route('admin.suppliers.pending')
            ->with('success', "Supplier {$supplier->company_name} ditolak.");
    }
}
