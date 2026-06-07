@extends('layouts.app')

@section('title', 'Produk Penjual - Shyness OS')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-primary dark:text-white">Produk Penjual</h1>
            <p class="text-sm text-secondary dark:text-gray-400">Kelola pengajuan produk dari penjual.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.sellers.products', ['status' => 'pending']) }}" class="px-3 py-1 text-xs border border-thin dark:border-gray-800 rounded {{ request('status') === 'pending' ? 'bg-primary text-white dark:bg-white dark:text-primary' : 'text-secondary dark:text-gray-400' }}">Pending</a>
            <a href="{{ route('admin.sellers.products', ['status' => 'approved']) }}" class="px-3 py-1 text-xs border border-thin dark:border-gray-800 rounded {{ request('status') === 'approved' ? 'bg-primary text-white dark:bg-white dark:text-primary' : 'text-secondary dark:text-gray-400' }}">Approved</a>
            <a href="{{ route('admin.sellers.products', ['status' => 'all']) }}" class="px-3 py-1 text-xs border border-thin dark:border-gray-800 rounded {{ !request('status') || request('status') === 'all' ? 'bg-primary text-white dark:bg-white dark:text-primary' : 'text-secondary dark:text-gray-400' }}">Semua</a>
        </div>
    </div>

    @if($sellerProducts->isEmpty())
        <div class="bg-white dark:bg-primary border border-thin dark:border-gray-800 rounded-xl p-8 text-center">
            <p class="text-secondary dark:text-gray-400">Belum ada pengajuan produk dari penjual.</p>
        </div>
    @else
        <div class="bg-white dark:bg-primary border border-thin dark:border-gray-800 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-[#151515]">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Produk</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Penjual</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Harga Dasar</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($sellerProducts as $sp)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if($sp->image)
                                    <img src="{{ $sp->image_url }}" class="w-10 h-10 rounded object-cover">
                                @endif
                                <span class="font-medium text-primary dark:text-white">{{ $sp->title }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-secondary dark:text-gray-400">{{ $sp->sellerContract->user->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-secondary dark:text-gray-400">Rp {{ number_format($sp->base_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-secondary dark:text-gray-400">{{ $sp->category->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if($sp->status === 'pending')
                                <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 rounded">Pending</span>
                            @elseif($sp->status === 'approved' || $sp->status === 'active')
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 rounded">Approved</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 rounded">Rejected</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.sellers.products.show', $sp) }}" class="text-xs text-primary dark:text-white underline hover:no-underline">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $sellerProducts->links() }}</div>
    @endif
</div>
@endsection
