@extends('layouts.app')

@section('title', 'Tambah Banner - Shyness OS')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-primary dark:text-white">Tambah Banner Baru</h2>
            <p class="text-sm text-secondary dark:text-gray-400">Atur info/promo yang akan tampil.</p>
        </div>
        <a href="{{ route('banners.index') }}" class="text-sm text-secondary hover:text-black dark:text-gray-400 dark:hover:text-white font-medium transition-colors">
            <i class="fa fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-primary border-thin dark:border-gray-800 transition-colors p-8 rounded-xl shadow-sm">
        <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">Gambar Banner <span class="text-red-500">*</span></label>
                <input type="file" name="image" accept="image/*" required class="block w-full text-sm text-secondary dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-800 dark:file:bg-gray-800 dark:file:text-white cursor-pointer focus:outline-none">
                <p class="text-xs text-secondary dark:text-gray-500 mt-1">Rasio disarankan landscape (e.g. 16:9 atau 21:9).</p>
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">Judul (Opsional)</label>
                <input type="text" name="title" class="w-full px-4 py-3 bg-transparent dark:bg-primary border border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors focus:ring-2 focus:ring-gray-500" placeholder="Misal: Promo Akhir Tahun" value="{{ old('title') }}">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">Link Tujuan (Opsional)</label>
                <input type="url" name="link" class="w-full px-4 py-3 bg-transparent dark:bg-primary border border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors focus:ring-2 focus:ring-gray-500" placeholder="https://..." value="{{ old('link') }}">
                <p class="text-xs text-secondary dark:text-gray-500 mt-1">Link produk atau halaman tertentu ketika banner diklik.</p>
                @error('link') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 rounded border-gray-300 text-black focus:ring-black">
                    <span class="text-sm font-bold text-secondary dark:text-gray-300">Aktifkan Banner Ini</span>
                </label>
            </div>

            <div class="pt-4 border-t border-gray-100 dark:border-gray-800">
                <button type="submit" class="w-full py-3 bg-primary text-white dark:bg-white dark:text-primary font-bold rounded-lg hover:bg-black dark:hover:bg-gray-200 transition-all">SIMPAN BANNER</button>
            </div>
        </form>
    </div>
</div>
@endsection
