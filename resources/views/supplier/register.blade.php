@extends('layouts.app')

@section('title', 'Daftar Jadi Supplier - SHYNESS')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-primary dark:text-white">Daftar Jadi Supplier</h1>
        <p class="text-sm text-secondary dark:text-gray-400">Isi data perusahaan Anda untuk menjadi mitra supplier.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-green-700 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    @if(isset($existing) && $existing)
        <div class="bg-white dark:bg-primary border border-thin dark:border-gray-800 rounded-xl p-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                    <i class="fa-solid fa-building text-lg text-secondary"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-primary dark:text-white">{{ $existing->company_name }}</h3>
                    <p class="text-xs text-secondary dark:text-gray-400">
                        Status:
                        @if($existing->status === 'pending')
                            <span class="text-yellow-600 font-semibold">Menunggu Persetujuan</span>
                        @elseif($existing->status === 'active')
                            <span class="text-green-600 font-semibold">Disetujui</span>
                        @else
                            <span class="text-red-600 font-semibold">Ditolak</span>
                        @endif
                    </p>
                </div>
            </div>

            @if($existing->status === 'active')
                <a href="{{ route('admin.contracts.index') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700">
                    Lihat Kontrak Saya
                </a>
            @endif
        </div>
    @else
        <div class="bg-white dark:bg-primary border border-thin dark:border-gray-800 rounded-xl p-6">
            <form action="{{ route('supplier.register.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-primary dark:text-gray-300 mb-1">Nama Perusahaan</label>
                    <input type="text" name="company_name" class="w-full border rounded-lg px-3 py-2 dark:bg-[#1a1a1a] dark:border-gray-700 dark:text-white" required>
                    @error('company_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-primary dark:text-gray-300 mb-1">Nama Kontak Person</label>
                    <input type="text" name="contact_person" class="w-full border rounded-lg px-3 py-2 dark:bg-[#1a1a1a] dark:border-gray-700 dark:text-white" required>
                    @error('contact_person') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-primary dark:text-gray-300 mb-1">Email Perusahaan</label>
                    <input type="email" name="email" class="w-full border rounded-lg px-3 py-2 dark:bg-[#1a1a1a] dark:border-gray-700 dark:text-white" required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-primary dark:text-gray-300 mb-1">Nomor Telepon</label>
                    <input type="text" name="phone" class="w-full border rounded-lg px-3 py-2 dark:bg-[#1a1a1a] dark:border-gray-700 dark:text-white" required>
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-primary dark:text-gray-300 mb-1">Alamat Perusahaan</label>
                    <textarea name="address" rows="3" class="w-full border rounded-lg px-3 py-2 dark:bg-[#1a1a1a] dark:border-gray-700 dark:text-white" required></textarea>
                    @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700">
                    Daftar Sebagai Supplier
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
