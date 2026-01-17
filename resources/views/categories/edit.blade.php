<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori - Shyness OS</title>

    <!-- Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>body { font-family: 'Inter', sans-serif; background-color: #f9fafb; }</style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 px-4 py-3 shadow-sm sticky top-0 z-30">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <a href="{{ route('posts.index') }}" class="flex items-center gap-2 hover:opacity-80 transition group">
                    <img src="{{ asset('storage/images/shyness.png') }}" alt="Logo" class="h-9 w-auto object-contain"
                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=S&background=000&color=fff';">
                    <h1 class="font-bold text-xl text-gray-900 tracking-tight">SHYNESS</h1>
                </a>
            </div>

            <!-- MENU NAVIGASI LENGKAP -->
            <div class="flex items-center gap-6">
                <!-- Link ke Postingan -->
                <a href="{{ route('posts.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors pb-0.5 hover:border-b-2 hover:border-gray-300">
                    Postingan
                </a>

                <!-- Link ke Produk -->
                <a href="{{ route('products.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors pb-0.5 hover:border-b-2 hover:border-gray-300">
                    Produk
                </a>

                <!-- Link ke Kategori (Aktif - Bold) -->
                <a href="{{ route('categories.index') }}" class="text-sm font-bold text-gray-900 border-b-2 border-gray-900 pb-0.5">
                    Kategori
                </a>

                <!-- Link ke Video -->
                <a href="{{ route('videos.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors pb-0.5 hover:border-b-2 hover:border-gray-300">
                    Video
                </a>

                <div class="flex items-center gap-2 ml-2 pl-6 border-l border-gray-200">
                    <div class="hidden md:block text-xs text-right">
                        <div class="font-bold text-gray-700">Admin</div>
                        <div class="text-gray-400">Panel</div>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=Admin&background=1f2937&color=fff" class="w-8 h-8 rounded-full border border-gray-300">
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- Header & Back Button -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">Edit Kategori</h2>
                <p class="mt-1 text-sm text-gray-500">Perbarui nama atau deskripsi kategori produk.</p>
            </div>
            <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:text-gray-900 transition-all">
                <i class="fa fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Input Nama -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori</label>
                        <input type="text" name="name"
                            class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 focus:bg-white focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all outline-none @error('name') border-red-500 @enderror"
                            placeholder="Contoh: T-Shirt" value="{{ old('name', $category->name) }}">

                        @error('name')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fa fa-circle-exclamation mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Input Deskripsi -->
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi (Opsional)</label>
                        <textarea name="description" rows="4"
                            class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 focus:bg-white focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all outline-none"
                            placeholder="Keterangan singkat tentang kategori ini...">{{ old('description', $category->description) }}</textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <button type="submit" class="inline-flex justify-center py-2.5 px-6 border border-transparent shadow-sm text-sm font-bold rounded-lg text-white bg-gray-900 hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all">
                            <i class="fa fa-save mr-2 mt-0.5"></i> UPDATE
                        </button>
                        <button type="reset" class="inline-flex justify-center py-2.5 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all">
                            <i class="fa fa-rotate-left mr-2 mt-0.5"></i> RESET
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</body>
</html>
