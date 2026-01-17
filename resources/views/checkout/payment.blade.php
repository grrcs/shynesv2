@extends('layouts.app')

@section('title', 'Pembayaran - Shyness OS')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="p-8 text-center bg-gray-900">
            <h2 class="text-3xl font-bold text-white mb-2">Checkout & Pembayaran</h2>
            <p class="text-gray-400">Silakan selesaikan pembayaran Anda</p>
        </div>
        
        <div class="p-8">
            <!-- Order Summary -->
            <div class="mb-8 bg-gray-50 rounded-xl p-6 border border-gray-200">
                <h3 class="font-bold text-gray-900 mb-4">Ringkasan Pesanan</h3>
                <div class="space-y-3">
                    @php $total = 0; @endphp
                    @foreach($cartItems as $item)
                        @php 
                            $subtotal = $item->product->price * $item->quantity;
                            $total += $subtotal;
                        @endphp
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $item->product->title }} (x{{ $item->quantity }})</span>
                            <span class="font-medium text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    <div class="border-t border-gray-200 pt-3 mt-3 flex justify-between text-lg font-bold">
                        <span>Total Bayar</span>
                        <span class="text-blue-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Warning Stock Check -->
            @foreach($cartItems as $item)
                @if($item->product->stock < $item->quantity)
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <strong class="font-bold">Perhatian!</strong>
                        <span class="block sm:inline">Stok untuk {{ $item->product->title }} tidak mencukupi (Sisa: {{ $item->product->stock }}). Silakan kurangi jumlah di keranjang.</span>
                        <a href="{{ route('cart.index') }}" class="underline font-bold ml-2">Kembali ke Keranjang</a>
                    </div>
                @endif
            @endforeach

            <!-- QRIS Payment -->
            <div class="text-center mb-8">
                <p class="text-sm font-medium text-gray-500 mb-4 uppercase tracking-wide">Scan QRIS untuk Membayar</p>
                <div class="inline-block p-4 bg-white border-2 border-gray-900 rounded-2xl shadow-sm relative group">
                    <!-- Random QRIS using API -->
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=PAYMENT-{{ time() }}-{{ \Illuminate\Support\Str::random(5) }}&color=000000&bgcolor=ffffff" 
                         alt="QRIS Code" 
                         class="w-48 h-48 mx-auto">
                    <div class="mt-2 text-xs text-gray-400 font-mono">DUMMY QRIS SYSTEM</div>
                </div>
                <p class="mt-4 text-sm text-gray-600">
                    Buka aplikasi e-wallet Anda (GoPay, OVO, Dana, dll) dan scan kode di atas.<br>
                    Ini adalah simulasi, pembayaran akan otomatis dianggap berhasil.
                </p>
            </div>

            <!-- Confirm Button -->
            <form action="{{ route('orders.store') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-4 bg-green-600 text-white font-bold text-lg rounded-xl hover:bg-green-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center">
                    <i class="fa fa-check-circle mr-2"></i> Konfirmasi Pembayaran Selesai
                </button>
            </form>
            
            <div class="mt-4 text-center">
                <a href="{{ route('cart.index') }}" class="text-sm text-gray-500 hover:text-gray-900">Batal & Kembali ke Keranjang</a>
            </div>
        </div>
    </div>
</div>
@endsection
