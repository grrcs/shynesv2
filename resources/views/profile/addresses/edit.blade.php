@extends('layouts.app')

@section('title', 'Edit Alamat - Shyness')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-10 border-b border-thin pb-6 border-gray-200 dark:border-gray-800 transition-colors">
        <div class="flex items-center gap-4 mb-2">
            <a href="{{ route('addresses.index') }}" class="text-secondary hover:text-primary dark:hover:text-white transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-3xl font-serif font-medium text-primary dark:text-white transition-colors">Edit Alamat</h1>
        </div>
        <p class="text-xs tracking-widest uppercase text-secondary ml-8">Perbarui informasi alamat pengiriman Anda</p>
    </div>

    <form action="{{ route('addresses.update', $address->id) }}" method="POST" class="bg-white dark:bg-primary border border-thin border-gray-200 dark:border-gray-800 rounded-xl p-8 shadow-sm transition-colors">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="col-span-1 md:col-span-2">
                <label for="label" class="block text-xs tracking-widest uppercase text-secondary mb-2">Label Alamat (Contoh: Rumah, Kantor)</label>
                <input type="text" name="label" id="label" value="{{ old('label', $address->label) }}" required class="w-full bg-gray-50 dark:bg-[#151515] border border-gray-200 dark:border-gray-800 text-primary dark:text-white text-sm rounded-lg focus:ring-black focus:border-black dark:focus:ring-white dark:focus:border-white block p-3 transition-colors" placeholder="Rumah / Kantor / Apartemen">
                @error('label') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="recipient_name" class="block text-xs tracking-widest uppercase text-secondary mb-2">Nama Penerima</label>
                <input type="text" name="recipient_name" id="recipient_name" value="{{ old('recipient_name', $address->recipient_name) }}" required class="w-full bg-gray-50 dark:bg-[#151515] border border-gray-200 dark:border-gray-800 text-primary dark:text-white text-sm rounded-lg focus:ring-black focus:border-black dark:focus:ring-white dark:focus:border-white block p-3 transition-colors" placeholder="Nama Lengkap">
                @error('recipient_name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="phone_number" class="block text-xs tracking-widest uppercase text-secondary mb-2">Nomor Telepon</label>
                <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $address->phone_number) }}" required class="w-full bg-gray-50 dark:bg-[#151515] border border-gray-200 dark:border-gray-800 text-primary dark:text-white text-sm rounded-lg focus:ring-black focus:border-black dark:focus:ring-white dark:focus:border-white block p-3 transition-colors" placeholder="08xxxxxxxxxx">
                @error('phone_number') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="col-span-1 md:col-span-2">
                <label for="full_address" class="block text-xs tracking-widest uppercase text-secondary mb-2">Alamat Lengkap</label>
                <textarea name="full_address" id="full_address" rows="3" required class="w-full bg-gray-50 dark:bg-[#151515] border border-gray-200 dark:border-gray-800 text-primary dark:text-white text-sm rounded-lg focus:ring-black focus:border-black dark:focus:ring-white dark:focus:border-white block p-3 transition-colors" placeholder="Nama Jalan, Gedung, No. Rumah, RT/RW, Kecamatan, Kelurahan">{{ old('full_address', $address->full_address) }}</textarea>
                @error('full_address') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="city" class="block text-xs tracking-widest uppercase text-secondary mb-2">Kota / Kabupaten</label>
                <input type="text" name="city" id="city" value="{{ old('city', $address->city) }}" required class="w-full bg-gray-50 dark:bg-[#151515] border border-gray-200 dark:border-gray-800 text-primary dark:text-white text-sm rounded-lg focus:ring-black focus:border-black dark:focus:ring-white dark:focus:border-white block p-3 transition-colors" placeholder="Kota / Kabupaten">
                @error('city') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="province" class="block text-xs tracking-widest uppercase text-secondary mb-2">Provinsi</label>
                <input type="text" name="province" id="province" value="{{ old('province', $address->province) }}" required class="w-full bg-gray-50 dark:bg-[#151515] border border-gray-200 dark:border-gray-800 text-primary dark:text-white text-sm rounded-lg focus:ring-black focus:border-black dark:focus:ring-white dark:focus:border-white block p-3 transition-colors" placeholder="Provinsi">
                @error('province') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="postal_code" class="block text-xs tracking-widest uppercase text-secondary mb-2">Kode Pos</label>
                <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $address->postal_code) }}" required class="w-full bg-gray-50 dark:bg-[#151515] border border-gray-200 dark:border-gray-800 text-primary dark:text-white text-sm rounded-lg focus:ring-black focus:border-black dark:focus:ring-white dark:focus:border-white block p-3 transition-colors" placeholder="Kode Pos">
                @error('postal_code') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mb-8">
            <label class="flex items-center space-x-3 cursor-pointer group {{ $address->is_primary ? 'opacity-70 pointer-events-none' : '' }}">
                <input type="checkbox" name="is_primary" value="1" {{ old('is_primary', $address->is_primary) ? 'checked' : '' }} {{ $address->is_primary ? 'readonly onclick="return false;"' : '' }} class="w-5 h-5 rounded border-gray-300 text-black focus:ring-black dark:border-gray-700 dark:bg-[#151515] dark:checked:bg-white dark:focus:ring-white transition-all cursor-pointer">
                <div>
                    <p class="text-sm font-bold text-primary dark:text-white">Jadikan Alamat Utama</p>
                    <p class="text-xs text-secondary dark:text-gray-400">Alamat ini akan dipilih secara otomatis saat checkout</p>
                </div>
            </label>
        </div>

        <div class="flex justify-end gap-4 pt-6 border-t border-thin border-gray-200 dark:border-gray-800">
            <a href="{{ route('addresses.index') }}" class="px-6 py-2.5 bg-gray-100 dark:bg-[#151515] hover:bg-gray-200 dark:hover:bg-gray-800 text-secondary dark:text-gray-300 text-xs tracking-widest uppercase font-bold rounded-lg transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-primary text-white dark:bg-white dark:text-primary hover:bg-black dark:hover:bg-gray-200 text-xs tracking-widest uppercase font-bold rounded-lg transition-colors shadow-md">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
