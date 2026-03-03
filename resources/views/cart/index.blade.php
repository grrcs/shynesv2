@extends('layouts.app')

@section('title', 'Keranjang - Shyness')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-12 border-b border-thin dark:border-gray-800 pb-6 flex justify-between items-end transition-colors">
        <div>
            <h1 class="text-3xl font-serif font-medium text-primary dark:text-white mb-2 transition-colors">Shopping Bag</h1>
            <p class="text-xs tracking-widest uppercase text-secondary">Your curated selections</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 border border-primary dark:border-white p-4 text-sm bg-gray-50 dark:bg-primary text-primary dark:text-white uppercase tracking-widest text-center transition-colors">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-8 border border-red-500 p-4 text-sm text-red-600 dark:text-red-400 uppercase tracking-widest text-center">
            {{ session('error') }}
        </div>
    @endif

    @if($cartItems->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="border-t border-thin dark:border-gray-800 transition-colors">
                    @php $total = 0; @endphp
                    @foreach($cartItems as $item)
                        @php 
                            $priceToUse = ($item->product->is_discount_active && $item->product->discount_price) ? $item->product->discount_price : $item->product->price;
                            $subtotal = $priceToUse * $item->quantity;
                            $total += $subtotal;
                        @endphp
                        <div class="py-8 border-b border-thin dark:border-gray-800 flex gap-6 relative group transition-colors">
                            <!-- Product Image -->
                            <a href="{{ route('products.show', $item->product->id) }}" class="w-24 h-32 bg-gray-50 dark:bg-[#151515] shrink-0 border border-thin dark:border-gray-800 overflow-hidden transition-colors">
                                <img src="{{ asset('storage/products/'.$item->product->image) }}" alt="{{ $item->product->title }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
                            </a>

                            <!-- Product Details -->
                            <div class="flex-grow flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start mb-1">
                                        <a href="{{ route('products.show', $item->product->id) }}" class="text-sm font-medium text-primary dark:text-white hover:underline underline-offset-4 transition-colors">
                                            {{ $item->product->title }}
                                        </a>
                                        <span class="text-sm font-medium text-primary dark:text-white transition-colors">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                    </div>
                                    <p class="text-xs text-secondary">{{ $item->product->category->name ?? 'Essentials' }}</p>
                                    @if($item->product->is_discount_active && $item->product->discount_price)
                                        <p class="text-xs font-light mt-2 flex gap-2">
                                            <span class="text-secondary line-through">Rp {{ number_format($item->product->price, 0, ',', '.') }}</span>
                                            <span class="text-red-500 font-medium">Rp {{ number_format($item->product->discount_price, 0, ',', '.') }}</span>
                                        </p>
                                    @else
                                        <p class="text-xs font-light text-secondary mt-2">Price: Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="flex justify-between items-end mt-4">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center border border-thin dark:border-gray-700 transition-colors">
                                        @csrf @method('PUT')
                                        <label class="sr-only">Quantity</label>
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}"
                                               class="w-16 py-2 px-2 text-center text-sm focus:outline-none focus:ring-0 bg-transparent text-primary dark:text-white transition-colors" onchange="this.form.submit()">
                                    </form>

                                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs tracking-widest uppercase text-secondary hover:text-red-500 transition-colors" onclick="return confirm('Remove item?')">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-gray-50 dark:bg-[#151515] p-8 border border-thin dark:border-gray-800 sticky top-28 transition-colors">
                    <h3 class="text-xs tracking-widest uppercase font-medium text-primary dark:text-gray-300 mb-6 pb-4 border-b border-thin dark:border-gray-800 transition-colors">Order Summary</h3>
                    
                    <div class="space-y-4 mb-6 text-sm text-secondary font-light">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping</span>
                            <span>Calculated next</span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between text-base font-medium text-primary dark:text-white pt-4 border-t border-thin dark:border-gray-800 mb-8 transition-colors">
                        <span>Total</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <a href="{{ route('orders.checkout') }}" class="block w-full text-center px-8 py-4 text-xs tracking-widest uppercase font-medium text-white bg-primary dark:bg-white dark:text-primary hover:bg-black dark:hover:bg-gray-200 transition-colors">
                        Proceed to Checkout
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-32 border border-thin dark:border-gray-800 bg-gray-50 dark:bg-[#151515] transition-colors">
            <h3 class="text-xl font-serif text-primary dark:text-white mb-4 transition-colors">Your bag is empty</h3>
            <p class="text-sm font-light text-secondary mb-8">Discover our latest collection.</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center px-10 py-4 text-xs tracking-widest uppercase font-medium text-primary dark:text-white border border-primary dark:border-white hover:bg-primary hover:text-white dark:hover:bg-white dark:hover:text-black transition-colors">
                Explore Collection
            </a>
        </div>
    @endif
</div>
@endsection
