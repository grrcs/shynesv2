<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierRegistrationController extends Controller
{
    public function create()
    {
        $user = auth()->user();

        $existing = Supplier::withoutGlobalScope('tenant')
            ->where('user_id', $user->id)
            ->first();

        return view('supplier.register', compact('existing'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $existing = Supplier::withoutGlobalScope('tenant')
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            return redirect()->route('supplier.register')
                ->with('error', 'Anda sudah mendaftar sebagai supplier.');
        }

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        Supplier::withoutGlobalScope('tenant')->create([
            'user_id' => $user->id,
            'company_name' => $validated['company_name'],
            'contact_person' => $validated['contact_person'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'status' => 'pending',
        ]);

        return redirect()->route('supplier.register')
            ->with('success', 'Pendaftaran supplier berhasil dikirim! Silakan tunggu persetujuan admin.');
    }
}
