@extends('layouts.app')

@section('title', 'Detail Produk Penjual - Shyness OS')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-secondary dark:text-gray-400 mb-2">
            <a href="{{ route('admin.sellers.products') }}" class="hover:text-primary dark:hover:text-white">Produk Penjual</a>
            <i class="fa fa-chevron-right text-xs"></i>
            <span>Detail</span>
        </div>
        <h1 class="text-2xl font-bold text-primary dark:text-white">{{ $sellerProduct->title }}</h1>
    </div>

    <div class="bg-white dark:bg-primary border border-thin dark:border-gray-800 rounded-xl p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if($sellerProduct->image)
            <div>
                <img src="{{ $sellerProduct->image_url }}" class="w-full rounded-lg object-cover" alt="{{ $sellerProduct->title }}">
            </div>
            @endif
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-secondary dark:text-gray-400 uppercase tracking-widest mb-1">Penjual</p>
                    <p class="font-bold text-primary dark:text-white">{{ $sellerProduct->sellerContract->user->name }}</p>
                    <p class="text-xs text-secondary dark:text-gray-400">{{ $sellerProduct->sellerContract->business_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-secondary dark:text-gray-400 uppercase tracking-widest mb-1">Kategori</p>
                    <p class="text-primary dark:text-white">{{ $sellerProduct->category->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-secondary dark:text-gray-400 uppercase tracking-widest mb-1">Harga Dasar (dari penjual)</p>
                    <p class="text-lg font-bold text-primary dark:text-white">Rp {{ number_format($sellerProduct->base_price, 0, ',', '.') }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-secondary dark:text-gray-400 uppercase tracking-widest mb-1">Stok</p>
                        <p class="text-primary dark:text-white">{{ $sellerProduct->stock }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-secondary dark:text-gray-400 uppercase tracking-widest mb-1">Berat</p>
                        <p class="text-primary dark:text-white">{{ $sellerProduct->weight ? $sellerProduct->weight . 'g' : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-6">
            <p class="text-xs text-secondary dark:text-gray-400 uppercase tracking-widest mb-1">Deskripsi</p>
            <p class="text-sm text-primary dark:text-white">{{ $sellerProduct->description }}</p>
        </div>
    </div>

    @if($sellerProduct->status === 'pending')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Approve Form --}}
        <div class="bg-green-50 dark:bg-green-900/10 border border-green-200 dark:border-green-800 rounded-xl p-6">
            <h3 class="font-bold text-green-800 dark:text-green-300 mb-4">Setujui Produk</h3>
            <form action="{{ route('admin.sellers.products.approve', $sellerProduct) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="markup_percentage" class="block text-sm font-bold text-green-700 dark:text-green-400 mb-2">
                        Markup (%) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="markup_percentage" 
                        id="markup_percentage"
                        required
                        min="0" max="100" step="0.01"
                        value="{{ old('markup_percentage', $sellerProduct->sellerContract->default_markup_percentage) }}"
                        class="w-full px-4 py-3 bg-white dark:bg-primary border border-green-200 dark:border-green-800 text-primary dark:text-white rounded-lg outline-none"
                        oninput="updatePreview()"
                    >
                    <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                        Harga jual: <strong id="preview-price">Rp {{ number_format($sellerProduct->base_price * (1 + $sellerProduct->sellerContract->default_markup_percentage / 100), 0, ',', '.') }}</strong>
                    </p>
                </div>
                <div class="mb-4">
                    <label for="approve_notes" class="block text-sm font-bold text-green-700 dark:text-green-400 mb-2">Catatan (opsional)</label>
                    <textarea name="admin_notes" id="approve_notes" rows="2" class="w-full px-4 py-3 bg-white dark:bg-primary border border-green-200 dark:border-green-800 text-primary dark:text-white rounded-lg outline-none resize-y" placeholder="Catatan..."></textarea>
                </div>
                <button type="submit" class="w-full py-3 bg-green-600 text-white text-xs tracking-widest uppercase font-medium rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fa fa-check mr-1"></i> Setujui & Tambah ke Toko
                </button>
            </form>
        </div>

        {{-- Reject Form --}}
        <div class="bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800 rounded-xl p-6">
            <h3 class="font-bold text-red-800 dark:text-red-300 mb-4">Tolak Produk</h3>
            <form action="{{ route('admin.sellers.products.reject', $sellerProduct) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="reject_notes" class="block text-sm font-bold text-red-700 dark:text-red-400 mb-2">
                        Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="admin_notes" id="reject_notes" rows="3" required class="w-full px-4 py-3 bg-white dark:bg-primary border border-red-200 dark:border-red-800 text-primary dark:text-white rounded-lg outline-none resize-y" placeholder="Jelaskan alasan penolakan..."></textarea>
                </div>
                <button type="submit" class="w-full py-3 bg-red-600 text-white text-xs tracking-widest uppercase font-medium rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fa fa-times mr-1"></i> Tolak
                </button>
            </form>
        </div>
    </div>

    <script>
        function updatePreview() {
            const markup = parseFloat(document.getElementById('markup_percentage').value) || 0;
            const basePrice = {{ $sellerProduct->base_price }};
            const finalPrice = basePrice * (1 + markup / 100);
            document.getElementById('preview-price').textContent = 'Rp ' + Math.round(finalPrice).toLocaleString('id-ID');
        }
    </script>
    @endif
</div>
@endsection
