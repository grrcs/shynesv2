@extends('layouts.app')

@section('title', 'Kelola Banner - Shyness OS')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-primary dark:text-white">Banner Promo & Info</h2>
        <p class="text-sm text-secondary dark:text-gray-400">Atur banner yang akan tampil di halaman utama.</p>
    </div>
    @if(auth()->user()->isAdmin())
        <a href="{{ route('banners.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white dark:bg-white dark:text-primary rounded-lg hover:bg-black dark:hover:bg-gray-200 transition-all shadow-md">
            <i class="fa fa-plus mr-2"></i> Tambah Banner
        </a>
    @endif
</div>

<div class="bg-white dark:bg-primary rounded-xl shadow-sm border border-thin dark:border-gray-800 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
        <thead class="bg-gray-50 dark:bg-transparent border-b border-thin dark:border-gray-800">
            <tr>
                <th class="p-4 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase tracking-wider w-20">Gambar</th>
                <th class="px-2 py-4 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase tracking-wider">Judul</th>
                <th class="px-2 py-4 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase tracking-wider">Link</th>
                <th class="px-2 py-4 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase tracking-wider">Status</th>
                <th class="p-4 text-center text-xs font-bold text-secondary dark:text-gray-400 uppercase tracking-wider w-32">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
            @forelse ($banners as $banner)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                    <td class="p-4">
                        <img src="{{ Storage::url($banner->image) }}" class="w-16 h-10 object-cover rounded border border-gray-200 dark:border-gray-700">
                    </td>
                    <td class="px-2 py-4">
                        <div class="text-sm font-bold text-primary dark:text-white">{{ $banner->title ?? '-' }}</div>
                    </td>
                    <td class="px-2 py-4">
                        @if($banner->link)
                            <a href="{{ $banner->link }}" target="_blank" class="text-sm text-blue-500 hover:underline">Link <i class="fa fa-external-link-alt text-xs"></i></a>
                        @else
                            <span class="text-sm text-secondary dark:text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-2 py-4">
                        @if($banner->is_active)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Aktif</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400">Tidak Aktif</span>
                        @endif
                    </td>
                    <td class="p-4 flex items-center justify-center space-x-2">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('banners.edit', $banner->id) }}" class="text-secondary hover:text-black dark:text-gray-400 dark:hover:text-white transition-colors">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus banner ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 dark:text-red-500 dark:hover:text-red-300 transition-colors">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-12 text-center text-secondary dark:text-gray-400">Belum ada banner promo / info yang ditambahkan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="bg-gray-50 dark:bg-[#151515] px-4 py-3 border-t border-gray-200 dark:border-gray-800">
        {{ $banners->links() }}
    </div>
</div>
@endsection
