@extends('layouts.app')

@section('title', 'Kelola Kontrak Penjual - Shyness OS')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-primary dark:text-white">Kontrak Penjual</h1>
            <p class="text-sm text-secondary dark:text-gray-400">Kelola pengajuan dan kontrak penjual.</p>
        </div>
    </div>

    @if($contracts->isEmpty())
        <div class="bg-white dark:bg-primary border border-thin dark:border-gray-800 rounded-xl p-8 text-center">
            <p class="text-secondary dark:text-gray-400">Belum ada pengajuan kontrak penjual.</p>
        </div>
    @else
        <div class="bg-white dark:bg-primary border border-thin dark:border-gray-800 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-[#151515]">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Penjual</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Bisnis</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Telepon</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Markup</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($contracts as $contract)
                    <tr>
                        <td class="px-4 py-3">
                            <p class="font-medium text-primary dark:text-white">{{ $contract->user->name }}</p>
                            <p class="text-xs text-secondary dark:text-gray-400">{{ $contract->user->email }}</p>
                        </td>
                        <td class="px-4 py-3 text-primary dark:text-white">{{ $contract->business_name }}</td>
                        <td class="px-4 py-3 text-secondary dark:text-gray-400">{{ $contract->phone }}</td>
                        <td class="px-4 py-3">
                            @if($contract->status === 'pending')
                                <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 rounded">Pending</span>
                            @elseif($contract->status === 'approved')
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 rounded">Approved</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 rounded">Rejected</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-secondary dark:text-gray-400">{{ $contract->default_markup_percentage }}%</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.sellers.contracts.show', $contract) }}" class="text-xs text-primary dark:text-white underline hover:no-underline">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $contracts->links() }}</div>
    @endif
</div>
@endsection
