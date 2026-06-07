@extends('layouts.app')

@section('title', 'Admin Dashboard - Shyness OS')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white transition-colors">Dashboard Admin</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 transition-colors">Overview sistem Anda</p>
        </div>
        <a href="{{ route('admin.pos') }}" class="px-4 py-2 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-lg hover:bg-gray-800 dark:hover:bg-gray-200 transition-colors">
            <i class="fa-solid fa-cash-register mr-2"></i>Buka Kasir
        </a>
    </div>

    <!-- POS Sales Report -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Today's Sales -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 rounded-2xl text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="text-green-100 text-sm">Penjualan Hari Ini</div>
                <div class="p-2 bg-white/20 rounded-lg"><i class="fa-solid fa-calendar-day"></i></div>
            </div>
            <div class="text-3xl font-bold mb-1">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</div>
            <div class="text-green-100 text-sm">{{ $todayOrderCount }} transaksi</div>
        </div>

        <!-- Weekly Sales -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-2xl text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="text-blue-100 text-sm">Penjualan Minggu Ini</div>
                <div class="p-2 bg-white/20 rounded-lg"><i class="fa-solid fa-chart-line"></i></div>
            </div>
            <div class="text-3xl font-bold mb-1">Rp {{ number_format($weeklyRevenue, 0, ',', '.') }}</div>
            <div class="text-blue-100 text-sm">{{ $weeklyOrderCount }} transaksi</div>
        </div>
    </div>

    <!-- Weekly Chart -->
    <div class="bg-white dark:bg-[#151515] p-6 rounded-2xl border border-gray-200 dark:border-gray-800 mb-8 transition-colors">
        <h3 class="font-bold text-gray-900 dark:text-white mb-4">Grafik Penjualan 7 Hari Terakhir</h3>
        <div class="flex items-end justify-between gap-2 h-40">
            @foreach($weeklyData as $index => $day)
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-t relative" style="height: 100%;">
                        <div class="absolute bottom-0 w-full bg-gradient-to-t from-blue-500 to-blue-400 rounded-t transition-all hover:from-blue-600 hover:to-blue-500" 
                             style="height: {{ $weeklyRevenue > 0 ? ($day['revenue'] / $weeklyRevenue * 100) : 0 }}%"></div>
                    </div>
                    <div class="text-[10px] text-gray-500 dark:text-gray-400 mt-2">{{ $day['day'] }}</div>
                    <div class="text-[10px] text-gray-400 dark:text-gray-500">{{ $day['date'] }}</div>
                </div>
            @endforeach
        </div>
        <div class="mt-4 text-center">
            <span class="text-xs text-gray-500 dark:text-gray-400">Total: Rp {{ number_format($weeklyRevenue, 0, ',', '.') }} | {{ $weeklyOrderCount }} transaksi</span>
        </div>
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

        <!-- Payment Options -->
        <div class="bg-white dark:bg-[#151515] p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <div class="text-gray-500 dark:text-gray-400 text-sm">Opsi Pembayaran</div>
                <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg text-indigo-600 dark:text-indigo-400"><i class="fa fa-credit-card"></i></div>
            </div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\PaymentOption::where('is_active', true)->count() }}</div>
            <div class="mt-2 text-xs">
                <a href="{{ route('admin.payment-options.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors">Kelola Opsi &rarr;</a>
            </div>
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
                            <img src="{{ $product->image_url }}" class="w-10 h-10 rounded-md object-cover bg-white dark:bg-gray-800">
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
