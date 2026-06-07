@extends('layouts.app')

@section('title', 'Wishlist Saya - Shyness OS')

@section('content')
<div class="max-w-7xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
        <i class="fa fa-heart text-pink-500 mr-3"></i> Wishlist Saya
    </h2>

    @if($wishlists->isEmpty())
        <div class="bg-white rounded-xl p-12 text-center border border-gray-200">
            <div class="text-gray-300 text-6xl mb-4"><i class="fa fa-heart-broken"></i></div>
            <h3 class="text-lg font-medium text-gray-900">Wishlist Kosong</h3>
            <p class="text-gray-500 mt-2 mb-6">Simpan produk favorit Anda di sini.</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-2 bg-pink-500 text-white font-medium rounded-lg hover:bg-pink-600 transition-colors">
                Jelajahi Produk
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($wishlists as $item)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow relative group">
                    <!-- Remove Button -->
                    <form action="{{ route('wishlist.destroy', $item->id) }}" method="POST" class="absolute top-2 right-2 z-10">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-8 h-8 flex items-center justify-center bg-white rounded-full shadow-md text-gray-400 hover:text-red-500 transition-colors" title="Hapus dari Wishlist">
                            <i class="fa fa-times"></i>
                        </button>
                    </form>

                     <a href="{{ route('products.show', $item->product->id) }}" class="block p-4">
                        <img src="{{ $item->product->image_url }}" class="w-full h-48 object-cover rounded-md mb-4 bg-gray-100">
                        <h4 class="font-bold text-gray-900 mb-1 truncate">{{ $item->product->title }}</h4>
                        <div class="text-blue-600 font-mono text-sm font-bold">Rp {{ number_format($item->product->price, 0, ',', '.') }}</div>
                     </a>
                     
                     <div class="px-4 pb-4">
                        <form action="{{ route('cart.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="w-full py-2 bg-gray-900 text-white font-bold rounded-lg hover:bg-black transition-colors text-sm">
                                <i class="fa fa-cart-plus mr-1"></i> + Keranjang
                            </button>
                        </form>
                     </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
