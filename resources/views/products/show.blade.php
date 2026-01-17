@extends('layouts.app')

@section('title', $product->title . ' - Shyness OS')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('products.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <i class="fa fa-shopping-bag mr-2"></i>
                    Produk
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fa fa-chevron-right text-gray-400 text-xs mx-2"></i>
                    <span class="text-sm font-medium text-gray-500">{{ $product->title }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <!-- Product Image -->
            <div class="p-6 md:p-8 bg-gray-50 flex items-center justify-center">
                <div class="relative w-full aspect-square rounded-xl overflow-hidden shadow-md bg-white">
                    <img src="{{ asset('storage/products/'.$product->image) }}" 
                         alt="{{ $product->title }}" 
                         class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-500">
                </div>
            </div>

            <!-- Product Info -->
            <div class="p-6 md:p-8 flex flex-col justify-between">
                <div>
                    <!-- Category Badge -->
                    <div class="mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">
                            {{ $product->category->name ?? 'Uncategorized' }}
                        </span>
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->title }}</h1>
                    
                    <!-- Price -->
                    <div class="flex items-baseline mb-6">
                        <span class="text-3xl font-mono font-bold text-gray-900">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                        @if($product->stock < 5 && $product->stock > 0)
                            <span class="ml-4 text-sm font-medium text-red-600">
                                Sisa {{ $product->stock }} stok!
                            </span>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="prose prose-sm text-gray-600 mb-8">
                        <h3 class="text-sm font-bold text-gray-900 mb-2 uppercase tracking-wide">Deskripsi</h3>
                        <div class="whitespace-pre-line">{!! $product->description !!}</div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="border-t border-gray-100 pt-6">
                    <div class="flex flex-col gap-4">
                        @if($product->status == 'active' && $product->stock > 0)
                            @if(auth()->user()->isAdmin())
                                <div class="flex gap-4">
                                    <a href="{{ route('products.edit', $product->id) }}" class="flex-1 text-center px-6 py-3 bg-gray-100 text-gray-900 font-bold rounded-xl hover:bg-gray-200 transition-colors">
                                        <i class="fa fa-pencil mr-2"></i> Edit Produk
                                    </a>
                                </div>
                            @else
                                <form action="{{ route('cart.store') }}" method="POST" class="w-full">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <div class="flex gap-4">
                                        <div class="w-24">
                                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" 
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl text-center focus:outline-none focus:ring-2 focus:ring-black">
                                        </div>
                                        <button type="submit" class="flex-1 px-6 py-3 bg-gray-900 text-white font-bold rounded-xl hover:bg-black transition-colors shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                            <i class="fa fa-cart-plus mr-2"></i> Tambah ke Keranjang
                                        </button>
                                    </div>
                                </form>
                                <form action="{{ route('wishlist.store') }}" method="POST" class="ml-3">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="h-full px-4 bg-white text-gray-400 border border-gray-200 rounded-xl hover:text-pink-500 hover:border-pink-500 transition-colors shadow-sm hover:shadow-md" title="Simpan ke Wishlist">
                                        <i class="fa fa-heart text-xl"></i>
                                    </button>
                                </form>
                            @endif
                        @else
                           <button disabled class="w-full px-6 py-3 bg-gray-200 text-gray-400 font-bold rounded-xl cursor-not-allowed">
                                @if($product->stock <= 0)
                                    Stok Habis
                                @else
                                    Produk Tidak Aktif
                                @endif
                           </button>
                        @endif
                        
                        <!-- Shopee Link if available -->
                        @if($product->link_shopee)
                            <a href="{{ $product->link_shopee }}" target="_blank" class="w-full text-center px-6 py-3 border border-orange-500 text-orange-600 font-bold rounded-xl hover:bg-orange-50 transition-colors">
                                <i class="fa fa-shopping-bag mr-2"></i> Beli di Shopee
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden p-6 md:p-8">
        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fa fa-star text-yellow-400 mr-2"></i> Ulasan Produk ({{ $product->reviews->count() }})
        </h3>

        <!-- Review Form -->
        <div class="mb-8 bg-gray-50 rounded-xl p-6 border border-gray-200">
            <h4 class="font-bold text-gray-900 mb-2">Tulis Ulasan Anda</h4>
            <form action="{{ route('reviews.store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                    <div class="flex gap-4">
                        @foreach([5, 4, 3, 2, 1] as $rating)
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="rating" value="{{ $rating }}" class="mr-1" {{ $rating == 5 ? 'checked' : '' }}>
                                <span class="text-yellow-500">
                                    @for($i=0; $i<$rating; $i++) <i class="fa fa-star"></i> @endfor
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Komentar</label>
                    <textarea name="comment" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-black focus:border-black" placeholder="Bagaimana pendapat Anda tentang produk ini?"></textarea>
                </div>
                <button type="submit" class="px-6 py-2 bg-gray-900 text-white font-bold rounded-lg hover:bg-black transition-colors">
                    Kirim Ulasan
                </button>
            </form>
        </div>

        <!-- Reviews List -->
        <div class="space-y-6">
            @forelse($product->reviews as $review)
                <div class="border-b border-gray-100 last:border-0 pb-6 last:pb-0">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <span class="font-bold text-gray-900">{{ $review->user->name }}</span>
                            <span class="text-xs text-gray-500 ml-2">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="text-yellow-400 text-sm">
                            @for($i=0; $i<$review->rating; $i++) <i class="fa fa-star"></i> @endfor
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm">{{ $review->comment }}</p>
                </div>
            @empty
                <div class="text-center text-gray-500 py-8">
                    Belum ada ulasan untuk produk ini. Jadilah yang pertama!
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
