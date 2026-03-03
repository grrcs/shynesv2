@extends('layouts.app')

@section('title', 'Alamat Saya - Shyness')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-thin pb-6 border-gray-200 dark:border-gray-800 transition-colors">
        <div>
            <h1 class="text-3xl font-serif font-medium text-primary dark:text-white mb-2 transition-colors">Alamat Saya</h1>
            <p class="text-xs tracking-widest uppercase text-secondary">Kelola daftar alamat pengiriman Anda</p>
        </div>
        <a href="{{ route('addresses.create') }}" class="px-5 py-2.5 bg-primary text-white dark:bg-white dark:text-primary hover:bg-black dark:hover:bg-gray-200 text-xs tracking-widest uppercase font-medium rounded-lg transition-colors shadow-md text-center max-w-max">
            <i class="fa fa-plus mr-2"></i> Tambah Alamat
        </a>
    </div>

    @if($addresses->isEmpty())
        <div class="bg-gray-50 dark:bg-[#151515] border border-thin border-gray-200 dark:border-gray-800 rounded-xl p-8 text-center transition-colors">
            <i class="fa-regular fa-map text-4xl text-gray-300 dark:text-gray-600 mb-4 transition-colors"></i>
            <h3 class="text-lg font-medium text-primary dark:text-white mb-2 font-serif transition-colors">Belum Ada Alamat</h3>
            <p class="text-sm text-secondary dark:text-gray-400 max-w-md mx-auto transition-colors mt-2 mb-6">Anda belum menambahkan alamat pengiriman apapun. Tambahkan alamat sekarang untuk mempermudah proses checkout Anda.</p>
            <a href="{{ route('addresses.create') }}" class="inline-block px-5 py-2.5 bg-primary text-white dark:bg-white dark:text-primary hover:bg-black dark:hover:bg-gray-200 text-xs tracking-widest uppercase font-medium rounded-lg transition-colors shadow-md">
                Tambah Alamat Baru
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($addresses as $address)
                <div class="relative bg-white dark:bg-primary border {{ $address->is_primary ? 'border-primary dark:border-white ring-1 ring-primary dark:ring-white' : 'border-gray-200 dark:border-gray-800' }} rounded-xl p-6 transition-all shadow-sm">
                    @if($address->is_primary)
                        <div class="absolute -top-3 left-6">
                            <span class="bg-primary text-white dark:bg-white dark:text-primary text-[10px] tracking-widest uppercase px-3 py-1 rounded-full font-bold shadow-md">Utama</span>
                        </div>
                    @endif
                    
                    <div class="flex justify-between items-start mb-4 mt-2">
                        <h3 class="font-bold text-lg text-primary dark:text-white capitalize"><i class="fa-solid fa-location-dot mr-2 text-secondary"></i>{{ $address->label }}</h3>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('addresses.edit', $address->id) }}" class="text-secondary hover:text-black dark:hover:text-white transition-colors" title="Edit">
                                <i class="fa fa-pen"></i>
                            </a>
                            <form action="{{ route('addresses.destroy', $address->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 transition-colors" title="Hapus">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="space-y-2 text-sm text-secondary dark:text-gray-400">
                        <p class="font-bold text-primary dark:text-white">{{ $address->recipient_name }}</p>
                        <p>{{ $address->phone_number }}</p>
                        <p class="line-clamp-2 mt-2" title="{{ $address->full_address }}">{{ $address->full_address }}</p>
                        <p>{{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
