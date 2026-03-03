<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-8 gap-y-12">
    @forelse ($products as $product)
        <div class="group flex flex-col items-start relative product-card" data-product-id="{{ $product->id }}">
            <!-- Product Image -->
            <a href="{{ route('products.show', $product->id) }}" class="w-full relative aspect-[3/4] bg-gray-100 dark:bg-[#151515] overflow-hidden mb-4 border border-thin dark:border-gray-800 transition-colors">
                <img src="{{ asset('storage/products/'.$product->image) }}" alt="{{ $product->title }}" 
                     class="w-full h-full object-cover grayscale transition-all duration-700 ease-in-out group-hover:grayscale-0 group-hover:scale-105 select-image">
                
                @if($product->stock <= 0 || $product->status == 'sold_out')
                    <div class="absolute inset-0 bg-white/60 dark:bg-black/60 backdrop-blur-[2px] flex items-center justify-center transition-colors">
                        <span class="border border-primary dark:border-white px-4 py-2 text-xs tracking-widest uppercase text-primary dark:text-white font-medium bg-white dark:bg-black transition-colors">Sold Out</span>
                    </div>
                @endif
            </a>

            <!-- Product Meta -->
            <div class="w-full flex justify-between items-start mb-4">
                <div>
                    <a href="{{ route('products.show', $product->id) }}">
                        <h3 class="text-sm font-medium text-primary dark:text-white hover:underline underline-offset-4 transition-colors">{{ $product->title }}</h3>
                    </a>
                    <p class="text-xs text-secondary mt-1">{{ $product->category->name ?? 'Essentials' }}</p>
                </div>
                <div class="text-right flex flex-col items-end">
                    @if($product->is_discount_active && $product->discount_price)
                        <span class="text-xs text-secondary line-through mb-0.5">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        <span class="text-sm font-bold text-red-600 dark:text-red-400">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</span>
                    @else
                        <span class="text-sm font-medium text-primary dark:text-white transition-colors">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    @endif
                </div>
            </div>

            <!-- Actions (Always visible or visible on hover) -->
            @if($product->stock > 0 && $product->status != 'sold_out')
                <div class="w-full flex gap-2">
                    <!-- Beli Sekarang (Direct) -->
                    <form action="{{ route('cart.store') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full h-10 bg-primary text-white dark:bg-white dark:text-primary text-xs tracking-widest uppercase font-medium hover:bg-black dark:hover:bg-gray-200 transition-colors border border-primary dark:border-white">
                            Beli
                        </button>
                    </form>
                    
                    <!-- Tambah ke Keranjang (AJAX) -->
                    <button type="button" onclick="addToCart({{ $product->id }}, 1, this)" class="w-10 h-10 flex-shrink-0 bg-white dark:bg-primary border border-thin dark:border-gray-700 flex items-center justify-center text-primary dark:text-white hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors" title="Tambah ke Keranjang">
                        <i class="fa-solid fa-cart-plus text-sm"></i>
                    </button>
                    
                    <!-- Wishlist (AJAX) -->
                    <button type="button" onclick="addToWishlist({{ $product->id }}, this)" class="w-10 h-10 flex-shrink-0 bg-white dark:bg-primary border border-thin dark:border-gray-700 flex items-center justify-center text-primary dark:text-white hover:bg-red-50 hover:text-red-500 hover:border-red-200 dark:hover:bg-red-900/20 dark:hover:text-red-400 dark:hover:border-red-900 transition-colors" title="Favoritkan">
                        @php
                            $inWishlist = false;
                            if(auth()->check()) {
                                $inWishlist = \App\Models\Wishlist::where('user_id', auth()->id())->where('product_id', $product->id)->exists();
                            }
                        @endphp
                        <i class="{{ $inWishlist ? 'fa-solid fa-heart text-red-500' : 'fa-regular fa-heart' }} text-sm wishlist-icon"></i>
                    </button>
                </div>
            @endif
        </div>
    @empty
        <div class="col-span-1 sm:col-span-2 lg:col-span-3 xl:col-span-4 text-center py-20">
            <p class="text-sm tracking-widest uppercase text-secondary">The collection is currently empty.</p>
        </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-16 w-full flex justify-center transition-colors">
    {{ $products->links() }}
</div>
