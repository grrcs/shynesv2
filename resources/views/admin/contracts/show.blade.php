@extends('layouts.app')

@section('title', 'Detail Kontrak ' . $contract->contract_code . ' - Shyness OS')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.contracts.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Daftar Kontrak</a>
    </div>

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-primary border border-thin dark:border-gray-800 rounded-xl p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-primary dark:text-white">Kontrak {{ $contract->contract_code }}</h1>
                <p class="text-sm text-secondary dark:text-gray-400">Detail kontrak distributor</p>
            </div>
            <div>
                @if($contract->status === 'active')
                    <span class="px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Active</span>
                @elseif($contract->status === 'expired')
                    <span class="px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">Expired</span>
                @else
                    <span class="px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Terminated</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Supplier Info -->
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <h3 class="text-sm font-bold text-secondary dark:text-gray-400 uppercase mb-3">Supplier</h3>
                <p class="text-lg font-semibold text-primary dark:text-white">{{ $contract->supplier->company_name ?? '-' }}</p>
                <p class="text-sm text-secondary dark:text-gray-400 mt-1">{{ $contract->supplier->contact_person ?? '-' }}</p>
                <p class="text-sm text-secondary dark:text-gray-400">{{ $contract->supplier->email ?? '-' }}</p>
                <p class="text-sm text-secondary dark:text-gray-400">{{ $contract->supplier->phone ?? '-' }}</p>
            </div>

            <!-- Distributor Info -->
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <h3 class="text-sm font-bold text-secondary dark:text-gray-400 uppercase mb-3">Distributor</h3>
                <p class="text-lg font-semibold text-primary dark:text-white">{{ $contract->distributor_company }}</p>
                <p class="text-sm text-secondary dark:text-gray-400 mt-1">{{ $contract->distributor_contact }}</p>
            </div>
        </div>

        <!-- Contract Details -->
        <div class="mt-6 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
            <h3 class="text-sm font-bold text-secondary dark:text-gray-400 uppercase mb-3">Detail Kontrak</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-xs text-secondary dark:text-gray-500">Kode Kontrak</p>
                    <p class="font-mono font-bold text-primary dark:text-white">{{ $contract->contract_code }}</p>
                </div>
                <div>
                    <p class="text-xs text-secondary dark:text-gray-500">Nilai Kontrak</p>
                    <p class="font-bold text-primary dark:text-white">Rp{{ number_format($contract->contract_value, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-secondary dark:text-gray-500">Tanggal Mulai</p>
                    <p class="text-primary dark:text-gray-300">{{ \Carbon\Carbon::parse($contract->contract_start_date)->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-secondary dark:text-gray-500">Tanggal Berakhir</p>
                    <p class="text-primary dark:text-gray-300">{{ \Carbon\Carbon::parse($contract->contract_end_date)->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Security Info -->
        <div class="mt-6 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
            <h3 class="text-sm font-bold text-secondary dark:text-gray-400 uppercase mb-3">Informasi Keamanan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-secondary dark:text-gray-500">Tenant ID</p>
                    <p class="font-mono text-xs text-primary dark:text-gray-300 break-all">{{ $contract->tenant_id }}</p>
                </div>
                <div>
                    <p class="text-xs text-secondary dark:text-gray-500">Encryption Key Hash</p>
                    <p class="font-mono text-xs text-primary dark:text-gray-300 break-all">{{ $contract->encryption_key_hash }}</p>
                </div>
            </div>
        </div>

        <!-- Download -->
        <div class="mt-6 flex gap-3">
            <a href="{{ route('admin.contracts.download', $contract) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700">
                Download File Kontrak
            </a>
        </div>
    </div>
</div>
@endsection
