@extends('layouts.app')

@section('title', 'My Orders - Shyness')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-12 border-b border-thin dark:border-gray-800 pb-6 flex justify-between items-end transition-colors">
        <div>
            <h1 class="text-3xl font-serif font-medium text-primary dark:text-white mb-2 transition-colors">My Orders</h1>
            <p class="text-xs tracking-widest uppercase text-secondary">Purchase History</p>
        </div>
    </div>

    <div class="space-y-6">
        @forelse ($orders as $order)
            <div class="bg-white dark:bg-primary border border-thin dark:border-gray-800 p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 group hover:border-black dark:hover:border-white transition-colors">
                <div>
                    <div class="flex flex-wrap items-center gap-4 mb-4">
                        <span class="text-xs font-mono tracking-widest text-secondary">{{ $order->invoice_number }}</span>
                        <span class="text-xs text-gray-300 dark:text-gray-700">|</span>
                        <span class="text-xs text-secondary">{{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="font-medium text-xl text-primary dark:text-white mb-2 transition-colors">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                    
                    <div class="text-sm font-light text-secondary">
                        {{ $order->items->count() }} item(s)
                        @if($order->items->first())
                            <span class="italic text-gray-400 dark:text-gray-500">&mdash; {{ Str::limit($order->items->first()->product_name, 30) }}...</span>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 w-full md:w-auto mt-4 md:mt-0 pt-4 md:pt-0 border-t border-thin dark:border-gray-800 md:border-0 transition-colors">
                    <span class="inline-flex items-center text-xs tracking-widest uppercase
                        {{ $order->status == 'pending' ? 'text-yellow-600 dark:text-yellow-500' : '' }}
                        {{ in_array($order->status, ['paid', 'completed']) ? 'text-primary dark:text-white' : '' }}
                        {{ $order->status == 'shipped' ? 'text-blue-600 dark:text-blue-400' : '' }}
                        {{ $order->status == 'cancelled' ? 'text-red-500 dark:text-red-400' : '' }}
                    ">
                        {{ $order->status }}
                    </span>
                    <a href="{{ route('orders.show', $order->id) }}" class="px-6 py-3 border border-thin dark:border-gray-700 text-xs tracking-widest uppercase font-medium text-primary dark:text-white hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black transition-colors text-center w-full sm:w-auto">
                        View Receipt
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-24 bg-gray-50 dark:bg-[#151515] border border-thin dark:border-gray-800 transition-colors">
                <h3 class="text-xl font-serif text-primary dark:text-white mb-4 transition-colors">No orders yet</h3>
                <p class="text-sm font-light text-secondary mb-8">You haven't made any purchases.</p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center px-10 py-4 text-xs tracking-widest uppercase font-medium text-white bg-primary dark:bg-white dark:text-primary hover:bg-black dark:hover:bg-gray-200 transition-colors">
                    Start Shopping
                </a>
            </div>
        @endforelse
    </div>
    
    @if($orders->hasPages())
        <div class="mt-12 pt-6 border-t border-thin dark:border-gray-800 transition-colors">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
