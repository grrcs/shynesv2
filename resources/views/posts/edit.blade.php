<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post - Shyness</title>

    <!-- Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f9fafb; }
        .cke_chrome { border-color: #d1d5db !important; border-radius: 0.5rem !important; box-shadow: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 px-4 py-3 shadow-sm sticky top-0 z-20">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <a href="{{ route('posts.index') }}" class="flex items-center gap-2 hover:opacity-80 transition group">
                    <img src="{{ asset('storage/images/shyness.png') }}" alt="Logo" class="h-9 w-auto object-contain"
                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=S&background=000&color=fff';">
                    <h1 class="font-bold text-xl text-gray-900 tracking-tight">SHYNESS</h1>
                </a>
            </div>

            <!-- MENU NAVIGASI -->
            <div class="flex items-center gap-6">
                <a href="{{ route('posts.index') }}" class="text-sm font-bold text-gray-900 border-b-2 border-gray-900 pb-0.5">
                    Postingan
                </a>
                <a href="{{ route('categories.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors pb-0.5 hover:border-b-2 hover:border-gray-300">
                    Kategori
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
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">Edit Post</h2>
                <p class="mt-1 text-sm text-gray-500">Perbarui konten, kategori, dan status publikasi.</p>
            </div>
            <a href="{{ route('posts.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:text-gray-900 transition-all">
                <i class="fa fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Grid Layout untuk Gambar & Status -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <!-- Kolom Kiri: Preview Gambar -->
                        <div class="col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar Saat Ini</label>
                            <div class="relative rounded-lg overflow-hidden border border-gray-200 shadow-sm group">
                                <img src="{{ asset('storage/posts/'.$post->image) }}" alt="Current Image" class="w-full h-40 object-cover transition-transform duration-300 group-hover:scale-105">
                            </div>
                        </div>

                        <!-- Kolom Kanan: Upload, Status, & Kategori -->
                        <div class="col-span-1 md:col-span-2 space-y-6">

                            <!-- 1. Input Status -->
                            <div>
                                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status Publikasi</label>
                                <div class="relative">
                                    <select name="status" id="status" class="appearance-none w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-900 focus:ring-2 focus:ring-gray-500 focus:border-gray-500 outline-none transition-all cursor-pointer">
                                        <option value="publish" {{ old('status', $post->status) == 'publish' ? 'selected' : '' }}>Publish (Tayangkan)</option>
                                        <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft (Simpan Konsep)</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                        <i class="fa fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Input Kategori (BARU DITAMBAHKAN) -->
                            <div>
                                <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">Kategori Produk</label>
                                <div class="relative">
                                    <select name="category_id" class="appearance-none w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-900 focus:ring-2 focus:ring-gray-500 outline-none transition-all cursor-pointer @error('category_id') border-red-500 @enderror">
                                        <option value="" disabled>-- Pilih Kategori --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                        <i class="fa fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                                @error('category_id')
                                    <p class="mt-2 text-sm text-red-600 flex items-center"><i class="fa fa-circle-exclamation mr-1"></i> {{ $message }}</p>
                                @enderror
                            </div>

                            <!-- 3. Input File -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Ganti Gambar (Opsional)</label>
                                <input type="file" name="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-200 file:text-gray-800 hover:file:bg-gray-300 cursor-pointer focus:outline-none">
                                @error('image')
                                    <p class="mt-2 text-sm text-red-600 flex items-center"><i class="fa fa-circle-exclamation mr-1"></i> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Input Judul -->
                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-1">Judul Post</label>
                        <input type="text" name="title" id="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 outline-none transition-all @error('title') border-red-500 @enderror" value="{{ old('title', $post->title) }}">
                        @error('title')
                            <p class="mt-2 text-sm text-red-600 flex items-center"><i class="fa fa-circle-exclamation mr-1"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Input Konten -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Konten</label>
                        <textarea name="content" id="content" rows="5" class="w-full rounded-lg border-gray-300">{{ old('content', $post->content) }}</textarea>
                        @error('content')
                            <p class="mt-2 text-sm text-red-600 flex items-center"><i class="fa fa-circle-exclamation mr-1"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-gray-900 hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all">
                            <i class="fa fa-save mr-2 mt-0.5"></i> UPDATE POST
                        </button>
                        <button type="reset" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-gray-800 bg-gray-200 hover:bg-gray-300 transition-all">
                            <i class="fa fa-rotate-left mr-2 mt-0.5"></i> RESET
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
    <script> CKEDITOR.replace('content'); </script>
</body>
</html>
