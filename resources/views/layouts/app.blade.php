<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SHYNESS | Premium UI')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <!-- Vite CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
        
        /* Luxury Preloader */
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.8s cubic-bezier(0.65, 0, 0.35, 1), visibility 0.8s;
        }

        .dark #preloader {
            background-color: #111111;
        }

        .preloader-logo {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 600;
            letter-spacing: 0.5em;
            text-transform: uppercase;
            color: #111111;
            margin-bottom: 20px;
            opacity: 0;
            transform: translateY(20px);
            animation: preloaderText 1.2s ease-out forwards;
        }

        .dark .preloader-logo {
            color: #ffffff;
        }

        .preloader-bar-container {
            width: 150px;
            height: 1px;
            background-color: #eeeeee;
            overflow: hidden;
            position: relative;
        }

        .dark .preloader-bar-container {
            background-color: #222222;
        }

        .preloader-bar {
            position: absolute;
            top: 0;
            left: 0;
            width: 0%;
            height: 100%;
            background-color: #111111;
            transition: width 0.5s ease-out;
            animation: preloaderProgress 2s cubic-bezier(0.65, 0, 0.35, 1) forwards;
        }

        .dark .preloader-bar {
            background-color: #ffffff;
        }

        @keyframes preloaderText {
            to {
                opacity: 1;
                transform: translateY(0);
                letter-spacing: 0.8em;
            }
        }

        @keyframes preloaderProgress {
            0% { width: 0%; }
            50% { width: 70%; }
            100% { width: 100%; }
        }

        .preloader-hidden {
            opacity: 0 !important;
            visibility: hidden !important;
        }

        /* Premium minimal scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e4e4e7; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #a1a1aa; }
        
        .fade-in-up { animation: fadeInUp 0.4s ease-out forwards; opacity: 0; transform: translateY(10px); }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
        
        .border-thin { border: 1px solid #E5E5E5; }
        
        /* Force hide hamburger menu on tablet and desktop */
        @media (min-width: 768px) {
            #mobile-menu-btn { display: none !important; }
        }
    </style>
    
    <script>
        // Minimalist Theme Script checking
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @stack('styles')
</head>
<body class="bg-white text-primary antialiased min-h-screen flex flex-col selection:bg-black selection:text-white dark:bg-primary dark:text-gray-200 transition-colors duration-300">

    <!-- Luxury Preloader -->
    <div id="preloader">
        <div class="preloader-logo">Shyness</div>
        <div class="preloader-bar-container">
            <div class="preloader-bar"></div>
        </div>
    </div>

    <!-- Minimalist Navbar -->
    <nav class="w-full bg-white dark:bg-primary border-b border-thin sticky top-0 z-40 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Branding -->
                <div class="flex items-center">
                    <a href="{{ auth()->check() ? (auth()->user()->isAdmin() ? route('admin.dashboard') : route('products.index')) : route('welcome') }}" class="font-serif font-semibold text-2xl tracking-widest uppercase hover:opacity-70 transition-opacity">
                        Shyness
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-xs tracking-widest uppercase {{ request()->routeIs('admin.dashboard') ? 'font-medium border-b border-black dark:border-white' : 'text-secondary hover:text-primary dark:hover:text-white transition-colors' }}">Dashboard</a>
                            <a href="{{ route('admin.pos') }}" class="text-xs tracking-widest uppercase {{ request()->routeIs('admin.pos') ? 'font-medium border-b border-black dark:border-white' : 'text-secondary hover:text-primary dark:hover:text-white transition-colors' }}">POS</a>
                            <a href="{{ route('posts.index') }}" class="text-xs tracking-widest uppercase {{ request()->routeIs('posts.*') ? 'font-medium border-b border-black dark:border-white' : 'text-secondary hover:text-primary dark:hover:text-white transition-colors' }}">Postingan</a>
                            <a href="{{ route('categories.index') }}" class="text-xs tracking-widest uppercase {{ request()->routeIs('categories.*') ? 'font-medium border-b border-black dark:border-white' : 'text-secondary hover:text-primary dark:hover:text-white transition-colors' }}">Kategori</a>
                            <a href="{{ route('admin.sellers.contracts') }}" class="text-xs tracking-widest uppercase {{ request()->routeIs('admin.sellers.*') ? 'font-medium border-b border-black dark:border-white' : 'text-secondary hover:text-primary dark:hover:text-white transition-colors' }}">Penjual</a>
                            <a href="{{ route('admin.suppliers.pending') }}" class="text-xs tracking-widest uppercase {{ request()->routeIs('admin.suppliers.*') ? 'font-medium border-b border-black dark:border-white' : 'text-secondary hover:text-primary dark:hover:text-white transition-colors' }}">Supplier</a>
                            <a href="{{ route('admin.contracts.index') }}" class="text-xs tracking-widest uppercase {{ request()->routeIs('admin.contracts.*') ? 'font-medium border-b border-black dark:border-white' : 'text-secondary hover:text-primary dark:hover:text-white transition-colors' }}">Kontrak</a>
                            <a href="{{ route('banners.index') }}" class="text-xs tracking-widest uppercase {{ request()->routeIs('banners.*') ? 'font-medium border-b border-black dark:border-white' : 'text-secondary hover:text-primary dark:hover:text-white transition-colors' }}">Banner</a>
                        @endif
                        
                        <a href="{{ route('products.index') }}" class="text-xs tracking-widest uppercase {{ request()->routeIs('products.*') ? 'font-medium border-b border-black dark:border-white' : 'text-secondary hover:text-primary dark:hover:text-white transition-colors' }}">Produk</a>
                        
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('orders.index') }}" class="text-xs tracking-widest uppercase {{ request()->routeIs('orders.*') ? 'font-medium border-b border-black dark:border-white' : 'text-secondary hover:text-primary dark:hover:text-white transition-colors' }}">Pesanan</a>
                            <a href="{{ route('admin.payment-options.index') }}" class="text-xs tracking-widest uppercase {{ request()->routeIs('admin.payment-options.*') ? 'font-medium border-b border-black dark:border-white' : 'text-secondary hover:text-primary dark:hover:text-white transition-colors' }}">Opsi Pembayaran</a>
                        @else
                            <a href="{{ route('orders.my') }}" class="text-xs tracking-widest uppercase {{ request()->routeIs('orders.*') ? 'font-medium border-b border-black dark:border-white' : 'text-secondary hover:text-primary dark:hover:text-white transition-colors' }}">Pesanan Saya</a>
                        @endif
                    @else
                        <a href="{{ route('products.index') }}" class="text-xs tracking-widest uppercase {{ request()->routeIs('products.*') ? 'font-medium border-b border-black dark:border-white' : 'text-secondary hover:text-primary dark:hover:text-white transition-colors' }}">Koleksi</a>
                    @endauth
                </div>

                <!-- Right Side Actions & Mobile Menu Toggle -->
                <div class="flex items-center space-x-6">
                    <!-- Search Icon -->
                    <a href="{{ route('search.index') }}" class="text-secondary hover:text-primary dark:hover:text-white transition-colors">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </a>
                    
                    <!-- Theme Toggle -->
                    <button id="theme-toggle" class="text-secondary hover:text-primary dark:hover:text-white transition-colors focus:outline-none">
                        <i id="theme-icon" class="fa-solid fa-moon text-sm"></i>
                    </button>

                    @auth
                        <!-- Navbar Cart -->
                        @php
                            $cartCount = \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity');
                        @endphp
                        <a href="{{ route('cart.index') }}" class="relative text-secondary hover:text-primary dark:hover:text-white transition-colors flex items-center group">
                            <i class="fa-solid fa-bag-shopping text-sm"></i>
                            <span id="cart-badge" class="{{ $cartCount > 0 ? 'flex' : 'hidden' }} absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full items-center justify-center">
                                {{ $cartCount }}
                            </span>
                        </a>

                        <!-- Desktop & Mobile Profile Dropdown -->
                        <div class="relative group">
                            <button id="profile-menu-btn" class="flex items-center gap-2 cursor-pointer focus:outline-none">
                                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'U' }}&background=111111&color=fff&rounded=true" class="w-8 h-8 rounded-full border border-thin">
                            </button>
                            <!-- Dropdown -->
                            <div id="profile-dropdown" class="absolute right-0 top-10 mt-2 w-48 bg-white dark:bg-primary border border-thin shadow-sm opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                                <div class="p-4 border-b border-thin">
                                    <p class="text-xs font-medium truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-[10px] text-secondary uppercase tracking-widest">{{ Auth::user()->role ?? 'User' }}</p>
                                </div>
                                <div class="py-1">
                                    <a href="{{ route('addresses.index') }}" class="block px-4 py-2 text-xs tracking-widest uppercase text-secondary hover:text-primary dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                        Alamat Saya
                                    </a>
                                    <a href="{{ route('wishlist.index') }}" class="block px-4 py-2 text-xs tracking-widest uppercase text-secondary hover:text-primary dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                        Wishlist
                                    </a>
                                    <a href="{{ route('cart.index') }}" class="block px-4 py-2 text-xs tracking-widest uppercase text-secondary hover:text-primary dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                        Keranjang
                                    </a>
                                    @if(!auth()->user()->isAdmin())
                                    <a href="{{ route('seller.index') }}" class="block px-4 py-2 text-xs tracking-widest uppercase text-secondary hover:text-primary dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                        {{ auth()->user()->isPenjual() ? 'Dashboard Penjual' : 'Jadi Penjual' }}
                                    </a>
                                    @if(auth()->user()->isSupplier())
                                    <a href="{{ route('admin.contracts.index') }}" class="block px-4 py-2 text-xs tracking-widest uppercase text-secondary hover:text-primary dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                        Kontrak Saya
                                    </a>
                                    @else
                                    <a href="{{ route('supplier.register') }}" class="block px-4 py-2 text-xs tracking-widest uppercase text-secondary hover:text-primary dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                        Daftar Supplier
                                    </a>
                                    @endif
                                    @endif
                                </div>
                                <div class="py-1 border-t border-thin">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-xs tracking-widest uppercase text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors">
                                            Log Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="hidden md:inline-block text-xs tracking-widest uppercase hover:underline underline-offset-4">Log In</a>
                    @endauth

                    <!-- Mobile Hamburger -->
                    <button type="button" id="mobile-menu-btn" class="md:hidden text-secondary hover:text-primary dark:hover:text-white focus:outline-none p-2 -mr-2 relative z-50">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div id="mobile-menu" class="hidden md:hidden bg-white dark:bg-primary border-t border-thin absolute w-full left-0 top-20 px-4 py-4 shadow-lg transition-colors duration-300 z-40">
            <div class="flex flex-col space-y-4">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block text-xs md:hidden tracking-widest uppercase {{ request()->routeIs('admin.dashboard') ? 'font-medium text-primary dark:text-white' : 'text-secondary hover:text-primary dark:hover:text-white' }}">Dashboard</a>
                        <a href="{{ route('admin.pos') }}" class="block text-xs md:hidden tracking-widest uppercase {{ request()->routeIs('admin.pos') ? 'font-medium text-primary dark:text-white' : 'text-secondary hover:text-primary dark:hover:text-white' }}">POS</a>
                        <a href="{{ route('posts.index') }}" class="block text-xs md:hidden tracking-widest uppercase {{ request()->routeIs('posts.*') ? 'font-medium text-primary dark:text-white' : 'text-secondary hover:text-primary dark:hover:text-white' }}">Postingan</a>
                        <a href="{{ route('categories.index') }}" class="block text-xs md:hidden tracking-widest uppercase {{ request()->routeIs('categories.*') ? 'font-medium text-primary dark:text-white' : 'text-secondary hover:text-primary dark:hover:text-white' }}">Kategori</a>
                        <a href="{{ route('admin.sellers.contracts') }}" class="block text-xs md:hidden tracking-widest uppercase {{ request()->routeIs('admin.sellers.*') ? 'font-medium text-primary dark:text-white' : 'text-secondary hover:text-primary dark:hover:text-white' }}">Penjual</a>
                        <a href="{{ route('admin.suppliers.pending') }}" class="block text-xs md:hidden tracking-widest uppercase {{ request()->routeIs('admin.suppliers.*') ? 'font-medium text-primary dark:text-white' : 'text-secondary hover:text-primary dark:hover:text-white' }}">Supplier</a>
                        <a href="{{ route('admin.contracts.index') }}" class="block text-xs md:hidden tracking-widest uppercase {{ request()->routeIs('admin.contracts.*') ? 'font-medium text-primary dark:text-white' : 'text-secondary hover:text-primary dark:hover:text-white' }}">Kontrak</a>
                        <a href="{{ route('banners.index') }}" class="block text-xs md:hidden tracking-widest uppercase {{ request()->routeIs('banners.*') ? 'font-medium text-primary dark:text-white' : 'text-secondary hover:text-primary dark:hover:text-white' }}">Banner</a>
                    @endif
                    <a href="{{ route('products.index') }}" class="block text-xs md:hidden tracking-widest uppercase {{ request()->routeIs('products.*') ? 'font-medium text-primary dark:text-white' : 'text-secondary hover:text-primary dark:hover:text-white' }}">Produk</a>
                    
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('orders.index') }}" class="block text-xs md:hidden tracking-widest uppercase {{ request()->routeIs('orders.*') ? 'font-medium text-primary dark:text-white' : 'text-secondary hover:text-primary dark:hover:text-white' }}">Pesanan</a>
                        <a href="{{ route('admin.payment-options.index') }}" class="block text-xs md:hidden tracking-widest uppercase {{ request()->routeIs('admin.payment-options.*') ? 'font-medium text-primary dark:text-white' : 'text-secondary hover:text-primary dark:hover:text-white' }}">Opsi Pembayaran</a>
                    @else
                        <a href="{{ route('orders.my') }}" class="block text-xs md:hidden tracking-widest uppercase {{ request()->routeIs('orders.*') ? 'font-medium text-primary dark:text-white' : 'text-secondary hover:text-primary dark:hover:text-white' }}">Pesanan Saya</a>
                    @endif
                @else
                    <a href="{{ route('products.index') }}" class="block text-xs md:hidden tracking-widest uppercase {{ request()->routeIs('products.*') ? 'font-medium text-primary dark:text-white' : 'text-secondary hover:text-primary dark:hover:text-white' }}">Koleksi</a>
                    <a href="{{ route('login') }}" class="block text-xs md:hidden tracking-widest uppercase text-secondary hover:text-primary dark:hover:text-white">Log In</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-grow max-w-7xl mx-auto w-full px-4 sm:px-8 py-10 fade-in-up md:mt-0">
        @yield('content')
    </main>

    <!-- Essential Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        // Preloader Hiding Logic
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                setTimeout(() => {
                    preloader.classList.add('preloader-hidden');
                }, 500); // Small delay for aesthetic feel
            }
        });

        toastr.options = { "positionClass": "toast-bottom-right", "progressBar": true, "showDuration": "300" };
        @if(session()->has('success')) toastr.success('{{ session('success') }}'); @endif
        @if(session()->has('error')) toastr.error('{{ session('error') }}'); @endif
        @if($errors->any())
            toastr.error('Terdapat kesalahan pada form, periksa kembali input Anda.');
        @endif

        // Elegant Theme Toggle
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        
        const updateIconSettings = () => {
            if (document.documentElement.classList.contains('dark')) {
                themeIcon.classList.replace('fa-moon', 'fa-sun');
            } else {
                themeIcon.classList.replace('fa-sun', 'fa-moon');
            }
        };
        
        updateIconSettings();

        themeToggleBtn.addEventListener('click', function() {
            document.documentElement.classList.toggle('dark');
            const isDark = document.documentElement.classList.contains('dark');
            localStorage.theme = isDark ? 'dark' : 'light';
            updateIconSettings();
        });

        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Profile Menu Click Toggle (For Mobile Support)
        const profileMenuBtn = document.getElementById('profile-menu-btn');
        const profileDropdown = document.getElementById('profile-dropdown');
        
        if (profileMenuBtn) {
            profileMenuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                profileDropdown.classList.toggle('opacity-0');
                profileDropdown.classList.toggle('invisible');
            });

            // Close when clicking outside
            document.addEventListener('click', (e) => {
                if (!profileMenuBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                    profileDropdown.classList.add('opacity-0', 'invisible');
                }
            });
        }

        // Global Wishlist Functions
        async function addToWishlist(productId, btn) {
            const icon = btn.querySelector('.wishlist-icon');
            const card = btn.closest('.product-card');
            const image = card ? card.querySelector('.select-image') : null;
            
            const wasSolid = icon.classList.contains('fa-solid');
            
            // Set loading
            icon.className = 'fa-solid fa-spinner fa-spin text-sm wishlist-icon';

            try {
                const response = await fetch('{{ route("wishlist.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ product_id: productId })
                });
                
                if (response.status === 401) {
                    window.location.href = '/login';
                    return;
                }

                const data = await response.json();
                
                if (data.success) {
                    if (data.action === 'added') {
                        icon.className = 'fa-solid fa-heart text-red-500 text-sm wishlist-icon transition-all scale-110';
                        setTimeout(() => icon.classList.remove('scale-110'), 200);
                        if (typeof toastr !== 'undefined') toastr.success(data.message);
                        if (image) flyToProfile(image);
                    } else if (data.action === 'removed') {
                        icon.className = 'fa-regular fa-heart text-sm wishlist-icon transition-all scale-110';
                        setTimeout(() => icon.classList.remove('scale-110'), 200);
                        if (typeof toastr !== 'undefined') toastr.info(data.message);
                    }
                } else {
                    icon.className = (wasSolid ? 'fa-solid fa-heart text-red-500' : 'fa-regular fa-heart') + ' text-sm wishlist-icon';
                    if (typeof toastr !== 'undefined') toastr.error(data.message || 'Terjadi kesalahan.');
                }
            } catch (e) {
                icon.className = (wasSolid ? 'fa-solid fa-heart text-red-500' : 'fa-regular fa-heart') + ' text-sm wishlist-icon';
                if (typeof toastr !== 'undefined') toastr.error('Terjadi kesalahan koneksi.');
            }
        }

        async function addToCart(productId, quantity = 1, btn = null) {
            let originalText = '';
            if (btn) {
                originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
                btn.disabled = true;
            }

            try {
                const response = await fetch('{{ route("cart.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ product_id: productId, quantity: quantity })
                });

                if (response.status === 401) {
                    window.location.href = '/login';
                    return;
                }

                const data = await response.json();

                if (data.success) {
                    if (typeof toastr !== 'undefined') toastr.success(data.message);
                    
                    // Update cart badge
                    const badge = document.getElementById('cart-badge');
                    if (badge) {
                        badge.textContent = data.cart_count;
                        badge.classList.remove('hidden');
                        badge.classList.add('flex');
                        
                        // Bounce animation
                        badge.classList.add('animate-bounce');
                        setTimeout(() => badge.classList.remove('animate-bounce'), 1000);
                    }
                    
                    // Optional fly to bag
                    const card = btn ? btn.closest('.product-card') : null;
                    const image = card ? card.querySelector('.select-image') : null;
                    if (image) flyToProfile(image, 'bag'); // Modify flyToProfile to accept target

                } else {
                    if (typeof toastr !== 'undefined') toastr.error(data.message || 'Terjadi kesalahan.');
                }
            } catch (e) {
                if (typeof toastr !== 'undefined') toastr.error('Terjadi kesalahan koneksi.');
            } finally {
                if (btn) {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            }
        }

        function flyToProfile(imageElement, targetType = 'profile') {
            let selector = 'nav img.rounded-full, nav .fa-user'; // Default profile target
            if (targetType === 'bag') {
                selector = 'nav .fa-bag-shopping';
            }
            const targetEl = document.querySelector(selector);
            if (!targetEl || !imageElement) return;

            const clone = imageElement.cloneNode(true);
            const rect = imageElement.getBoundingClientRect();
            const targetRect = targetEl.getBoundingClientRect();

            Object.assign(clone.style, {
                position: 'fixed',
                top: rect.top + 'px',
                left: rect.left + 'px',
                width: rect.width + 'px',
                height: rect.height + 'px',
                objectFit: 'cover',
                zIndex: 9999,
                transition: 'all 0.8s cubic-bezier(0.25, 1, 0.5, 1)',
                borderRadius: '8px',
                boxShadow: '0 10px 25px rgba(0,0,0,0.2)',
                pointerEvents: 'none'
            });

            document.body.appendChild(clone);

            requestAnimationFrame(() => {
                Object.assign(clone.style, {
                    top: targetRect.top + 'px',
                    left: targetRect.left + 'px',
                    width: '20px',
                    height: '20px',
                    opacity: '0.2',
                    borderRadius: '50%',
                    transform: 'scale(0.5)'
                });
            });

            setTimeout(() => {
                clone.remove();
                targetEl.style.transition = 'transform 0.2s ease-out';
                targetEl.style.transform = 'scale(1.3)';
                setTimeout(() => {
                    targetEl.style.transform = 'scale(1)';
                }, 200);
            }, 800);
        }

        // Global Script for File Input Previews
        document.addEventListener('DOMContentLoaded', function() {
            document.body.addEventListener('change', function(e) {
                if (e.target && e.target.tagName === 'INPUT' && e.target.type === 'file' && e.target.hasAttribute('data-preview-container')) {
                    generateFilePreview(e.target);
                }
            });

            function generateFilePreview(input) {
                const containerSelector = input.getAttribute('data-preview-container');
                const container = document.querySelector(containerSelector);
                if (!container) return;

                // Clear previous dynamic previews
                container.querySelectorAll('.dynamic-preview').forEach(el => el.remove());

                const files = Array.from(input.files);
                files.forEach((file, index) => {
                    const isVideo = file.type.startsWith('video/');
                    const isImage = file.type.startsWith('image/');
                    
                    if (!isVideo && !isImage) return;

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'dynamic-preview relative rounded overflow-hidden border border-gray-200 aspect-square inline-block align-top mt-2 mr-2 w-24 h-24 mb-2';
                        
                        let mediaEl;
                        if (isImage) {
                            mediaEl = document.createElement('img');
                            mediaEl.src = e.target.result;
                            mediaEl.className = 'w-full h-full object-cover';
                        } else if (isVideo) {
                            mediaEl = document.createElement('video');
                            mediaEl.src = e.target.result;
                            mediaEl.className = 'w-full h-full object-cover';
                            mediaEl.muted = true;
                        }

                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-[10px] hover:bg-red-600 shadow z-10 transition-colors';
                        removeBtn.innerHTML = '<i class="fa fa-times"></i>';
                        removeBtn.onclick = () => {
                            const dt = new DataTransfer();
                            const currentFiles = Array.from(input.files);
                            // We need to find the correct index again because previous files might be deleted
                            const fileIndex = currentFiles.indexOf(file);
                            if (fileIndex !== -1) {
                                currentFiles.splice(fileIndex, 1);
                            }
                            currentFiles.forEach(f => dt.items.add(f));
                            input.files = dt.files;
                            wrapper.remove();
                        };

                        wrapper.appendChild(mediaEl);
                        wrapper.appendChild(removeBtn);
                        container.appendChild(wrapper);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
