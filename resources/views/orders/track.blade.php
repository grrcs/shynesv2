@extends('layouts.app')

@section('title', 'Lacak Pesanan #' . $order->id . ' - Shyness OS')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-secondary dark:text-gray-400 mb-2">
            <a href="{{ auth()->user()->isAdmin() ? route('orders.index') : route('orders.my') }}" class="hover:text-primary dark:hover:text-white">Pesanan</a>
            <i class="fa fa-chevron-right text-xs"></i>
            <span>Lacak Pesanan</span>
        </div>
        <h2 class="text-2xl font-bold text-primary dark:text-white mb-2">Lacak Pesanan #{{ $order->id }}</h2>
        <p class="text-sm text-secondary dark:text-gray-400">Status pesanan Anda saat ini: <strong class="text-primary dark:text-white">{{ ucfirst($order->status) }}</strong></p>
    </div>

    <!-- Order Info Card -->
    <div class="bg-white dark:bg-primary rounded-xl shadow-sm border border-thin dark:border-gray-800 p-6 mb-8 transition-colors">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h4 class="text-sm font-bold text-secondary dark:text-gray-400 mb-1">Nomor Invoice</h4>
                <p class="font-mono text-primary dark:text-white">{{ $order->invoice_number }}</p>
            </div>
            <div>
                <h4 class="text-sm font-bold text-secondary dark:text-gray-400 mb-1">Tanggal Pemesanan</h4>
                <p class="text-primary dark:text-white">{{ $order->created_at->format('d M Y H:i') }}</p>
            </div>
            <div>
                <h4 class="text-sm font-bold text-secondary dark:text-gray-400 mb-1">Total Pembayaran</h4>
                <p class="font-bold text-primary dark:text-white">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Shipping Details -->
    @if($order->shippingDetail)
    <div class="bg-white dark:bg-primary rounded-xl shadow-sm border border-thin dark:border-gray-800 p-6 mb-8 transition-colors">
        <h3 class="text-lg font-bold text-primary dark:text-white mb-4">Informasi Pengiriman</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-sm font-bold text-secondary dark:text-gray-400 mb-2">Kurir</h4>
                <p class="text-primary dark:text-white font-medium">
                    {{ $order->shippingDetail->courier_name ?? '-' }}
                    @if($order->shippingDetail->service_type)
                        <span class="text-sm text-secondary dark:text-gray-400">({{ $order->shippingDetail->service_type }})</span>
                    @endif
                </p>
            </div>
            <div>
                <h4 class="text-sm font-bold text-secondary dark:text-gray-400 mb-2">Nomor Resi</h4>
                <p class="text-primary dark:text-white">
                    @if($order->shippingDetail->tracking_number)
                        <span class="font-mono">{{ $order->shippingDetail->tracking_number }}</span>
                        @if($order->shippingDetail->tracking_url)
                            <a href="{{ $order->shippingDetail->tracking_url }}" target="_blank" class="ml-2 text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                <i class="fa fa-external-link-alt"></i> Lacak
                            </a>
                        @endif
                    @else
                        <span class="text-secondary dark:text-gray-400">-</span>
                    @endif
                </p>
            </div>
            <div>
                <h4 class="text-sm font-bold text-secondary dark:text-gray-400 mb-2">Tanggal Pengiriman</h4>
                <p class="text-primary dark:text-white">
                    {{ $order->shippingDetail->shipped_at ? $order->shippingDetail->shipped_at->format('d M Y H:i') : '-' }}
                </p>
            </div>
            <div>
                <h4 class="text-sm font-bold text-secondary dark:text-gray-400 mb-2">Estimasi Sampai</h4>
                <p class="text-primary dark:text-white">
                    {{ $order->shippingDetail->estimated_delivery_at ? $order->shippingDetail->estimated_delivery_at->format('d M Y') : '-' }}
                </p>
            </div>
        </div>
        
        <!-- Receiver Address -->
        <div class="mt-6 pt-6 border-t border-thin dark:border-gray-800">
            <h4 class="text-sm font-bold text-secondary dark:text-gray-400 mb-2">Alamat Penerima</h4>
            <div class="text-primary dark:text-white">
                <p class="font-medium">{{ $order->shippingDetail->receiver_name }}</p>
                <p class="text-sm">{{ $order->shippingDetail->receiver_phone }}</p>
                <p class="text-sm mt-1">
                    {{ $order->shippingDetail->receiver_address }}
                    @if($order->shippingDetail->receiver_city)
                        <br>{{ $order->shippingDetail->receiver_city }}
                    @endif
                    @if($order->shippingDetail->receiver_province)
                        , {{ $order->shippingDetail->receiver_province }}
                    @endif
                    @if($order->shippingDetail->receiver_postal_code)
                        <br>{{ $order->shippingDetail->receiver_postal_code }}
                    @endif
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Status Timeline -->
    <div class="bg-white dark:bg-primary rounded-xl shadow-sm border border-thin dark:border-gray-800 p-6 mb-8 transition-colors">
        <h3 class="text-lg font-bold text-primary dark:text-white mb-6">Timeline Status Pesanan</h3>
        
        @if($order->statusHistory->isEmpty())
            <div class="text-center py-8 text-secondary dark:text-gray-400">
                <i class="fa fa-clock text-3xl mb-2"></i>
                <p>Belum ada update status</p>
            </div>
        @else
            <div class="relative">
                <!-- Timeline Line -->
                <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200 dark:bg-gray-700"></div>
                
                @foreach($order->statusHistory as $history)
                    <div class="relative flex items-start mb-6 last:mb-0">
                        <!-- Timeline Dot -->
                        <div class="absolute left-2 w-4 h-4 rounded-full bg-primary dark:bg-white border-4 border-white dark:border-primary shadow-sm z-10"></div>
                        
                        <!-- Content -->
                        <div class="ml-12">
                            <div class="flex items-center justify-between mb-1">
                                <h4 class="font-bold text-primary dark:text-white">
                                    {{ ucfirst(str_replace('_', ' ', $history->status)) }}
                                </h4>
                                <span class="text-xs text-secondary dark:text-gray-400">
                                    {{ $history->changed_at->format('d M Y H:i') }}
                                </span>
                            </div>
                            @if($history->notes)
                                <p class="text-sm text-secondary dark:text-gray-400">{{ $history->notes }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Order Items -->
    <div class="bg-white dark:bg-primary rounded-xl shadow-sm border border-thin dark:border-gray-800 p-6 transition-colors">
        <h3 class="text-lg font-bold text-primary dark:text-white mb-4">Detail Pesanan</h3>
        <div class="space-y-4">
            @foreach($order->items as $item)
                <div class="flex items-center gap-4 pb-4 border-b border-thin dark:border-gray-800 last:border-0 last:pb-0">
                    <div class="w-16 h-20 bg-gray-50 dark:bg-[#151515] border border-thin dark:border-gray-800 overflow-hidden rounded">
                        <img src="{{ $item->product->image_url }}" 
                             alt="{{ $item->product_name }}" 
                             class="w-full h-full object-cover grayscale">
                    </div>
                    <div class="flex-grow">
                        <h4 class="font-medium text-primary dark:text-white">{{ $item->product_name }}</h4>
                        <p class="text-sm text-secondary dark:text-gray-400">Qty: {{ $item->quantity }}</p>
                        <p class="text-sm font-medium text-primary dark:text-white mt-1">
                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Order Summary -->
        <div class="mt-6 pt-6 border-t border-thin dark:border-gray-800">
            <div class="flex justify-between text-sm mb-2">
                <span class="text-secondary dark:text-gray-400">Subtotal</span>
                <span class="text-primary dark:text-white">
                    Rp {{ number_format($order->items->sum(function($item) { return $item->price * $item->quantity; }), 0, ',', '.') }}
                </span>
            </div>
            @if($order->tax_amount > 0)
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-secondary dark:text-gray-400">Pajak & Biaya</span>
                    <span class="text-primary dark:text-white">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</span>
                </div>
            @endif
            @if($order->shippingDetail && $order->shippingDetail->shipping_cost > 0)
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-secondary dark:text-gray-400">Ongkir</span>
                    <span class="text-primary dark:text-white">Rp {{ number_format($order->shippingDetail->shipping_cost, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="flex justify-between text-lg font-bold mt-4">
                <span>Total</span>
                <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-8 text-center">
        <a href="{{ auth()->user()->isAdmin() ? route('orders.index') : route('orders.my') }}" 
           class="inline-flex items-center px-6 py-3 bg-gray-100 dark:bg-[#151515] hover:bg-gray-200 dark:hover:bg-gray-800 text-secondary dark:text-gray-300 font-bold rounded-lg transition-colors">
            <i class="fa fa-arrow-left mr-2"></i>
            Kembali ke Daftar Pesanan
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-refresh tracking data every 30 seconds if order is shipped
    @if($order->status === 'shipped' && $order->shippingDetail && $order->shippingDetail->tracking_number)
        setInterval(function() {
            // In real implementation, fetch from courier API
            console.log('Checking courier status...');
        }, 30000);
    @endif
</script>
@endpush
