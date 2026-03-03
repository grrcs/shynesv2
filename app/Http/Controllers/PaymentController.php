<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentOption;

class PaymentController extends Controller
{
    /**
     * Get all active payment options for buyer
     */
    public function getPaymentOptions()
    {
        $paymentOptions = PaymentOption::where('is_active', true)
            ->orderBy('name')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $paymentOptions
        ]);
    }
    
    /**
     * Show payment options in checkout page
     */
    public function showPaymentOptions()
    {
        $cartItems = collect();
        $addresses = collect();
        
        if (auth()->check()) {
            $cartItems = auth()->user()->cartItems()->with('product')->get();
            $addresses = auth()->user()->addresses()->orderByDesc('is_primary')->latest()->get();
        }

        $paymentOptions = PaymentOption::where('is_active', true)
            ->orderBy('name')
            ->get();
            
        return view('checkout.payment-options', compact('paymentOptions', 'cartItems', 'addresses'));
    }
}
