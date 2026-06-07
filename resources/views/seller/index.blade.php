@extends('layouts.app')

@section('title', 'Dashboard Penjual - Shyness')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-primary dark:text-white">Dashboard Penjual</h1>
        <p class="text-sm text-secondary dark:text-gray-400">Kelola produk dan kontrak penjualan Anda.</p>
    </div>

    {{-- No contract yet --}}
    @if(!$contract)
        <div class="bg-white dark:bg-primary border border-thin dark:border-gray-800 rounded-xl p-8 text-center">
            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa fa-store text-2xl text-secondary"></i>
            </div>
            <h2 class="text-lg font-bold text-primary dark:text-white mb-2">Mulai Berjualan</h2>
            <p class="text-sm text-secondary dark:text-gray-400 mb-6">Ajukan permintaan untuk menjadi penjual di Shyness Store.</p>
            <a href="{{ route('seller.request') }}" class="inline-block px-6 py-3 bg-primary dark:bg-white text-white dark:text-primary text-xs tracking-widest uppercase font-medium hover:bg-black dark:hover:bg-gray-200 transition-colors">
                Ajukan Sekarang
            </a>
        </div>

    {{-- Contract pending --}}
    @elseif($contract->isPending())
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-300 dark:border-yellow-700 rounded-xl p-6">
            <div class="flex items-center gap-3 mb-2">
                <i class="fa fa-clock text-yellow-600"></i>
                <h2 class="text-lg font-bold text-yellow-800 dark:text-yellow-300">Menunggu Persetujuan</h2>
            </div>
            <p class="text-sm text-yellow-700 dark:text-yellow-400">Pengajuan Anda sebagai penjual sedang ditinjau oleh admin. Kami akan memberitahu Anda setelah disetujui.</p>
            <div class="mt-4 p-4 bg-white dark:bg-primary rounded-lg border border-thin dark:border-gray-800">
                <p class="text-xs text-secondary dark:text-gray-400">Nama Bisnis</p>
                <p class="font-bold text-primary dark:text-white">{{ $contract->business_name }}</p>
            </div>
        </div>

    {{-- Contract rejected --}}
    @elseif($contract->status === 'rejected')
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-300 dark:border-red-700 rounded-xl p-6">
            <div class="flex items-center gap-3 mb-2">
                <i class="fa fa-times-circle text-red-600"></i>
                <h2 class="text-lg font-bold text-red-800 dark:text-red-300">Pengajuan Ditolak</h2>
            </div>
            <p class="text-sm text-red-700 dark:text-red-400">Maaf, pengajuan Anda ditolak oleh admin.</p>
            @if($contract->admin_notes)
                <div class="mt-4 p-4 bg-white dark:bg-primary rounded-lg border border-thin dark:border-gray-800">
                    <p class="text-xs text-secondary dark:text-gray-400">Catatan Admin</p>
                    <p class="text-sm text-primary dark:text-white">{{ $contract->admin_notes }}</p>
                </div>
            @endif
        </div>

    {{-- Contract approved - show products --}}
    @elseif($contract->isApproved())
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-300 dark:border-green-700 rounded-xl p-4 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fa fa-check-circle text-green-600"></i>
                    <div>
                        <p class="font-bold text-green-800 dark:text-green-300">{{ $contract->business_name }}</p>
                        <p class="text-xs text-green-700 dark:text-green-400">Markup default: {{ $contract->default_markup_percentage }}%</p>
                    </div>
                </div>
                <a href="{{ route('seller.products.create') }}" class="px-4 py-2 bg-primary dark:bg-white text-white dark:text-primary text-xs tracking-widest uppercase font-medium hover:bg-black dark:hover:bg-gray-200 transition-colors">
                    <i class="fa fa-plus mr-1"></i> Ajukan Produk
                </a>
            </div>
        </div>

        {{-- Products list --}}
        @if($products->isEmpty())
            <div class="bg-white dark:bg-primary border border-thin dark:border-gray-800 rounded-xl p-8 text-center">
                <p class="text-secondary dark:text-gray-400">Belum ada produk. Ajukan produk pertama Anda!</p>
            </div>
        @else
            <div class="bg-white dark:bg-primary border border-thin dark:border-gray-800 rounded-xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-[#151515]">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Produk</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Harga Dasar</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Harga Jual</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($products as $product)
                        <tr>
                            <td class="px-4 py-3 font-medium text-primary dark:text-white">{{ $product->title }}</td>
                            <td class="px-4 py-3 text-secondary dark:text-gray-400">Rp {{ number_format($product->base_price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-secondary dark:text-gray-400">Rp {{ number_format($product->final_price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                @if($product->status === 'pending')
                                    <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 rounded">Menunggu</span>
                                @elseif($product->status === 'approved' || $product->status === 'active')
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 rounded">Disetujui</span>
                                @elseif($product->status === 'rejected')
                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 rounded">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $products->links() }}</div>
        @endif
    @endif
</div>
@endsection
