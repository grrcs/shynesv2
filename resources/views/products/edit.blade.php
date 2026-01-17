<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Shyness OS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { font-family: 'Inter', sans-serif; background-color: #f9fafb; } .cke_chrome { border-color: #d1d5db !important; border-radius: 0.5rem !important; box-shadow: none !important; }</style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 px-4 py-3 shadow-sm sticky top-0 z-30">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <a href="{{ route('posts.index') }}" class="flex items-center gap-2 hover:opacity-80 transition group">
                    <img src="{{ asset('storage/images/shyness.png') }}" class="h-9 w-auto" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=S&background=000&color=fff';">
                    <h1 class="font-bold text-xl text-gray-900 tracking-tight">SHYNESS</h1>
                </a>
            </div>
            <div class="flex items-center gap-6">
                <a href="{{ route('posts.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">Postingan</a>
                <a href="{{ route('products.index') }}" class="text-sm font-bold text-gray-900 border-b-2 border-gray-900 pb-0.5">Produk</a>
                <a href="{{ route('categories.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">Kategori</a>
                <div class="flex items-center gap-2 ml-2 pl-6 border-l border-gray-200">
                    <div class="hidden md:block text-xs text-right"><div class="font-bold text-gray-700">Admin</div><div class="text-gray-400">Panel</div></div>
                    <img src="https://ui-avatars.com/api/?name=Admin&background=1f2937&color=fff" class="w-8 h-8 rounded-full border border-gray-300">
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Edit Produk</h2>
                <p class="text-sm text-gray-500">Perbarui informasi, harga, atau stok barang.</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-sm text-gray-600 hover:text-black font-medium"><i class="fa fa-arrow-left mr-1"></i> Kembali</a>
        </div>

        <div class="bg-white rounded-xl shadow border border-gray-200 p-8">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Grid Gambar & Info Dasar -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <!-- Kiri: Gambar -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Gambar Produk</label>

                            <!-- Preview Gambar Lama -->
                            <div class="mb-3 relative rounded-lg overflow-hidden border border-gray-200 w-full h-64">
                                <img src="{{ asset('storage/products/'.$product->image) }}" class="w-full h-full object-cover" alt="Preview">
                            </div>

                            <input type="file" name="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                            <p class="text-xs text-gray-400 mt-1">*Kosongkan jika tidak ingin mengubah gambar.</p>
                            @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Kanan: Form Data -->
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Produk</label>
                            <input type="text" name="title" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-black outline-none" placeholder="Nama Produk" value="{{ old('title', $product->title) }}">
                            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                            <select name="category_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-black outline-none bg-white">
                                <option value="" disabled>-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Harga (Rp)</label>
                                <input type="number" name="price" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-black outline-none" placeholder="0" value="{{ old('price', $product->price) }}">
                                @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Stok</label>
                                <input type="number" name="stock" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-black outline-none" placeholder="0" value="{{ old('stock', $product->stock) }}">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Link E-Commerce (Opsional)</label>
                            <input type="text" name="link_shopee" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-black outline-none" placeholder="https://..." value="{{ old('link_shopee', $product->link_shopee) }}">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-black outline-none bg-white">
                                <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active (Dijual)</option>
                                <option value="sold_out" {{ old('status', $product->status) == 'sold_out' ? 'selected' : '' }}>Sold Out (Habis)</option>
                                <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive (Disembunyikan)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Produk</label>
                    <textarea name="description" id="content" rows="4" class="w-full border rounded-lg">{{ old('description', $product->description) }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 border-t border-gray-100 flex gap-3">
                    <button type="submit" class="w-full py-3 bg-gray-900 text-white font-bold rounded-lg hover:bg-black transition-all shadow-md">
                        <i class="fa fa-save mr-2"></i> UPDATE PRODUK
                    </button>
                    <a href="{{ route('products.index') }}" class="w-full py-3 bg-gray-100 text-gray-700 font-bold rounded-lg hover:bg-gray-200 transition-all text-center">
                        BATAL
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
    <script> CKEDITOR.replace( 'content' ); </script>
</body>
</html>
