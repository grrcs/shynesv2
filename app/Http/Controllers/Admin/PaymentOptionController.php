<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentOption;
use Illuminate\Http\Request;

class PaymentOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentOptions = PaymentOption::orderBy('name')->paginate(20);
        return view('admin.payment-options.index', compact('paymentOptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.payment-options.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'description' => 'nullable|string',
            'tax_percentage' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        PaymentOption::create($validated);

        return redirect()->route('admin.payment-options.index')
            ->with('success', 'Opsi pembayaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $paymentOption = PaymentOption::findOrFail($id);
        return view('admin.payment-options.show', compact('paymentOption'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $paymentOption = PaymentOption::findOrFail($id);
        return view('admin.payment-options.edit', compact('paymentOption'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'description' => 'nullable|string',
            'tax_percentage' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        $paymentOption = PaymentOption::findOrFail($id);
        $paymentOption->update($validated);

        return redirect()->route('admin.payment-options.index')
            ->with('success', 'Opsi pembayaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $paymentOption = PaymentOption::findOrFail($id);
        $paymentOption->delete();

        return redirect()->route('admin.payment-options.index')
            ->with('success', 'Opsi pembayaran berhasil dihapus.');
    }
}
