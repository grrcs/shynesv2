@extends('layouts.app')

@section('title', 'Pesanan Saya - Shyness OS')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Pesanan Saya</h2>
    </div>

    <div class="space-y-4">
        @forelse ($orders as $order)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:shadow-md transition-shadow">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-xs font-mono font-bold text-gray-500">{{ $order->invoice_number }}</span>
                        <span class="text-xs text-gray-400">&bull;</span>
                        <span class="text-xs text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="font-bold text-lg text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                    <div class="text-sm text-gray-600 mt-1">
                        {{ $order->items->count() }} Produk
                        @if($order->items->first())
                            <span class="text-gray-400">({{ $order->items->first()->product_name }}...)</span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                        {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $order->status == 'paid' || $order->status == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                    ">
                        {{ $order->status }}
                    </span>
                    <a href="{{ route('orders.show', $order->id) }}" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-black transition-colors">
                        Lihat Nota
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-xl border border-gray-200">
                <i class="fa fa-shopping-bag text-gray-300 text-4xl mb-3"></i>
                <p class="text-gray-500">Belum ada pesanan.</p>
                <a href="{{ route('products.index') }}" class="mt-4 inline-block text-black font-medium hover:underline">Mulai Belanja &rarr;</a>
            </div>
        @endforelse
    </div>
    
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</div>
@endsection
