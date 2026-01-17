@extends('layouts.app')

@section('title', 'Katalog Produk - Shyness OS')

@section('content')
    <!-- STATISTIK CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card 1: Total Produk -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Produk</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $products->total() }}</p>
            </div>
            <div class="p-3 bg-gray-50 rounded-full text-gray-600">
                <i class="fa fa-box-open text-xl"></i>
            </div>
        </div>
        <!-- Card 2: Produk Terbaru -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Terbaru</p>
                <p class="text-sm font-bold text-gray-900 mt-1 truncate w-32">
                    {{ $products->first() ? $products->first()->title : '-' }}
                </p>
            </div>
            <div class="p-3 bg-gray-50 rounded-full text-gray-600">
                <i class="fa fa-clock text-xl"></i>
            </div>
        </div>
        <!-- Card 3: Status System -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Status System</p>
                <p class="text-sm font-bold text-gray-900 mt-1 flex items-center gap-1">
                    <span class="w-2 h-2 bg-gray-900 rounded-full animate-pulse"></span> Online
                </p>
            </div>
            <div class="p-3 bg-gray-50 rounded-full text-gray-600">
                <i class="fa fa-server text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Daftar Produk</h2>
            <p class="text-sm text-gray-500">Kelola katalog barang dagangan Anda.</p>
        </div>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-black transition-all shadow-md">
                <i class="fa fa-plus mr-2"></i> Tambah Produk
            </a>
        @endif
    </div>

    <!-- Tabel Produk -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase w-24">Gambar</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Nama Produk</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase w-32">Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase w-32">Harga</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase w-24">Stok</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase w-24">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="h-12 w-12 rounded-md overflow-hidden border border-gray-200 group relative">
                                    <a href="{{ route('products.show', $product->id) }}" class="block w-full h-full">
                                        <img class="h-full w-full object-cover" src="{{ asset('storage/products/'.$product->image) }}" alt="Img">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-opacity"></div>
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('products.show', $product->id) }}" class="text-sm font-bold text-gray-900 hover:text-blue-600 transition-colors">
                                    {{ $product->title }}
                                </a>
                                <div class="text-xs text-gray-400 mt-0.5">Link: {{ $product->link_shopee ? 'Ada' : '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-mono font-medium text-gray-900">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $product->stock < 5 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($product->status == 'active')
                                    <span class="text-xs font-bold text-green-600">Active</span>
                                @elseif($product->status == 'sold_out')
                                    <span class="text-xs font-bold text-red-600">Sold Out</span>
                                @else
                                    <span class="text-xs font-bold text-gray-400">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center space-x-2">
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('products.edit', $product->id) }}" class="text-gray-400 hover:text-gray-900"><i class="fa fa-pencil"></i></a>
                                        <form onsubmit="return confirm('Hapus produk ini?');" action="{{ route('products.destroy', $product->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600"><i class="fa fa-trash"></i></button>
                                        </form>
                                    @else
                                        <form action="{{ route('orders.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="text-blue-600 hover:text-blue-800 font-bold text-sm">
                                                <i class="fa fa-shopping-cart mr-1"></i> Beli
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-12 text-center text-gray-500">Belum ada produk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">{{ $products->links() }}</div>
    </div>
@endsection
