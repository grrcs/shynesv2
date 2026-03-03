@extends('layouts.app')

@section('title', 'Tambah Opsi Pembayaran - Shyness OS')

@section('content')
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-secondary dark:text-gray-400 mb-2">
            <a href="{{ route('admin.payment-options.index') }}" class="hover:text-primary dark:hover:text-white">Opsi Pembayaran</a>
            <i class="fa fa-chevron-right text-xs"></i>
            <span>Tambah Opsi</span>
        </div>
        <h2 class="text-2xl font-bold text-primary dark:text-white">Tambah Opsi Pembayaran Baru</h2>
        <p class="text-sm text-secondary dark:text-gray-400">Tambahkan metode pembayaran baru untuk pelanggan.</p>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-primary rounded-xl shadow-sm border border-thin dark:border-gray-800 transition-colors overflow-hidden">
        <form action="{{ route('admin.payment-options.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Opsi -->
                <div>
                    <label for="name" class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">
                        Nama Opsi Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}"
                        required
                        class="w-full px-4 py-3 bg-transparent border border-thin dark:border-gray-800 text-primary dark:text-white rounded-lg outline-none transition-colors focus:ring-2 focus:ring-primary dark:focus:ring-white @error('name') border-red-500 @enderror"
                        placeholder="Contoh: Transfer Bank, Kartu Kredit, E-Wallet"
                    >
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Persentase Pajak -->
                <div>
                    <label for="tax_percentage" class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">
                        Persentase Pajak (%) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        id="tax_percentage" 
                        name="tax_percentage" 
                        value="{{ old('tax_percentage', 0) }}"
                        step="0.01"
                        min="0"
                        max="100"
                        required
                        class="w-full px-4 py-3 bg-transparent border border-thin dark:border-gray-800 text-primary dark:text-white rounded-lg outline-none transition-colors focus:ring-2 focus:ring-primary dark:focus:ring-white @error('tax_percentage') border-red-500 @enderror"
                        placeholder="0.00"
                    >
                    <p class="text-xs text-secondary dark:text-gray-400 mt-1">Masukkan 0 jika tidak ada pajak untuk opsi ini.</p>
                    @error('tax_percentage')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="mt-6">
                <label for="description" class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">
                    Deskripsi
                </label>
                <textarea 
                    id="description" 
                    name="description" 
                    rows="4"
                    class="w-full px-4 py-3 bg-transparent border border-thin dark:border-gray-800 text-primary dark:text-white rounded-lg outline-none transition-colors focus:ring-2 focus:ring-primary dark:focus:ring-white resize-y @error('description') border-red-500 @enderror"
                    placeholder="Jelaskan detail opsi pembayaran ini..."
                >{{ old('description') }}</textarea>
                <p class="text-xs text-secondary dark:text-gray-400 mt-1">Beri penjelasan singkat tentang cara pembayaran ini bekerja.</p>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Aktif -->
            <div class="mt-6">
                <label class="flex items-center cursor-pointer">
                    <input 
                        type="checkbox" 
                        id="is_active" 
                        name="is_active" 
                        value="1"
                        {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-4 h-4 text-primary border border-thin dark:border-gray-800 rounded focus:ring-2 focus:ring-primary dark:focus:ring-white"
                    >
                    <span class="ml-3 text-sm font-bold text-secondary dark:text-gray-300">
                        Aktifkan opsi pembayaran ini
                    </span>
                </label>
                <p class="text-xs text-secondary dark:text-gray-400 mt-1 ml-7">Opsi yang tidak aktif tidak akan ditampilkan kepada pelanggan.</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-thin dark:border-gray-800">
                <a href="{{ route('admin.payment-options.index') }}" class="px-5 py-2.5 bg-gray-100 dark:bg-[#151515] hover:bg-gray-200 dark:hover:bg-gray-800 text-secondary dark:text-gray-300 font-bold rounded-lg transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-primary text-white dark:bg-white dark:text-primary font-bold rounded-lg hover:bg-black dark:hover:bg-gray-200 transition-colors">
                    <i class="fa fa-save mr-2"></i> Simpan Opsi Pembayaran
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    // Format tax percentage input
    document.getElementById('tax_percentage').addEventListener('input', function(e) {
        if (this.value < 0) this.value = 0;
        if (this.value > 100) this.value = 100;
    });
</script>
@endpush
