@extends('layouts.app')

@section('title', 'Pilih Opsi Pembayaran - Shyness')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-12 border-b border-thin dark:border-gray-800 pb-6 text-center transition-colors">
        <h1 class="text-3xl font-serif font-medium text-primary dark:text-white mb-2 transition-colors">Pilih Opsi Pembayaran</h1>
        <p class="text-xs tracking-widest uppercase text-secondary">Pilih metode pembayaran yang Anda inginkan</p>
    </div>
    
    @php
        $subtotalAmount = 0;
        if (isset($cartItems)) {
            foreach ($cartItems as $item) {
                // Determine actual price (discount or regular)
                $actualPrice = ($item->product->is_discount_active && $item->product->discount_price) ? $item->product->discount_price : $item->product->price;
                $subtotalAmount += $actualPrice * $item->quantity;
            }
        }
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Payment Options -->
        <div class="md:col-span-2">
            <h3 class="text-xs tracking-widest uppercase font-medium text-primary dark:text-gray-300 mb-6 pb-4 border-b border-thin dark:border-gray-800 transition-colors">Metode Pembayaran</h3>
            
            @if($paymentOptions->isEmpty())
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-500 p-4 rounded-lg text-center">
                    <p class="text-yellow-700 dark:text-yellow-300">Tidak ada opsi pembayaran yang tersedia saat ini.</p>
                </div>
            @else
                <form id="paymentForm">
                    @csrf
                    <div class="space-y-4">
                        @foreach($paymentOptions as $option)
                            <label class="payment-option-label block cursor-pointer group">
                                <input type="radio" name="payment_option_id" value="{{ $option->id }}" 
                                       class="sr-only" 
                                       data-tax="{{ $option->tax_percentage }}"
                                       {{ $loop->first ? 'checked' : '' }}>
                                <div class="p-6 bg-white dark:bg-primary border border-gray-200 dark:border-gray-800 rounded-xl transition-all duration-300 group-has-[:checked]:border-black group-has-[:checked]:ring-1 group-has-[:checked]:ring-black group-has-[:checked]:bg-gray-50 dark:group-has-[:checked]:border-white dark:group-has-[:checked]:ring-white dark:group-has-[:checked]:bg-[#151515]">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start gap-4">
                                            <div class="w-6 h-6 rounded-full border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center mt-1 transition-colors group-has-[:checked]:border-black dark:group-has-[:checked]:border-white">
                                                <div class="w-3 h-3 rounded-full bg-black dark:bg-white scale-0 transition-transform duration-300 group-has-[:checked]:scale-100"></div>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-primary dark:text-white text-lg">{{ $option->name }}</h4>
                                                @if($option->description)
                                                    <p class="text-sm text-secondary dark:text-gray-400 mt-1">{{ $option->description }}</p>
                                                @endif
                                                @if($option->tax_percentage > 0)
                                                    <p class="text-xs text-red-500 dark:text-red-400 mt-2">
                                                        <i class="fa fa-info-circle mr-1"></i>
                                                        Biaya tambahan: {{ number_format($option->tax_percentage, 2) }}%
                                                    </p>
                                                @else
                                                    <p class="text-xs text-green-500 dark:text-green-400 mt-2">
                                                        <i class="fa fa-check-circle mr-1"></i>
                                                        Tanpa biaya tambahan
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-bold text-primary dark:text-white">
                                                Rp <span class="subtotal-amount">0</span>
                                            </div>
                                            @if($option->tax_percentage > 0)
                                                <div class="text-xs text-secondary dark:text-gray-400 mt-1">
                                                    + Rp <span class="tax-amount">0</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </form>
            @endif
        </div>

        <!-- Order Summary -->
        <div class="md:col-span-1">
            <h3 class="text-xs tracking-widest uppercase font-medium text-primary dark:text-gray-300 mb-6 pb-4 border-b border-thin dark:border-gray-800 transition-colors">Ringkasan Pesanan</h3>
            
            <div class="bg-white dark:bg-primary border border-thin dark:border-gray-800 rounded-xl p-6 sticky top-8">
                <div class="space-y-4 mb-6 text-sm">
                    <div class="flex justify-between">
                        <span class="text-secondary dark:text-gray-400">Subtotal</span>
                        <span class="font-medium text-primary dark:text-white">Rp <span id="subtotal">0</span></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-secondary dark:text-gray-400">Pajak & Biaya</span>
                        <span class="font-medium text-primary dark:text-white">Rp <span id="tax">0</span></span>
                    </div>
                    <div class="pt-4 border-t border-thin dark:border-gray-800">
                        <div class="flex justify-between text-base font-bold">
                            <span>Total</span>
                            <span>Rp <span id="total">0</span></span>
                        </div>
                    </div>
                </div>

                <!-- Confirm Button -->
                <form id="orderForm" action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_option_id" id="selectedPaymentOption">
                    <button type="submit" id="confirmPayment" class="w-full py-4 text-xs tracking-widest uppercase font-medium text-white bg-primary dark:bg-white dark:text-primary hover:bg-black dark:hover:bg-gray-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <i class="fa fa-lock mr-2"></i> Konfirmasi Pembayaran
                    </button>
                </form>
                
                <p class="text-xs text-secondary dark:text-gray-400 text-center mt-4">
                    <i class="fa fa-shield-alt mr-1"></i>
                    Pembayaran aman & terenkripsi
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Data subtotal asli dari backend
    const subtotalAmount = {{ $subtotalAmount ?? 0 }};
    
    
    function formatRupiah(amount) {
        return amount.toLocaleString('id-ID');
    }
    
    function updateOrderSummary() {
        const selectedOption = document.querySelector('input[name="payment_option_id"]:checked');
        if (!selectedOption) return;
        
        const taxPercentage = parseFloat(selectedOption.dataset.tax) || 0;
        const taxAmount = subtotalAmount * (taxPercentage / 100);
        const totalAmount = subtotalAmount + taxAmount;
        
        // Update summary
        document.getElementById('subtotal').textContent = formatRupiah(subtotalAmount);
        document.getElementById('tax').textContent = formatRupiah(Math.round(taxAmount));
        document.getElementById('total').textContent = formatRupiah(Math.round(totalAmount));
        
        // Update per-option amounts
        document.querySelectorAll('.payment-option-label').forEach(label => {
            const radio = label.querySelector('input[type="radio"]');
            const optionTax = parseFloat(radio.dataset.tax) || 0;
            const optionTaxAmount = subtotalAmount * (optionTax / 100);
            const optionTotal = subtotalAmount + optionTaxAmount;
            
            label.querySelector('.subtotal-amount').textContent = formatRupiah(subtotalAmount);
            
            const taxAmountElement = label.querySelector('.tax-amount');
            if (taxAmountElement) {
                taxAmountElement.textContent = formatRupiah(Math.round(optionTaxAmount));
            }
        });
        
        // Enable confirm button
        document.getElementById('confirmPayment').disabled = false;
    }
    
    // Initialize
    updateOrderSummary();
    
    // Listen for payment option changes
    document.querySelectorAll('input[name="payment_option_id"]').forEach(radio => {
        radio.addEventListener('change', updateOrderSummary);
    });
    
    // Handle payment option change to update hidden input
    document.querySelectorAll('input[name="payment_option_id"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('selectedPaymentOption').value = this.value;
            updateOrderSummary();
        });
    });
    
    // Set initial hidden input value
    const initialSelectedOption = document.querySelector('input[name="payment_option_id"]:checked');
    if (initialSelectedOption) {
        document.getElementById('selectedPaymentOption').value = initialSelectedOption.value;
    }
    
    // Handle form submission
    document.getElementById('orderForm').addEventListener('submit', function(e) {
        const selectedOption = document.querySelector('input[name="payment_option_id"]:checked');
        if (!selectedOption) {
            e.preventDefault();
            alert('Silakan pilih metode pembayaran terlebih dahulu.');
            return;
        }
    });
</script>
@endpush
