<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DistributorContract;
use App\Models\Supplier;
use App\Services\ContractEncryptionService;
use App\Services\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContractController extends Controller
{
    protected ContractEncryptionService $encryptionService;

    public function __construct(ContractEncryptionService $encryptionService)
    {
        $this->encryptionService = $encryptionService;
    }

    public function index()
    {
        $user = auth()->user();
        $tenantId = TenantContext::get();

        if ($user->role === 'admin') {
            $contracts = DistributorContract::with('supplier')
                ->where('tenant_id', $tenantId)
                ->latest()
                ->paginate(20);
        } else {
            $supplier = Supplier::where('user_id', $user->id)->first();
            $contracts = collect();
            if ($supplier) {
                $contracts = DistributorContract::with('supplier')
                    ->where('supplier_id', $supplier->id)
                    ->where('tenant_id', $tenantId)
                    ->latest()
                    ->paginate(20);
            }
        }

        return view('admin.contracts.index', compact('contracts'));
    }

    public function create()
    {
        $suppliers = Supplier::where('tenant_id', TenantContext::get())->get();
        return view('admin.contracts.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'distributor_company' => 'required|string|max:255',
            'distributor_contact' => 'required|string|max:255',
            'contract_start_date' => 'required|date',
            'contract_end_date' => 'required|date|after:contract_start_date',
            'contract_value' => 'required|numeric|min:0',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $tenantId = TenantContext::get();

        $encrypted = $this->encryptionService->encryptFile(
            $request->file('file'),
            $tenantId
        );

        $filePath = 'contracts/' . uniqid() . '.enc';
        Storage::put($filePath, base64_decode($encrypted['encrypted']));

        $contract = DistributorContract::create([
            'supplier_id' => $validated['supplier_id'],
            'distributor_company' => $validated['distributor_company'],
            'distributor_contact' => $validated['distributor_contact'],
            'contract_start_date' => $validated['contract_start_date'],
            'contract_end_date' => $validated['contract_end_date'],
            'contract_value' => $validated['contract_value'],
            'file_path' => $filePath,
            'encryption_key_hash' => $encrypted['key_hash'],
            'tenant_id' => $tenantId,
        ]);

        return redirect()->route('admin.contracts.show', $contract)
            ->with('success', 'Kontrak distributor berhasil dibuat.');
    }

    public function show(DistributorContract $contract)
    {
        $tenantId = TenantContext::get();

        if ($contract->tenant_id !== $tenantId) {
            abort(403, 'Unauthorized access to this contract');
        }

        $user = auth()->user();
        if ($user->role !== 'admin') {
            $supplier = Supplier::where('user_id', $user->id)->first();
            if (!$supplier || $contract->supplier_id !== $supplier->id) {
                abort(403, 'Unauthorized access to this contract');
            }
        }

        $contract->load('supplier');
        return view('admin.contracts.show', compact('contract'));
    }

    public function download(DistributorContract $contract)
    {
        $tenantId = TenantContext::get();

        if ($contract->tenant_id !== $tenantId) {
            abort(403, 'Unauthorized access to this contract');
        }

        $user = auth()->user();
        if ($user->role !== 'admin') {
            $supplier = Supplier::where('user_id', $user->id)->first();
            if (!$supplier || $contract->supplier_id !== $supplier->id) {
                abort(403, 'Unauthorized access to this contract');
            }
        }

        try {
            $decrypted = $this->encryptionService->decryptFile(
                $contract->file_path,
                $contract->encryption_key_hash
            );

            return response()->streamDownload(function () use ($decrypted) {
                echo $decrypted;
            }, 'contract-' . $contract->contract_code . '.pdf', [
                'Content-Type' => 'application/pdf',
                'Content-Length' => strlen($decrypted),
            ]);
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', 'Gagal mendekripsi file kontrak: ' . $e->getMessage());
        }
    }

    public function destroy(DistributorContract $contract)
    {
        $tenantId = TenantContext::get();

        if ($contract->tenant_id !== $tenantId) {
            abort(403, 'Unauthorized access to this contract');
        }

        Storage::delete($contract->file_path);
        $contract->delete();

        return redirect()->route('admin.contracts.index')
            ->with('success', 'Kontrak distributor berhasil dihapus.');
    }
}
