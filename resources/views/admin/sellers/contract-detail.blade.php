@extends('layouts.app')

@section('title', 'Detail Kontrak - Shyness OS')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-secondary dark:text-gray-400 mb-2">
            <a href="{{ route('admin.sellers.contracts') }}" class="hover:text-primary dark:hover:text-white">Kontrak Penjual</a>
            <i class="fa fa-chevron-right text-xs"></i>
            <span>Detail</span>
        </div>
        <h1 class="text-2xl font-bold text-primary dark:text-white">Detail Kontrak Penjual</h1>
    </div>

    <div class="bg-white dark:bg-primary border border-thin dark:border-gray-800 rounded-xl p-6 mb-6">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <p class="text-xs text-secondary dark:text-gray-400 uppercase tracking-widest mb-1">Nama User</p>
                <p class="font-bold text-primary dark:text-white">{{ $contract->user->name }}</p>
                <p class="text-sm text-secondary dark:text-gray-400">{{ $contract->user->email }}</p>
            </div>
            <div>
                <p class="text-xs text-secondary dark:text-gray-400 uppercase tracking-widest mb-1">Nama Bisnis</p>
                <p class="font-bold text-primary dark:text-white">{{ $contract->business_name }}</p>
            </div>
            <div>
                <p class="text-xs text-secondary dark:text-gray-400 uppercase tracking-widest mb-1">Telepon</p>
                <p class="text-primary dark:text-white">{{ $contract->phone }}</p>
            </div>
            <div>
                <p class="text-xs text-secondary dark:text-gray-400 uppercase tracking-widest mb-1">Status</p>
                @if($contract->status === 'pending')
                    <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 rounded">Pending</span>
                @elseif($contract->status === 'approved')
                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 rounded">Approved</span>
                @else
                    <span class="px-2 py-1 text-xs bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 rounded">Rejected</span>
                @endif
            </div>
        </div>
        <div class="mt-6">
            <p class="text-xs text-secondary dark:text-gray-400 uppercase tracking-widest mb-1">Deskripsi Bisnis</p>
            <p class="text-sm text-primary dark:text-white">{{ $contract->business_description }}</p>
        </div>
    </div>

    @if($contract->status === 'pending')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Approve Form --}}
        <div class="bg-green-50 dark:bg-green-900/10 border border-green-200 dark:border-green-800 rounded-xl p-6">
            <h3 class="font-bold text-green-800 dark:text-green-300 mb-4">Setujui Kontrak</h3>
            <form action="{{ route('admin.sellers.contracts.approve', $contract) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="default_markup_percentage" class="block text-sm font-bold text-green-700 dark:text-green-400 mb-2">
                        Markup Default (%) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="default_markup_percentage" 
                        id="default_markup_percentage"
                        required
                        min="0" max="100" step="0.01"
                        value="{{ old('default_markup_percentage', 20) }}"
                        class="w-full px-4 py-3 bg-white dark:bg-primary border border-green-200 dark:border-green-800 text-primary dark:text-white rounded-lg outline-none"
                        placeholder="Contoh: 20"
                    >
                    <p class="text-xs text-green-600 dark:text-green-400 mt-1">Persentase markup yang diterapkan pada semua produk penjual ini.</p>
                </div>
                <div class="mb-4">
                    <label for="approve_notes" class="block text-sm font-bold text-green-700 dark:text-green-400 mb-2">Catatan (opsional)</label>
                    <textarea name="admin_notes" id="approve_notes" rows="2" class="w-full px-4 py-3 bg-white dark:bg-primary border border-green-200 dark:border-green-800 text-primary dark:text-white rounded-lg outline-none resize-y" placeholder="Catatan untuk penjual..."></textarea>
                </div>
                <button type="submit" class="w-full py-3 bg-green-600 text-white text-xs tracking-widest uppercase font-medium rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fa fa-check mr-1"></i> Setujui
                </button>
            </form>
        </div>

        {{-- Reject Form --}}
        <div class="bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800 rounded-xl p-6">
            <h3 class="font-bold text-red-800 dark:text-red-300 mb-4">Tolak Kontrak</h3>
            <form action="{{ route('admin.sellers.contracts.reject', $contract) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="reject_notes" class="block text-sm font-bold text-red-700 dark:text-red-400 mb-2">
                        Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="admin_notes" id="reject_notes" rows="3" required class="w-full px-4 py-3 bg-white dark:bg-primary border border-red-200 dark:border-red-800 text-primary dark:text-white rounded-lg outline-none resize-y" placeholder="Jelaskan alasan penolakan (min. 5 karakter)"></textarea>
                </div>
                <button type="submit" class="w-full py-3 bg-red-600 text-white text-xs tracking-widest uppercase font-medium rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fa fa-times mr-1"></i> Tolak
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- Products from this seller --}}
    @if($contract->products->isNotEmpty())
    <div class="mt-8">
        <h2 class="text-lg font-bold text-primary dark:text-white mb-4">Produk Diajukan</h2>
        <div class="bg-white dark:bg-primary border border-thin dark:border-gray-800 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-[#151515]">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Produk</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Harga Dasar</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($contract->products as $product)
                    <tr>
                        <td class="px-4 py-3 font-medium text-primary dark:text-white">{{ $product->title }}</td>
                        <td class="px-4 py-3 text-secondary dark:text-gray-400">Rp {{ number_format($product->base_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            @if($product->status === 'pending')
                                <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 rounded">Pending</span>
                            @elseif($product->status === 'approved')
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 rounded">Approved</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 rounded">Rejected</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($product->status === 'pending')
                                <a href="{{ route('admin.sellers.products.show', $product) }}" class="px-3 py-1 text-xs bg-primary dark:bg-white text-white dark:text-primary rounded hover:bg-black dark:hover:bg-gray-200 transition-colors">Tinjau</a>
                            @else
                                <a href="{{ route('admin.sellers.products.show', $product) }}" class="text-xs text-secondary underline hover:no-underline">Detail</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
