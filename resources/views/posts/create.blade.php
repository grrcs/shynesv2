<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Post Baru - Shyness</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style> body { font-family: 'Inter', sans-serif; background-color: #f9fafb; } .cke_chrome { border-color: #d1d5db !important; border-radius: 0.5rem !important; box-shadow: none !important; } </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <nav class="bg-white border-b border-gray-200 px-4 py-3 shadow-sm sticky top-0 z-20">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <a href="{{ route('posts.index') }}" class="flex items-center gap-2 hover:opacity-80 transition group">
                    <img src="{{ asset('storage/images/shyness.png') }}" class="h-10 w-auto object-contain" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=S&background=000&color=fff';">
                    <h1 class="font-bold text-xl text-gray-900 tracking-tight">SHYNESS</h1>
                </a>
            </div>
        </div>
    </nav>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">Tambah Post Baru</h2>
                <p class="mt-1 text-sm text-gray-500">Silakan isi form di bawah ini.</p>
            </div>
            <a href="{{ route('posts.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:text-gray-900 transition-all">
                <i class="fa fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
        <div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Grid Layout -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Gambar -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Gambar</label>
                            <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-600 transition-colors bg-gray-50">
                                <input type="file" name="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-200 file:text-gray-800 hover:file:bg-gray-300 cursor-pointer focus:outline-none @error('image') border-red-500 @enderror">
                            </div>
                            @error('image') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Kanan: Status & Kategori -->
                        <div class="space-y-6">
                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status Publikasi</label>
                                <div class="relative">
                                    <select name="status" class="appearance-none w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-900 focus:ring-2 focus:ring-gray-500 outline-none transition-all cursor-pointer">
                                        <option value="publish" {{ old('status') == 'publish' ? 'selected' : '' }}>Publish</option>
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500"><i class="fa fa-chevron-down text-xs"></i></div>
                                </div>
                            </div>

                            <!-- KATEGORI (BARU) -->
                            <div>
                                <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">Kategori Produk</label>
                                <div class="relative">
                                    <select name="category_id" class="appearance-none w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-900 focus:ring-2 focus:ring-gray-500 outline-none transition-all cursor-pointer @error('category_id') border-red-500 @enderror">
                                        <option value="" disabled selected>-- Pilih Kategori --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500"><i class="fa fa-chevron-down text-xs"></i></div>
                                </div>
                                @error('category_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Judul -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Judul Post</label>
                        <input type="text" name="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 outline-none transition-all @error('title') border-red-500 @enderror" placeholder="Judul..." value="{{ old('title') }}">
                        @error('title') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Konten -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Konten</label>
                        <textarea name="content" id="content" rows="5" class="w-full rounded-lg border-gray-300">{{ old('content') }}</textarea>
                        @error('content') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tombol -->
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <button type="submit" class="inline-flex justify-center py-2 px-6 rounded-lg text-white bg-gray-900 hover:bg-black transition-all">SIMPAN</button>
                        <button type="reset" class="inline-flex justify-center py-2 px-6 rounded-lg text-gray-800 bg-gray-200 hover:bg-gray-300 transition-all">RESET</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
    <script> CKEDITOR.replace( 'content' ); @if ($errors->any()) toastr.error('Periksa inputan Anda.', 'GAGAL!'); @endif </script>
</body>
</html>
