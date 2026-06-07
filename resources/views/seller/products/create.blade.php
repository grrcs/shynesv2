@extends('layouts.app')

@section('title', 'Ajukan Produk - Shyness')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-secondary dark:text-gray-400 mb-2">
            <a href="{{ route('seller.index') }}" class="hover:text-primary dark:hover:text-white">Penjual</a>
            <i class="fa fa-chevron-right text-xs"></i>
            <span>Ajukan Produk</span>
        </div>
        <h1 class="text-2xl font-bold text-primary dark:text-white">Ajukan Produk Baru</h1>
        <p class="text-sm text-secondary dark:text-gray-400">Produk akan ditinjau admin sebelum ditampilkan di toko.</p>
    </div>

    <div class="bg-white dark:bg-primary border border-thin dark:border-gray-800 rounded-xl p-6">
        <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                <!-- Nama Produk -->
                <div>
                    <label for="title" class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">
                        Nama Produk <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        value="{{ old('title') }}"
                        required
                        class="w-full px-4 py-3 bg-transparent border border-thin dark:border-gray-800 text-primary dark:text-white rounded-lg outline-none transition-colors focus:ring-2 focus:ring-primary dark:focus:ring-white @error('title') border-red-500 @enderror"
                        placeholder="Nama produk Anda"
                    >
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label for="category_id" class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="category_id" 
                        name="category_id" 
                        required
                        class="w-full px-4 py-3 bg-transparent border border-thin dark:border-gray-800 text-primary dark:text-white rounded-lg outline-none transition-colors focus:ring-2 focus:ring-primary dark:focus:ring-white @error('category_id') border-red-500 @enderror"
                    >
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga Dasar -->
                <div>
                    <label for="base_price" class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">
                        Harga Dasar (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        id="base_price" 
                        name="base_price" 
                        value="{{ old('base_price') }}"
                        required
                        min="1000"
                        class="w-full px-4 py-3 bg-transparent border border-thin dark:border-gray-800 text-primary dark:text-white rounded-lg outline-none transition-colors focus:ring-2 focus:ring-primary dark:focus:ring-white @error('base_price') border-red-500 @enderror"
                        placeholder="Harga yang Anda inginkan (min. 1000)"
                    >
                    <p class="text-xs text-secondary dark:text-gray-400 mt-1">Harga jual akhir akan ditentukan admin dengan markup {{ $contract->default_markup_percentage }}%.</p>
                    @error('base_price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Stok -->
                    <div>
                        <label for="stock" class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">
                            Stok <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            id="stock" 
                            name="stock" 
                            value="{{ old('stock') }}"
                            required
                            min="1"
                            class="w-full px-4 py-3 bg-transparent border border-thin dark:border-gray-800 text-primary dark:text-white rounded-lg outline-none transition-colors focus:ring-2 focus:ring-primary dark:focus:ring-white @error('stock') border-red-500 @enderror"
                            placeholder="Jumlah stok"
                        >
                        @error('stock')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Berat -->
                    <div>
                        <label for="weight" class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">
                            Berat (gram)
                        </label>
                        <input 
                            type="number" 
                            id="weight" 
                            name="weight" 
                            value="{{ old('weight') }}"
                            min="1"
                            class="w-full px-4 py-3 bg-transparent border border-thin dark:border-gray-800 text-primary dark:text-white rounded-lg outline-none transition-colors focus:ring-2 focus:ring-primary dark:focus:ring-white @error('weight') border-red-500 @enderror"
                            placeholder="Berat produk"
                        >
                        @error('weight')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="description" class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">
                        Deskripsi Produk <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="4"
                        required
                        class="w-full px-4 py-3 bg-transparent border border-thin dark:border-gray-800 text-primary dark:text-white rounded-lg outline-none transition-colors focus:ring-2 focus:ring-primary dark:focus:ring-white resize-y @error('description') border-red-500 @enderror"
                        placeholder="Jelaskan produk Anda (min. 10 karakter)"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gambar -->
                <div>
                    <label for="image" class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">
                        Foto Produk <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="file" 
                        id="image" 
                        name="image" 
                        required
                        accept="image/jpeg,image/png,image/jpg,image/webp"
                        class="w-full px-4 py-3 bg-transparent border border-thin dark:border-gray-800 text-primary dark:text-white rounded-lg outline-none transition-colors @error('image') border-red-500 @enderror"
                    >
                    <p class="text-xs text-secondary dark:text-gray-400 mt-1">Format: JPG, PNG, WEBP. Maks 2MB.</p>
                    @error('image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex gap-4">
                <button type="submit" class="px-6 py-3 bg-primary dark:bg-white text-white dark:text-primary text-xs tracking-widest uppercase font-medium hover:bg-black dark:hover:bg-gray-200 transition-colors">
                    Ajukan Produk
                </button>
                <a href="{{ route('seller.index') }}" class="px-6 py-3 border border-thin dark:border-gray-800 text-primary dark:text-white text-xs tracking-widest uppercase font-medium hover:bg-gray-50 dark:hover:bg-[#151515] transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
