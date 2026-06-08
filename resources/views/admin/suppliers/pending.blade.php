@extends('layouts.app')

@section('title', 'Persetujuan Supplier - Shyness OS')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-primary dark:text-white">Persetujuan Supplier</h1>
        <p class="text-sm text-secondary dark:text-gray-400">Kelola pendaftaran supplier baru.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-green-700 dark:text-green-400">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-400">{{ session('error') }}</div>
    @endif

    <!-- Pending Suppliers -->
    <div class="bg-white dark:bg-primary border border-thin dark:border-gray-800 rounded-xl overflow-hidden mb-8">
        <div class="px-4 py-3 bg-gray-50 dark:bg-[#151515] border-b border-gray-100 dark:border-gray-800">
            <h2 class="text-sm font-bold text-primary dark:text-white uppercase tracking-widest">Pengajuan Baru</h2>
        </div>

        @if($pendingSuppliers->isEmpty())
            <div class="p-8 text-center text-secondary dark:text-gray-400 text-sm">Tidak ada pengajuan supplier baru.</div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">
                        <th class="px-4 py-3">Perusahaan</th>
                        <th class="px-4 py-3">Kontak</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Telepon</th>
                        <th class="px-4 py-3">User</th>
                        <th class="px-4 py-3">Tanggal Daftar</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($pendingSuppliers as $supplier)
                    <tr class="hover:bg-gray-50 dark:hover:bg-[#1a1a1a]">
                        <td class="px-4 py-3 font-semibold text-primary dark:text-white">{{ $supplier->company_name }}</td>
                        <td class="px-4 py-3 text-primary dark:text-gray-300">{{ $supplier->contact_person }}</td>
                        <td class="px-4 py-3 text-primary dark:text-gray-300">{{ $supplier->email }}</td>
                        <td class="px-4 py-3 text-primary dark:text-gray-300">{{ $supplier->phone }}</td>
                        <td class="px-4 py-3 text-primary dark:text-gray-300">{{ $supplier->user->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-secondary dark:text-gray-400 text-xs">{{ $supplier->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 flex gap-2">
                            <form action="{{ route('admin.suppliers.approve', $supplier) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded text-xs font-semibold hover:bg-green-700">Setujui</button>
                            </form>
                            <form action="{{ route('admin.suppliers.reject', $supplier) }}" method="POST" onsubmit="return confirm('Tolak pengajuan ini?')">
                                @csrf
                                <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded text-xs font-semibold hover:bg-red-700">Tolak</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($pendingSuppliers instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="p-4">{{ $pendingSuppliers->links() }}</div>
            @endif
        @endif
    </div>

    <!-- Approved Suppliers -->
    <div class="bg-white dark:bg-primary border border-thin dark:border-gray-800 rounded-xl overflow-hidden">
        <div class="px-4 py-3 bg-gray-50 dark:bg-[#151515] border-b border-gray-100 dark:border-gray-800">
            <h2 class="text-sm font-bold text-primary dark:text-white uppercase tracking-widest">Supplier Disetujui</h2>
        </div>

        @if($approvedSuppliers->isEmpty())
            <div class="p-8 text-center text-secondary dark:text-gray-400 text-sm">Belum ada supplier yang disetujui.</div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase">
                        <th class="px-4 py-3">Perusahaan</th>
                        <th class="px-4 py-3">Kontak</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Tenant ID</th>
                        <th class="px-4 py-3">Disetujui</th>
                        <th class="px-4 py-3">User</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($approvedSuppliers as $supplier)
                    <tr class="hover:bg-gray-50 dark:hover:bg-[#1a1a1a]">
                        <td class="px-4 py-3 font-semibold text-primary dark:text-white">{{ $supplier->company_name }}</td>
                        <td class="px-4 py-3 text-primary dark:text-gray-300">{{ $supplier->contact_person }}</td>
                        <td class="px-4 py-3 text-primary dark:text-gray-300">{{ $supplier->email }}</td>
                        <td class="px-4 py-3 font-mono text-xs text-primary dark:text-gray-300">{{ $supplier->tenant_id }}</td>
                        <td class="px-4 py-3 text-secondary dark:text-gray-400 text-xs">{{ $supplier->approved_at ? $supplier->approved_at->format('d/m/Y H:i') : '-' }}</td>
                        <td class="px-4 py-3 text-primary dark:text-gray-300">{{ $supplier->user->name ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($approvedSuppliers instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="p-4">{{ $approvedSuppliers->links() }}</div>
            @endif
        @endif
    </div>
</div>
@endsection
