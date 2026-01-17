@extends('layouts.app')

@section('title', 'Kategori - Shyness OS')

@section('content')
    <!-- STATISTIK CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card 1: Total Kategori -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Kategori</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $categories->total() }}</p>
            </div>
            <div class="p-3 bg-gray-50 rounded-full text-gray-600">
                <i class="fa fa-tags text-xl"></i>
            </div>
        </div>
        <!-- Card 2: Kategori Terbaru -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Terbaru</p>
                <p class="text-sm font-bold text-gray-900 mt-1 truncate w-32">
                    {{ $categories->first() ? $categories->first()->name : '-' }}
                </p>
            </div>
            <div class="p-3 bg-gray-50 rounded-full text-gray-600">
                <i class="fa fa-clock text-xl"></i>
            </div>
        </div>
        <!-- Card 3: Status -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Status System</p>
                <p class="text-sm font-bold text-gray-900 mt-1 flex items-center gap-1">
                    <span class="w-2 h-2 bg-gray-900 rounded-full animate-pulse"></span> Online
                </p>
            </div>
            <div class="p-3 bg-gray-50 rounded-full text-gray-600">
                <i class="fa fa-server text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Kategori Produk</h2>
            <p class="text-sm text-gray-500">Kelompokkan produk Anda agar lebih rapi.</p>
        </div>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('categories.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-black transition-all shadow-md">
                <i class="fa fa-plus mr-2"></i> Buat Kategori
            </a>
        @endif
    </div>

    <!-- Tabel Kategori -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-16">#</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($categories as $category)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900">{{ $category->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500">{{ $category->description ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center space-x-2">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('categories.edit', $category->id) }}" class="p-2 text-gray-400 hover:text-gray-900 transition-colors"><i class="fa fa-pencil"></i></a>

                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin hapus kategori ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition-colors"><i class="fa fa-trash"></i></button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400">Read Only</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
        <!-- Pagination -->
        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $categories->links() }}
        </div>
    </div>
@endsection
