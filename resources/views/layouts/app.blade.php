<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Shyness OS')</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f9fafb; }
        .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f3f4f6; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
        .fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    <script>
        // Check local storage for theme
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 antialiased dark:bg-gray-900 dark:text-gray-100 transition-colors duration-300">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 px-4 py-3 shadow-sm sticky top-0 z-30">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <a href="{{ route('posts.index') }}" class="flex items-center gap-2 hover:opacity-80 transition group">
                    <img src="{{ asset('storage/images/shyness.png') }}" class="h-9 w-auto object-contain"
                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=S&background=000&color=fff';">
                    <h1 class="font-bold text-xl text-gray-900 tracking-tight">SHYNESS</h1>
                </a>
            </div>

            <!-- MENU NAVIGASI -->
            <div class="flex items-center gap-6">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('posts.index') }}" class="text-sm font-medium {{ request()->routeIs('posts.*') ? 'text-gray-900 border-b-2 border-gray-900 font-bold' : 'text-gray-500 hover:text-gray-900 hover:border-b-2 hover:border-gray-300' }} pb-0.5 transition-all">
                        Postingan
                    </a>
                @endif

                <a href="{{ route('products.index') }}" class="text-sm font-medium {{ request()->routeIs('products.*') ? 'text-gray-900 border-b-2 border-gray-900 font-bold' : 'text-gray-500 hover:text-gray-900 hover:border-b-2 hover:border-gray-300' }} pb-0.5 transition-all">
                    Produk
                </a>

                @if(auth()->user()->isAdmin())
                    <a href="{{ route('categories.index') }}" class="text-sm font-medium {{ request()->routeIs('categories.*') ? 'text-gray-900 border-b-2 border-gray-900 font-bold' : 'text-gray-500 hover:text-gray-900 hover:border-b-2 hover:border-gray-300' }} pb-0.5 transition-all">
                        Kategori
                    </a>

                    <a href="{{ route('videos.index') }}" class="text-sm font-medium {{ request()->routeIs('videos.*') ? 'text-gray-900 border-b-2 border-gray-900 font-bold' : 'text-gray-500 hover:text-gray-900 hover:border-b-2 hover:border-gray-300' }} pb-0.5 transition-all">
                        Video
                    </a>
                @endif

                @if(auth()->user()->isAdmin())
                    <a href="{{ route('orders.index') }}" class="text-sm font-medium {{ request()->routeIs('orders.*') ? 'text-gray-900 border-b-2 border-gray-900 font-bold' : 'text-gray-500 hover:text-gray-900 hover:border-b-2 hover:border-gray-300' }} pb-0.5 transition-all">
                        Kelola Pesanan
                    </a>
                @else
                    <a href="{{ route('orders.my') }}" class="text-sm font-medium {{ request()->routeIs('orders.*') ? 'text-gray-900 border-b-2 border-gray-900 font-bold' : 'text-gray-500 hover:text-gray-900 hover:border-b-2 hover:border-gray-300' }} pb-0.5 transition-all">
                        Pesanan Saya
                    </a>
                @endif
                
                <!-- Search Bar -->
                <form action="{{ route('search.index') }}" method="GET" class="hidden md:flex items-center ml-auto mr-6">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                             <i class="fa fa-search text-gray-400"></i>
                        </span>
                        <input type="text" name="q" class="w-64 py-2 pl-10 pr-4 text-sm bg-gray-100 border border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-gray-200 focus:bg-white transition-all placeholder-gray-400" placeholder="Cari produk, post, video..." value="{{ request('q') }}">
                    </div>
                </form>

                <div class="flex items-center gap-3 pl-6 border-l border-gray-200">
                    <!-- Dark Mode Toggle -->
                    <button id="theme-toggle" class="p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 focus:outline-none transition-colors mr-2">
                        <i id="theme-icon" class="fas fa-moon"></i>
                    </button>
                    
                    <div class="hidden md:block text-xs text-right">
                        <div class="font-bold text-gray-700">{{ Auth::user()->name ?? 'User' }}</div>
                        <div class="text-gray-400 capitalize">{{ Auth::user()->role ?? 'Guest' }}</div>
                    </div>
                    <div class="relative group">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'User' }}&background=1f2937&color=fff" class="w-8 h-8 rounded-full border border-gray-300 cursor-pointer">
                        <!-- Dropdown Logout -->
                        <div class="hidden group-hover:block absolute right-0 top-8 w-48 bg-white dark:bg-gray-800 shadow-lg rounded-lg py-2 border border-gray-100 dark:border-gray-700 z-50">
                            <a href="{{ route('wishlist.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <i class="fa fa-heart text-pink-500 mr-2"></i> Wishlist
                            </a>
                            <a href="{{ route('cart.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <i class="fa fa-shopping-cart text-blue-500 mr-2"></i> Keranjang
                            </a>
                            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                    <i class="fa fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        @if(session()->has('success')) toastr.success('{{ session('success') }}', 'BERHASIL!'); @endif
        @if(session()->has('error')) toastr.error('{{ session('error') }}', 'GAGAL!'); @endif

        // Theme Toggle Logic
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        
        // Icon logic
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeIcon.classList.remove('fa-moon');
            themeIcon.classList.add('fa-sun');
        } else {
            themeIcon.classList.remove('fa-sun');
            themeIcon.classList.add('fa-moon');
        }

        themeToggleBtn.addEventListener('click', function() {
            // if set via local storage previously
            if (localStorage.theme === 'dark') {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
