@extends('layouts.app')

@section('title', 'Admin Dashboard - Shyness OS')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white transition-colors">Dashboard Admin</h2>
        <div class="text-sm text-gray-500 dark:text-gray-400 transition-colors">Overview sistem Anda</div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Revenue -->
        <div class="bg-white dark:bg-[#151515] p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <div class="text-gray-500 dark:text-gray-400 text-sm">Total Pendapatan</div>
                <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg text-green-600 dark:text-green-400"><i class="fa fa-dollar-sign"></i></div>
            </div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>

        <!-- Orders -->
        <div class="bg-white dark:bg-[#151515] p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <div class="text-gray-500 dark:text-gray-400 text-sm">Total Pesanan</div>
                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400"><i class="fa fa-shopping-bag"></i></div>
            </div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalOrders }}</div>
        </div>

        <!-- Products -->
        <div class="bg-white dark:bg-[#151515] p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <div class="text-gray-500 dark:text-gray-400 text-sm">Total Produk</div>
                <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg text-purple-600 dark:text-purple-400"><i class="fa fa-box"></i></div>
            </div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalProducts }}</div>
        </div>

        <!-- Users -->
        <div class="bg-white dark:bg-[#151515] p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <div class="text-gray-500 dark:text-gray-400 text-sm">Total Pengguna</div>
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg text-yellow-600 dark:text-yellow-400"><i class="fa fa-users"></i></div>
            </div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalUsers }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Orders -->
        <div class="bg-white dark:bg-[#151515] rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 p-6 transition-colors">
            <h3 class="font-bold text-gray-900 dark:text-white mb-6">Pesanan Terbaru</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-800 transition-colors">
                            <th class="pb-3">ID</th>
                            <th class="pb-3">User</th>
                            <th class="pb-3">Total</th>
                            <th class="pb-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-900 dark:text-gray-300">
                        @forelse($recentOrders as $order)
                            <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="py-3 font-mono">#{{ $order->id }}</td>
                                <td class="py-3">{{ $order->user->name ?? 'Guest' }}</td>
                                <td class="py-3 font-medium text-primary dark:text-white">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td class="py-3">
                                    <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-xs font-bold">{{ $order->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-4 text-center text-gray-500 dark:text-gray-400">Belum ada pesanan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-center">
                <a href="{{ route('orders.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium transition-colors">Lihat Semua Pesanan &rarr;</a>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white dark:bg-[#151515] rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 p-6 transition-colors">
            <h3 class="font-bold text-red-600 dark:text-red-400 mb-6 flex items-center transition-colors">
                <i class="fa fa-exclamation-triangle mr-2"></i> Stok Menipis (< 5)
            </h3>
            <div class="space-y-4">
                @forelse($lowStockProducts as $product)
                    <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-100 dark:border-red-900/30 transition-colors">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('storage/products/'.$product->image) }}" class="w-10 h-10 rounded-md object-cover bg-white dark:bg-gray-800">
                            <div>
                                <div class="font-bold text-gray-900 dark:text-white text-sm transition-colors">{{ $product->title }}</div>
                                <div class="text-xs text-red-600 dark:text-red-400 transition-colors">Sisa Stok: {{ $product->stock }}</div>
                            </div>
                        </div>
                        <a href="{{ route('products.edit', $product->id) }}" class="text-xs bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 px-2 py-1 rounded hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-300 transition-colors">Restock</a>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400 transition-colors">
                        <i class="fa fa-check-circle text-green-500 dark:text-green-400 text-3xl mb-2"></i>
                        <p>Stok aman!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
