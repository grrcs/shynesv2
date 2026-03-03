@extends('layouts.app')

@section('title', 'Opsi Pembayaran - Shyness OS')

@section('content')
    <!-- STATISTIK CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card 1: Total Opsi Pembayaran -->
        <div class="bg-white dark:bg-primary rounded-xl p-6 shadow-sm border border-thin dark:border-gray-800 transition-colors flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-secondary dark:text-gray-400">Total Opsi Pembayaran</p>
                <p class="text-3xl font-bold text-primary dark:text-white mt-1">{{ $paymentOptions->total() }}</p>
            </div>
            <div class="p-3 bg-gray-50 dark:bg-[#151515] rounded-full text-gray-600 dark:text-gray-300">
                <i class="fa fa-credit-card text-xl"></i>
            </div>
        </div>
        <!-- Card 2: Opsi Aktif -->
        <div class="bg-white dark:bg-primary rounded-xl p-6 shadow-sm border border-thin dark:border-gray-800 transition-colors flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-secondary dark:text-gray-400">Opsi Aktif</p>
                <p class="text-3xl font-bold text-primary dark:text-white mt-1">{{ $paymentOptions->where('is_active', true)->count() }}</p>
            </div>
            <div class="p-3 bg-gray-50 dark:bg-[#151515] rounded-full text-gray-600 dark:text-gray-300">
                <i class="fa fa-check-circle text-xl"></i>
            </div>
        </div>
        <!-- Card 3: Status System -->
        <div class="bg-white dark:bg-primary rounded-xl p-6 shadow-sm border border-thin dark:border-gray-800 transition-colors flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-secondary dark:text-gray-400">Status System</p>
                <p class="text-sm font-bold text-primary dark:text-white mt-1 flex items-center gap-1">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Online
                </p>
            </div>
            <div class="p-3 bg-gray-50 dark:bg-[#151515] rounded-full text-gray-600 dark:text-gray-300">
                <i class="fa fa-server text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-primary dark:text-white">Opsi Pembayaran</h2>
            <p class="text-sm text-secondary dark:text-gray-400">Kelola metode pembayaran yang tersedia untuk pelanggan.</p>
        </div>
        <a href="{{ route('admin.payment-options.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-primary text-white dark:bg-white dark:text-primary hover:bg-black dark:hover:bg-gray-200 text-sm font-medium rounded-lg transition-all shadow-md">
            <i class="fa fa-plus mr-2"></i> Tambah Opsi
        </a>
    </div>

    <!-- Tabel Opsi Pembayaran -->
    <div class="bg-white dark:bg-primary rounded-xl shadow-sm border border-thin dark:border-gray-800 transition-colors overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
            <thead class="bg-gray-50 dark:bg-transparent border-b border-thin dark:border-gray-800">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase tracking-wider w-16">#</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase tracking-wider">Nama Opsi</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase tracking-wider">Deskripsi</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-secondary dark:text-gray-400 uppercase tracking-wider">Pajak (%)</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-secondary dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-secondary dark:text-gray-400 uppercase tracking-wider w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                @forelse ($paymentOptions as $option)
                    <tr class="hover:bg-gray-50 dark:bg-[#151515] transition-colors group">
                        <td class="px-6 py-4 text-sm text-secondary dark:text-gray-400 font-mono">{{ $loop->iteration + ($paymentOptions->currentPage() - 1) * $paymentOptions->perPage() }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-primary dark:text-white">{{ $option->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-secondary dark:text-gray-400">{{ $option->description ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $option->tax_percentage > 0 ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' }}">
                                {{ number_format($option->tax_percentage, 2) }}%
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $option->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                {{ $option->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('admin.payment-options.edit', $option->id) }}" class="p-2 text-secondary hover:text-black dark:text-gray-400 dark:hover:text-white transition-colors" title="Edit">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <form action="{{ route('admin.payment-options.destroy', $option->id) }}" method="POST" onsubmit="return confirm('Yakin hapus opsi pembayaran ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-400 hover:text-red-600 dark:text-red-500 dark:hover:text-red-300 transition-colors" title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">Belum ada opsi pembayaran.</td></tr>
                @endforelse
            </tbody>
        </table>
        <!-- Pagination -->
        @if ($paymentOptions->hasPages())
            <div class="bg-gray-50 dark:bg-[#151515] px-4 py-3 border-t border-gray-200 dark:border-gray-800 sm:px-6">
                {{ $paymentOptions->links() }}
            </div>
        @endif
    </div>
@endsection
