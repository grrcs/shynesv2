@extends('layouts.app')

@section('title', 'Edit Opsi Pembayaran - Shyness OS')

@section('content')
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-secondary dark:text-gray-400 mb-2">
            <a href="{{ route('admin.payment-options.index') }}" class="hover:text-primary dark:hover:text-white">Opsi Pembayaran</a>
            <i class="fa fa-chevron-right text-xs"></i>
            <span>Edit Opsi</span>
        </div>
        <h2 class="text-2xl font-bold text-primary dark:text-white">Edit Opsi Pembayaran</h2>
        <p class="text-sm text-secondary dark:text-gray-400">Perbarui informasi metode pembayaran.</p>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-primary rounded-xl shadow-sm border border-thin dark:border-gray-800 transition-colors overflow-hidden">
        <form action="{{ route('admin.payment-options.update', $paymentOption->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
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
                        value="{{ old('name', $paymentOption->name) }}"
                        required
                        class="w-full px-4 py-3 bg-transparent border border-thin dark:border-gray-800 text-primary dark:text-white rounded-lg outline-none transition-colors focus:ring-2 focus:ring-primary dark:focus:ring-white @error('name') border-red-500 @enderror"
                        placeholder="Contoh: Transfer Bank, Kartu Kredit, E-Wallet"
                    >
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kode Channel -->
                <div>
                    <label for="code" class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">
                        Kode Channel Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="code" 
                        name="code" 
                        required
                        class="w-full px-4 py-3 bg-transparent border border-thin dark:border-gray-800 text-primary dark:text-white rounded-lg outline-none transition-colors focus:ring-2 focus:ring-primary dark:focus:ring-white @error('code') border-red-500 @enderror"
                    >
                        <option value="">-- Pilih Channel --</option>
                        <optgroup label="Wijaya Pay Gateway">
                            <option value="QRIS" {{ old('code', $paymentOption->code) == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                            <option value="BRIVA" {{ old('code', $paymentOption->code) == 'BRIVA' ? 'selected' : '' }}>BRI Virtual Account</option>
                            <option value="BCAVA" {{ old('code', $paymentOption->code) == 'BCAVA' ? 'selected' : '' }}>BCA Virtual Account</option>
                            <option value="BNIVA" {{ old('code', $paymentOption->code) == 'BNIVA' ? 'selected' : '' }}>BNI Virtual Account</option>
                            <option value="BSIVA" {{ old('code', $paymentOption->code) == 'BSIVA' ? 'selected' : '' }}>BSI Virtual Account</option>
                            <option value="MANDIRIVA" {{ old('code', $paymentOption->code) == 'MANDIRIVA' ? 'selected' : '' }}>Mandiri Virtual Account</option>
                            <option value="PERMATAVA" {{ old('code', $paymentOption->code) == 'PERMATAVA' ? 'selected' : '' }}>Permata Virtual Account</option>
                            <option value="MAYBANKVA" {{ old('code', $paymentOption->code) == 'MAYBANKVA' ? 'selected' : '' }}>Maybank Virtual Account</option>
                            <option value="MUAMALATVA" {{ old('code', $paymentOption->code) == 'MUAMALATVA' ? 'selected' : '' }}>Muamalat Virtual Account</option>
                            <option value="CIMBVA" {{ old('code', $paymentOption->code) == 'CIMBVA' ? 'selected' : '' }}>CIMB Virtual Account</option>
                            <option value="DANAMONVA" {{ old('code', $paymentOption->code) == 'DANAMONVA' ? 'selected' : '' }}>Danamon Virtual Account</option>
                            <option value="BNCVA" {{ old('code', $paymentOption->code) == 'BNCVA' ? 'selected' : '' }}>BNC Virtual Account</option>
                            <option value="OCBCVA" {{ old('code', $paymentOption->code) == 'OCBCVA' ? 'selected' : '' }}>OCBC Virtual Account</option>
                            <option value="INDOMARET" {{ old('code', $paymentOption->code) == 'INDOMARET' ? 'selected' : '' }}>Indomaret</option>
                            <option value="ALFAMART" {{ old('code', $paymentOption->code) == 'ALFAMART' ? 'selected' : '' }}>Alfamart</option>
                        </optgroup>
                        <optgroup label="Non-Gateway">
                            <option value="cash" {{ old('code', $paymentOption->code) == 'cash' ? 'selected' : '' }}>Tunai (Cash)</option>
                            <option value="bank_transfer" {{ old('code', $paymentOption->code) == 'bank_transfer' ? 'selected' : '' }}>Transfer Bank Manual</option>
                            <option value="cod" {{ old('code', $paymentOption->code) == 'cod' ? 'selected' : '' }}>COD (Cash on Delivery)</option>
                        </optgroup>
                    </select>
                    <p class="text-xs text-secondary dark:text-gray-400 mt-1">Pilih channel yang terhubung ke Wijaya Pay gateway.</p>
                    @error('code')
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
                        value="{{ old('tax_percentage', $paymentOption->tax_percentage) }}"
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
                >{{ old('description', $paymentOption->description) }}</textarea>
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
                        {{ old('is_active', $paymentOption->is_active) ? 'checked' : '' }}
                        class="w-4 h-4 text-primary border border-thin dark:border-gray-800 rounded focus:ring-2 focus:ring-primary dark:focus:ring-white"
                    >
                    <span class="ml-3 text-sm font-bold text-secondary dark:text-gray-300">
                        Aktifkan opsi pembayaran ini
                    </span>
                </label>
                <p class="text-xs text-secondary dark:text-gray-400 mt-1 ml-7">Opsi yang tidak aktif tidak akan ditampilkan kepada pelanggan.</p>
            </div>

            <!-- Informasi Dibuat/Diupdate -->
            <div class="mt-6 p-4 bg-gray-50 dark:bg-[#151515] rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-secondary dark:text-gray-400">
                    <div>
                        <i class="fa fa-calendar-plus mr-2"></i>
                        Dibuat: {{ $paymentOption->created_at->format('d M Y H:i') }}
                    </div>
                    <div>
                        <i class="fa fa-calendar-check mr-2"></i>
                        Diupdate: {{ $paymentOption->updated_at->format('d M Y H:i') }}
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-thin dark:border-gray-800">
                <a href="{{ route('admin.payment-options.index') }}" class="px-5 py-2.5 bg-gray-100 dark:bg-[#151515] hover:bg-gray-200 dark:hover:bg-gray-800 text-secondary dark:text-gray-300 font-bold rounded-lg transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-primary text-white dark:bg-white dark:text-primary font-bold rounded-lg hover:bg-black dark:hover:bg-gray-200 transition-colors">
                    <i class="fa fa-save mr-2"></i> Update Opsi Pembayaran
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
