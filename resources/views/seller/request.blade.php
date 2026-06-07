@extends('layouts.app')

@section('title', 'Ajukan Jadi Penjual - Shyness')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-secondary dark:text-gray-400 mb-2">
            <a href="{{ route('seller.index') }}" class="hover:text-primary dark:hover:text-white">Penjual</a>
            <i class="fa fa-chevron-right text-xs"></i>
            <span>Ajukan</span>
        </div>
        <h1 class="text-2xl font-bold text-primary dark:text-white">Ajukan Jadi Penjual</h1>
        <p class="text-sm text-secondary dark:text-gray-400">Isi formulir di bawah untuk mengajukan diri sebagai penjual di Shyness Store.</p>
    </div>

    <div class="bg-white dark:bg-primary border border-thin dark:border-gray-800 rounded-xl p-6">
        <form action="{{ route('seller.submitRequest') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <!-- Nama Bisnis -->
                <div>
                    <label for="business_name" class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">
                        Nama Bisnis / Brand <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="business_name" 
                        name="business_name" 
                        value="{{ old('business_name') }}"
                        required
                        class="w-full px-4 py-3 bg-transparent border border-thin dark:border-gray-800 text-primary dark:text-white rounded-lg outline-none transition-colors focus:ring-2 focus:ring-primary dark:focus:ring-white @error('business_name') border-red-500 @enderror"
                        placeholder="Nama brand atau bisnis Anda"
                    >
                    @error('business_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi Bisnis -->
                <div>
                    <label for="business_description" class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">
                        Deskripsi Bisnis <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="business_description" 
                        name="business_description" 
                        rows="4"
                        required
                        class="w-full px-4 py-3 bg-transparent border border-thin dark:border-gray-800 text-primary dark:text-white rounded-lg outline-none transition-colors focus:ring-2 focus:ring-primary dark:focus:ring-white resize-y @error('business_description') border-red-500 @enderror"
                        placeholder="Jelaskan bisnis Anda, produk apa yang ingin dijual, dll (min. 20 karakter)"
                    >{{ old('business_description') }}</textarea>
                    @error('business_description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor Telepon -->
                <div>
                    <label for="phone" class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">
                        Nomor Telepon / WhatsApp <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="phone" 
                        name="phone" 
                        value="{{ old('phone') }}"
                        required
                        class="w-full px-4 py-3 bg-transparent border border-thin dark:border-gray-800 text-primary dark:text-white rounded-lg outline-none transition-colors focus:ring-2 focus:ring-primary dark:focus:ring-white @error('phone') border-red-500 @enderror"
                        placeholder="08xxxxxxxxxx"
                    >
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex gap-4">
                <button type="submit" class="px-6 py-3 bg-primary dark:bg-white text-white dark:text-primary text-xs tracking-widest uppercase font-medium hover:bg-black dark:hover:bg-gray-200 transition-colors">
                    Kirim Pengajuan
                </button>
                <a href="{{ route('seller.index') }}" class="px-6 py-3 border border-thin dark:border-gray-800 text-primary dark:text-white text-xs tracking-widest uppercase font-medium hover:bg-gray-50 dark:hover:bg-[#151515] transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
