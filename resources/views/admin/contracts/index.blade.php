@extends('layouts.app')

@section('title', 'Data Kontrak Supplier - Shyness OS')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-primary dark:text-white">Data Kontrak Supplier</h1>
            <p class="text-sm text-secondary dark:text-gray-400">Daftar kontrak yang terafiliasi dengan akun Anda.</p>
        </div>
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

    @if($contracts instanceof \Illuminate\Pagination\LengthAwarePaginator && $contracts->isEmpty() || $contracts instanceof \Illuminate\Support\Collection && $contracts->isEmpty())
        <div class="bg-white dark:bg-primary border border-thin dark:border-gray-800 rounded-xl p-8 text-center">
            <p class="text-secondary dark:text-gray-400">Belum ada kontrak yang terdaftar.</p>
        </div>
    @else
        <div class="bg-white dark:bg-primary border border-thin dark:border-gray-800 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-[#151515]">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Kode</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Supplier</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Distributor</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Nilai Kontrak</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Periode</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($contracts as $contract)
                    <tr class="hover:bg-gray-50 dark:hover:bg-[#1a1a1a]">
                        <td class="px-4 py-3 font-mono font-bold text-primary dark:text-white">{{ $contract->contract_code }}</td>
                        <td class="px-4 py-3 text-primary dark:text-gray-300">{{ $contract->supplier->company_name ?? '-' }}</td>
                        <td class="px-4 py-3 text-primary dark:text-gray-300">{{ $contract->distributor_company }}</td>
                        <td class="px-4 py-3 text-primary dark:text-gray-300">Rp{{ number_format($contract->contract_value, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-secondary dark:text-gray-400 text-xs">
                            {{ \Carbon\Carbon::parse($contract->contract_start_date)->format('d/m/Y') }} -
                            {{ \Carbon\Carbon::parse($contract->contract_end_date)->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3">
                            @if($contract->status === 'active')
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Active</span>
                            @elseif($contract->status === 'expired')
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">Expired</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Terminated</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.contracts.show', $contract) }}" class="text-blue-600 hover:underline text-xs font-semibold">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($contracts instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-4">{{ $contracts->links() }}</div>
        @endif
    @endif
</div>
@endsection
