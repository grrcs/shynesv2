@extends('layouts.app')

@section('title', 'Buat Kontrak Baru - Shyness OS')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.contracts.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali</a>
    </div>

    <div class="bg-white dark:bg-primary border border-thin dark:border-gray-800 rounded-xl p-6">
        <h1 class="text-2xl font-bold text-primary dark:text-white mb-6">Buat Kontrak Baru</h1>

        <form action="{{ route('admin.contracts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold text-primary dark:text-gray-300 mb-1">Supplier</label>
                <select name="supplier_id" class="w-full border rounded-lg px-3 py-2 dark:bg-[#1a1a1a] dark:border-gray-700 dark:text-white" required>
                    <option value="">Pilih Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->company_name }}</option>
                    @endforeach
                </select>
                @error('supplier_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-primary dark:text-gray-300 mb-1">Perusahaan Distributor</label>
                <input type="text" name="distributor_company" class="w-full border rounded-lg px-3 py-2 dark:bg-[#1a1a1a] dark:border-gray-700 dark:text-white" required>
                @error('distributor_company') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-primary dark:text-gray-300 mb-1">Kontak Distributor</label>
                <input type="text" name="distributor_contact" class="w-full border rounded-lg px-3 py-2 dark:bg-[#1a1a1a] dark:border-gray-700 dark:text-white" required>
                @error('distributor_contact') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-primary dark:text-gray-300 mb-1">Tanggal Mulai</label>
                    <input type="date" name="contract_start_date" class="w-full border rounded-lg px-3 py-2 dark:bg-[#1a1a1a] dark:border-gray-700 dark:text-white" required>
                    @error('contract_start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-primary dark:text-gray-300 mb-1">Tanggal Berakhir</label>
                    <input type="date" name="contract_end_date" class="w-full border rounded-lg px-3 py-2 dark:bg-[#1a1a1a] dark:border-gray-700 dark:text-white" required>
                    @error('contract_end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-primary dark:text-gray-300 mb-1">Nilai Kontrak (Rp)</label>
                <input type="number" name="contract_value" class="w-full border rounded-lg px-3 py-2 dark:bg-[#1a1a1a] dark:border-gray-700 dark:text-white" required>
                @error('contract_value') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-primary dark:text-gray-300 mb-1">File Kontrak (PDF/DOC)</label>
                <input type="file" name="file" accept=".pdf,.doc,.docx" class="w-full border rounded-lg px-3 py-2 dark:bg-[#1a1a1a] dark:border-gray-700 dark:text-white" required>
                @error('file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700">
                Simpan Kontrak
            </button>
        </form>
    </div>
</div>
@endsection
