@extends('layouts.app')

@section('title', 'Checkout - Shyness')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-12 border-b border-thin dark:border-gray-800 pb-6 text-center transition-colors">
        <h1 class="text-3xl font-serif font-medium text-primary dark:text-white mb-2 transition-colors">Checkout</h1>
        <p class="text-xs tracking-widest uppercase text-secondary">Secure Payment</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
        <!-- Order Summary -->
        <div class="order-2 md:order-1">
            <h3 class="text-xs tracking-widest uppercase font-medium text-primary dark:text-gray-300 mb-6 pb-4 border-b border-thin dark:border-gray-800 transition-colors">Order Summary</h3>
            
            <div class="space-y-6 mb-8">
                @php $total = 0; @endphp
                @foreach($cartItems as $item)
                    @php 
                        $subtotal = $item->product->price * $item->quantity;
                        $total += $subtotal;
                    @endphp
                    <div class="flex gap-4">
                        <div class="w-16 h-20 bg-gray-50 dark:bg-[#151515] border border-thin dark:border-gray-800 shrink-0 overflow-hidden transition-colors">
                            <img src="{{ asset('storage/products/'.$item->product->image) }}" alt="{{ $item->product->title }}" class="w-full h-full object-cover grayscale">
                        </div>
                        <div class="flex flex-col justify-between flex-grow py-1">
                            <div class="flex justify-between">
                                <span class="text-sm text-primary dark:text-white font-medium transition-colors">{{ $item->product->title }}</span>
                                <span class="text-sm font-medium text-primary dark:text-white transition-colors">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="text-xs text-secondary font-light">Quantity: {{ $item->quantity }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="border-t border-thin dark:border-gray-800 transition-colors pt-6 mb-8 space-y-4 text-sm font-light text-secondary">
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Tax & Processing</span>
                    <span>Included</span>
                </div>
            </div>
            
            <div class="border-t border-b border-thin dark:border-gray-800 transition-colors py-4 mb-8 flex justify-between text-base font-medium text-primary dark:text-white">
                <span>Total Amount</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
            
            <a href="{{ route('cart.index') }}" class="text-xs tracking-widest uppercase text-secondary hover:text-black dark:hover:text-white transition-colors underline underline-offset-4">
                Return to Bag
            </a>
        </div>

        <!-- Payment Section -->
        <div class="order-1 md:order-2">
            <!-- Warning Stock Check -->
            @foreach($cartItems as $item)
                @if($item->product->stock < $item->quantity)
                    <div class="mb-8 border border-red-500 p-4 text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 text-center transition-colors">
                        <p class="font-medium mb-1">Stock Issue</p>
                        <p class="font-light">Only {{ $item->product->stock }} left for {{ $item->product->title }}.</p>
                        <a href="{{ route('cart.index') }}" class="underline mt-2 block hover:text-red-500 dark:hover:text-red-300 transition-colors">Update Bag</a>
                    </div>
                @endif
            @endforeach

            <div class="bg-gray-50 dark:bg-[#151515] border border-thin dark:border-gray-800 transition-colors p-8 text-center sticky top-28">
                <h3 class="text-xs tracking-widest uppercase font-medium text-primary dark:text-gray-300 mb-6">Scan to Pay (QRIS)</h3>
                
                <div class="inline-block p-4 bg-white border border-thin dark:border-gray-700 shadow-sm mb-6 relative">
                    <!-- Random QRIS -->
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=SHYNESS-{{ time() }}-{{ \Illuminate\Support\Str::random(5) }}&color=111111&bgcolor=ffffff" 
                         alt="QRIS Code" 
                         class="w-40 h-40 mx-auto">
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-100 bg-white/80 dark:bg-black/80 transition-opacity">
                        <span class="text-xs font-medium tracking-widest uppercase text-black dark:text-white">Simulation</span>
                    </div>
                </div>
                
                <p class="text-xs font-light text-secondary mb-8 leading-relaxed">
                    Scan with any supported Digital Wallet app.<br/>
                    Payment is automatically verified in simulation mode.
                </p>

                <!-- Confirm Button -->
                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-4 text-xs tracking-widest uppercase font-medium text-white bg-primary dark:bg-white dark:text-primary hover:bg-black dark:hover:bg-gray-200 transition-colors">
                        Confirm Payment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
