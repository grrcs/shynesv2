@extends('layouts.app')

@section('title', 'Koleksi - Shyness')

@section('content')
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-end mb-12 border-b border-thin dark:border-gray-800 pb-6 transition-colors">
        <div class="max-w-xl">
            <h1 class="text-4xl font-serif font-medium text-primary dark:text-white mb-2 transition-colors">The Collection</h1>
            <p class="text-sm tracking-wide text-secondary dark:text-gray-400 font-light transition-colors">
                Explore our curated selection of essentials. Crafted with purpose.
            </p>
        </div>
        
        @auth
            @if(auth()->user()->isAdmin())
                <div class="mt-6 md:mt-0 flex gap-4">
                    <a href="{{ route('products.create') }}" class="px-6 py-3 text-xs tracking-widest uppercase font-medium text-white bg-primary dark:bg-white dark:text-primary hover:bg-black dark:hover:bg-gray-200 transition-colors">
                        + Tambah Produk
                    </a>
                </div>
            @endif
        @endauth
    </div>

    @if(isset($banners) && $banners->count() > 0 && (!auth()->check() || (auth()->check() && !auth()->user()->isAdmin())))
        <div class="mb-12 overflow-x-auto pb-4 -mx-4 px-4 sm:mx-0 sm:px-0 custom-scrollbar fade-in-up">
            <div class="flex space-x-6 w-max">
                @foreach($banners as $banner)
                <div class="w-[85vw] md:w-[60vw] lg:w-[800px] aspect-[16/9] md:aspect-[21/9] relative rounded-xl overflow-hidden group shadow-sm shrink-0 border border-thin dark:border-gray-800 bg-gray-100 dark:bg-gray-800" style="min-width: 300px;">
                    <img src="{{ asset('storage/' . $banner->image) }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-[2s] group-hover:scale-105" alt="{{ $banner->title ?? 'Banner Promo' }}">
                    <div class="absolute inset-0 bg-black/10 group-hover:bg-black/20 transition-colors duration-500"></div>
                    @if($banner->title)
                        <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/80 to-transparent">
                            <h3 class="text-white text-xl md:text-3xl font-serif tracking-wide">{{ $banner->title }}</h3>
                        </div>
                    @endif
                    @if($banner->link)
                        <a href="{{ $banner->link }}" target="_blank" class="absolute inset-0 z-10"></a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    @endif


    @auth
        @if(auth()->user()->isAdmin())
            <!-- Admin View: Minimalist Table -->
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-thin dark:border-gray-800 text-xs tracking-widest uppercase text-secondary transition-colors">
                            <th class="p-4 font-normal w-16">Item</th>
                            <th class="py-4 px-2 font-normal">Nama</th>
                            <th class="py-4 px-2 font-normal">Kategori</th>
                            <th class="py-4 px-2 font-normal text-right">Harga</th>
                            <th class="py-4 px-2 font-normal text-center w-24">Stok</th>
                            <th class="p-4 font-normal text-right w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-primary dark:text-gray-300 transition-colors">
                        @forelse ($products as $product)
                            <tr class="border-b border-thin dark:border-gray-800 hover:bg-gray-50/50 dark:hover:bg-gray-900/50 transition-colors group">
                                <td class="p-4">
                                    <div class="w-12 h-16 bg-gray-100 dark:bg-[#151515] overflow-hidden relative border border-thin dark:border-gray-800 transition-colors">
                                        <img src="{{ asset('storage/products/'.$product->image) }}" class="object-cover w-full h-full grayscale hover:grayscale-0 transition-all duration-500" alt="{{ $product->title }}">
                                    </div>
                                </td>
                                <td class="py-4 px-2">
                                    <a href="{{ route('products.show', $product->id) }}" class="text-sm font-medium hover:underline text-primary dark:text-white transition-colors">{{ $product->title }}</a>
                                </td>
                                <td class="py-4 px-2 text-xs text-secondary">{{ $product->category->name ?? '-' }}</td>
                                <td class="py-4 px-2 text-sm text-right font-medium text-primary dark:text-white transition-colors">
                                    @if($product->is_discount_active && $product->discount_price)
                                        <div class="text-[10px] text-secondary line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                        <div class="text-red-600 dark:text-red-400">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</div>
                                    @else
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    @endif
                                </td>
                                <td class="py-4 px-2 text-xs text-center">
                                    <div class="{{ $product->stock < 5 ? 'text-red-500 dark:text-red-400' : 'text-primary dark:text-gray-300' }}">{{ $product->stock }}</div>
                                    @if($product->is_discount_active && $product->discount_limit !== null)
                                        <div class="text-[10px] text-red-500 mt-1">Limit: {{ $product->discount_limit }}</div>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex justify-end gap-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('products.edit', $product->id) }}" class="text-xs tracking-widest uppercase text-secondary hover:text-black dark:hover:text-white transition-colors">Edit</a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus permanen?');" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs tracking-widest uppercase text-red-400 hover:text-red-600 dark:hover:text-red-300 transition-colors">Del</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-12 text-center text-sm text-secondary">Tidak ada produk dalam koleksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pt-8 transition-colors">
                {{ $products->links() }}
            </div>

        @else
            <!-- User View: Aesthetic Grid -->
            @include('products.partials.product-grid')
        @endif
    @else
        <!-- Guest View: Aesthetic Grid -->
        @include('products.partials.product-grid')
    @endauth

@endsection
