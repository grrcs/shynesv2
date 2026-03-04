@extends('layouts.app')

@section('title', $product->title . ' - Shyness')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Clean Breadcrumb -->
    <nav class="flex mb-12 text-xs tracking-widest uppercase text-secondary font-light" aria-label="Breadcrumb">
        <a href="{{ route('products.index') }}" class="hover:text-black dark:hover:text-white transition-colors">Koleksi</a>
        <span class="mx-4">/</span>
        <span class="text-primary dark:text-white transition-colors">{{ $product->title }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-24 mb-20 product-card" data-product-id="{{ $product->id }}">
        <!-- Product Image - Swiper Slider -->
        <div class="lg:col-span-7">
            <!-- Swiper CSS -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
            <!-- Swiper JS -->
            <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

            <style>
                .swiper-button-next, .swiper-button-prev { color: black; }
                .dark .swiper-button-next, .dark .swiper-button-prev { color: white; }
                .swiper-pagination-bullet-active { background: black !important; }
                .dark .swiper-pagination-bullet-active { background: white !important; }
                .swiper-pagination-bullet { background: gray; }
                
                /* Image zoom styles */
                .zoom-container { position: relative; overflow: hidden; cursor: zoom-in; }
                .zoom-container.zoom-active { cursor: zoom-out; }
                .zoom-image { transition: transform 0.3s ease; }
                .zoom-container.zoom-active .zoom-image { transform: scale(2); }
                
                /* Thumbnail styles */
                .thumbnail-swiper { height: 100px; margin-top: 16px; }
                .thumbnail-slide { opacity: 0.6; transition: opacity 0.3s; cursor: pointer; }
                .thumbnail-slide-thumb-active { opacity: 1; }
                .thumbnail-slide img { width: 100%; height: 100%; object-fit: cover; border: 2px solid transparent; transition: border-color 0.3s; }
                .thumbnail-slide-thumb-active img { border-color: #000; }
                .dark .thumbnail-slide-thumb-active img { border-color: #fff; }
            </style>

            <!-- Main Swiper -->
            <div class="swiper product-swiper w-full bg-gray-50 dark:bg-[#151515] border border-thin dark:border-gray-800 aspect-[3/4] relative overflow-hidden transition-colors">
                <div class="swiper-wrapper">
                    <!-- Main Image Slide -->
                    <div class="swiper-slide h-full w-full flex items-center justify-center">
                        <div class="zoom-container w-full h-full flex items-center justify-center">
                            <img src="{{ asset('storage/products/'.$product->image) }}" onerror="this.onerror=null;this.src='{{ asset('images/campaign/shyness_vol_1.png') }}';" 
                                 alt="{{ $product->title }}" 
                                 class="zoom-image w-full h-full object-cover select-image">
                        </div>
                    </div>
                    
                    <!-- Additional Media Slides -->
                    @if($product->media && $product->media->count() > 0)
                        @foreach($product->media as $media)
                            <div class="swiper-slide h-full w-full flex items-center justify-center bg-gray-100 dark:bg-[#151515]">
                                <div class="zoom-container w-full h-full flex items-center justify-center">
                                    @if($media->file_type == 'image')
                                        <img src="{{ asset('storage/products/'.$media->file_path) }}" onerror="this.onerror=null;this.src='{{ asset('images/campaign/shyness_vol_1.png') }}';" 
                                             alt="{{ $product->title }}" 
                                             class="zoom-image w-full h-full object-cover select-image">
                                    @else
                                        <video src="{{ asset('storage/products_video/'.$media->file_path) }}" class="w-full h-full object-cover" controls playsinline></video>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Navigation arrows -->
                <div class="swiper-button-next drop-shadow-md"></div>
                <div class="swiper-button-prev drop-shadow-md"></div>
                
                <!-- Pagination -->
                <div class="swiper-pagination"></div>
            </div>

            <!-- Thumbnail Swiper -->
            <div thumbsSlider="" class="swiper thumbnail-swiper">
                <div class="swiper-wrapper">
                    <!-- Main Image Thumbnail -->
                    <div class="swiper-slide thumbnail-slide h-full w-full flex items-center justify-center bg-gray-100 dark:bg-[#151515]">
                        <img src="{{ asset('storage/products/'.$product->image) }}" onerror="this.onerror=null;this.src='{{ asset('images/campaign/shyness_vol_1.png') }}';" 
                             alt="{{ $product->title }}" 
                             class="w-full h-full object-cover">
                    </div>
                    
                    <!-- Additional Media Thumbnails -->
                    @if($product->media && $product->media->count() > 0)
                        @foreach($product->media as $media)
                            <div class="swiper-slide thumbnail-slide h-full w-full flex items-center justify-center bg-gray-100 dark:bg-[#151515]">
                                @if($media->file_type == 'image')
                                    <img src="{{ asset('storage/products/'.$media->file_path) }}" onerror="this.onerror=null;this.src='{{ asset('images/campaign/shyness_vol_1.png') }}';" 
                                         alt="{{ $product->title }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <video src="{{ asset('storage/products_video/'.$media->file_path) }}" class="w-full h-full object-cover"></video>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Initialize thumbnail swiper
                    const thumbnailSwiper = new Swiper('.thumbnail-swiper', {
                        spaceBetween: 10,
                        slidesPerView: 4,
                        freeMode: true,
                        watchSlidesProgress: true,
                        breakpoints: {
                            640: { slidesPerView: 5 },
                            1024: { slidesPerView: 6 },
                        },
                    });

                    // Initialize main swiper
                    const mainSwiper = new Swiper('.product-swiper', {
                        loop: true,
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: true,
                        },
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                        thumbs: {
                            swiper: thumbnailSwiper,
                        },
                    });

                    // Pause video when swiping away
                    mainSwiper.on('slideChange', function() {
                        document.querySelectorAll('.product-swiper video').forEach(function(video) {
                            video.pause();
                        });
                    });

                    // Image zoom functionality
                    document.querySelectorAll('.zoom-container').forEach(container => {
                        const img = container.querySelector('.zoom-image');
                        if (!img) return;

                        container.addEventListener('click', function(e) {
                            this.classList.toggle('zoom-active');
                            
                            if (this.classList.contains('zoom-active')) {
                                const rect = this.getBoundingClientRect();
                                const x = e.clientX - rect.left;
                                const y = e.clientY - rect.top;
                                const xPercent = (x / rect.width) * 100;
                                const yPercent = (y / rect.height) * 100;
                                
                                img.style.transformOrigin = `${xPercent}% ${yPercent}%`;
                            } else {
                                img.style.transformOrigin = 'center center';
                            }
                        });

                        container.addEventListener('mousemove', function(e) {
                            if (this.classList.contains('zoom-active')) {
                                const rect = this.getBoundingClientRect();
                                const x = e.clientX - rect.left;
                                const y = e.clientY - rect.top;
                                const xPercent = (x / rect.width) * 100;
                                const yPercent = (y / rect.height) * 100;
                                
                                img.style.transformOrigin = `${xPercent}% ${yPercent}%`;
                            }
                        });
                    });
                });
            </script>
        </div>

        <!-- Product Info -->
        <div class="lg:col-span-5 flex flex-col justify-start pt-8">
            <div class="mb-10">
                <p class="text-xs tracking-widest uppercase text-secondary mb-4">{{ $product->category->name ?? 'Essentials' }}</p>
                <h1 class="text-4xl lg:text-5xl font-serif font-medium text-primary dark:text-white mb-6 leading-tight transition-colors">{{ $product->title }}</h1>
                <div class="font-light tracking-wide transition-colors flex items-center flex-wrap gap-4">
                    @if($product->is_discount_active && $product->discount_price)
                        <span class="text-3xl text-red-600 dark:text-red-400 font-bold">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</span>
                        <span class="text-xl text-secondary line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    @else
                        <span class="text-2xl text-primary dark:text-white">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    @endif
                </div>
            </div>

            <div class="prose prose-sm prose-gray max-w-none text-secondary dark:text-gray-400 font-light leading-relaxed mb-12 transition-colors">
                <div class="whitespace-pre-line">{!! $product->description !!}</div>
            </div>

            <!-- Actions -->
            <div class="border-t border-thin dark:border-gray-800 pt-10 mt-auto transition-colors">
                @if($product->status == 'active' && $product->stock > 0)
                    @if(auth()->user() && auth()->user()->isAdmin())
                        <div class="flex gap-4">
                            <a href="{{ route('products.edit', $product->id) }}" class="w-full text-center px-8 py-4 text-xs tracking-widest uppercase font-medium text-white bg-primary dark:bg-white dark:text-primary hover:bg-black dark:hover:bg-gray-200 transition-colors">
                                Edit Produk
                            </a>
                        </div>
                    @else
                        <div class="flex items-center justify-between text-xs tracking-widest uppercase text-secondary mb-6">
                            <span>Availability</span>
                            @if($product->stock < 5)
                                <span class="text-red-500 dark:text-red-400">Only {{ $product->stock }} left</span>
                            @else
                                <span class="text-primary dark:text-white transition-colors">In Stock</span>
                            @endif
                        </div>

                        <div class="flex gap-4 mb-6">
                            <div class="flex flex-1 gap-4">
                                <div class="w-24 border border-thin dark:border-gray-700 relative transition-colors">
                                    <label class="sr-only">Quantity</label>
                                    <input type="number" id="qty-{{ $product->id }}" value="1" min="1" max="{{ $product->stock }}" 
                                           class="w-full h-full p-4 text-center text-sm focus:outline-none focus:ring-0 bg-transparent text-primary dark:text-white transition-colors" oninput="document.getElementById('form-qty-{{ $product->id }}').value = this.value">
                                </div>
                                
                                <form action="{{ route('cart.store') }}" method="POST" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1" id="form-qty-{{ $product->id }}">
                                    <button type="submit" class="w-full h-full px-8 py-4 text-xs tracking-widest uppercase font-medium text-white bg-primary dark:bg-white dark:text-primary hover:bg-black dark:hover:bg-gray-200 transition-all border border-primary dark:border-white">
                                        Beli Sekarang
                                    </button>
                                </form>
                            </div>
                            
                            <button type="button" onclick="addToCart({{ $product->id }}, document.getElementById('qty-{{ $product->id }}').value, this)" class="w-14 h-full min-h-[56px] flex-shrink-0 flex items-center justify-center border border-thin dark:border-gray-700 text-secondary dark:text-gray-400 hover:text-primary dark:hover:text-white hover:border-black dark:hover:border-white transition-colors bg-white dark:bg-primary" title="Tambah ke Keranjang">
                                <i class="fa-solid fa-cart-plus text-lg"></i>
                            </button>
                            
                            <button type="button" onclick="addToWishlist({{ $product->id }}, this)" class="w-14 h-full min-h-[56px] flex-shrink-0 flex items-center justify-center border border-thin dark:border-gray-700 text-secondary dark:text-gray-400 hover:text-red-500 dark:hover:text-red-400 hover:border-red-200 dark:hover:border-red-900 transition-colors bg-white dark:bg-primary" title="Favoritkan">
                                @php
                                    $inWishlist = false;
                                    if(auth()->check()) {
                                        $inWishlist = \App\Models\Wishlist::where('user_id', auth()->id())->where('product_id', $product->id)->exists();
                                    }
                                @endphp
                                <i class="{{ $inWishlist ? 'fa-solid fa-heart text-red-500' : 'fa-regular fa-heart' }} text-lg wishlist-icon"></i>
                            </button>
                        </div>
                    @endif
                @else
                   <button disabled class="w-full px-8 py-4 text-xs tracking-widest uppercase font-medium text-secondary bg-gray-100 dark:bg-gray-800 border border-thin dark:border-gray-700 cursor-not-allowed mb-6 transition-colors">
                        {{ $product->stock <= 0 ? 'Out of Stock' : 'Unavailable' }}
                   </button>
                @endif
                
                <!-- External Link -->
                @if($product->link_shopee)
                    <a href="{{ $product->link_shopee }}" target="_blank" class="w-full block text-center px-8 py-4 text-xs tracking-widest uppercase font-medium text-primary dark:text-white border border-thin dark:border-gray-700 hover:border-black dark:hover:border-white transition-colors">
                        Available on Shopee
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Editorial Reviews Section -->
    <div class="mt-24 pt-16 border-t border-thin dark:border-gray-800 max-w-4xl mx-auto transition-colors">
        <h3 class="text-2xl font-serif font-medium text-primary dark:text-white mb-12 text-center transition-colors">
            Client Impressions
        </h3>

        <!-- Reviews List -->
        <div class="space-y-12 mb-16">
            @forelse($product->reviews as $review)
                <div class="pb-12 border-b border-gray-100 dark:border-gray-800 last:border-0 last:pb-0 transition-colors">
                    <div class="flex items-center justify-between mb-6 text-xs tracking-widest uppercase">
                        <span class="font-medium text-primary dark:text-white transition-colors">{{ $review->user->name }}</span>
                        <span class="text-secondary">{{ $review->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="text-primary dark:text-white text-[10px] mb-4 space-x-1 transition-colors">
                        @for($i=0; $i<$review->rating; $i++) <i class="fa-solid fa-star"></i> @endfor
                    </div>
                    <p class="text-secondary dark:text-gray-400 font-light leading-relaxed italic transition-colors">"{{ $review->comment }}"</p>
                </div>
            @empty
                <div class="text-center py-12">
                    <p class="text-sm text-secondary font-light italic">No impressions yet for this item.</p>
                </div>
            @endforelse
        </div>

        <!-- Distinctive Review Form -->
        <div class="bg-gray-50 dark:bg-[#151515] border border-thin dark:border-gray-800 p-8 md:p-12 transition-colors">
            <h4 class="text-xs tracking-widest uppercase font-medium text-primary dark:text-white mb-8 text-center transition-colors">Leave your impression</h4>
            <form action="{{ route('reviews.store') }}" method="POST" class="max-w-xl mx-auto">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <div class="mb-8">
                    <label class="block text-xs text-secondary uppercase tracking-widest mb-4 text-center">Rate your experience</label>
                    <div class="flex justify-center gap-6 flex-row-reverse">
                        @foreach([5, 4, 3, 2, 1] as $rating)
                            <label class="group cursor-pointer">
                                <input type="radio" name="rating" value="{{ $rating }}" class="peer sr-only" {{ $rating == 5 ? 'checked' : '' }}>
                                <i class="fa-solid fa-star text-gray-300 dark:text-gray-600 peer-checked:text-primary dark:peer-checked:text-white group-hover:text-primary dark:group-hover:text-white transition-colors cursor-pointer text-lg"></i>
                            </label>
                        @endforeach
                    </div>
                </div>
                
                <div class="mb-8">
                    <textarea name="comment" rows="4" class="w-full px-4 py-4 bg-transparent border-b border-thin dark:border-gray-700 focus:border-black dark:focus:border-white focus:outline-none transition-colors resize-none text-sm font-light text-primary dark:text-white placeholder:text-gray-400" placeholder="Share your thoughts..."></textarea>
                </div>
                
                <div class="text-center">
                    <button type="submit" class="px-10 py-3 text-xs tracking-widest uppercase font-medium text-primary dark:text-white border border-primary dark:border-white hover:bg-primary hover:text-white dark:hover:bg-white dark:hover:text-black transition-colors">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
