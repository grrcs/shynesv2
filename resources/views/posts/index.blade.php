@extends('layouts.app')

@section('title', 'Manajemen Post - Shyness')

@section('content')
    <!-- STATISTIK CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card 1 -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Post</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $posts->total() }}</p>
            </div>
            <div class="p-3 bg-gray-50 rounded-full text-gray-600">
                <i class="fa fa-layer-group text-xl"></i>
            </div>
        </div>
        <!-- Card 2 -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Terbaru</p>
                <p class="text-sm font-bold text-gray-900 mt-1 truncate w-32">
                    {{ $posts->first() ? $posts->first()->title : '-' }}
                </p>
            </div>
            <div class="p-3 bg-gray-50 rounded-full text-gray-600">
                <i class="fa fa-clock text-xl"></i>
            </div>
        </div>
        <!-- Card 3 -->
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

    <!-- Header & Toolbar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Konten & Artikel</h2>
            <p class="text-sm text-gray-500">Manajemen seluruh data postingan Anda.</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <!-- SEARCH BAR -->
            <form action="{{ route('posts.index') }}" method="GET" class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa fa-search text-gray-400 group-focus-within:text-gray-800 transition-colors"></i>
                </div>
                <input type="text" name="search" placeholder="Cari judul..."
                    class="pl-10 pr-4 py-2 w-full sm:w-64 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-all bg-white"
                    value="{{ request('search') }}">
            </form>

            <!-- TOGGLE VIEW BUTTONS -->
            <div class="flex bg-white rounded-lg border border-gray-300 p-1 shadow-sm h-10 items-center">
                <button id="btnList" onclick="switchView('list')" class="p-1.5 px-3 rounded-md text-gray-900 bg-gray-100 transition-all">
                    <i class="fa fa-list"></i>
                </button>
                <button id="btnGrid" onclick="switchView('grid')" class="p-1.5 px-3 rounded-md text-gray-500 hover:bg-gray-50 transition-all">
                    <i class="fa fa-th-large"></i>
                </button>
            </div>

            @if(auth()->user()->isAdmin())
                <a href="{{ route('posts.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-black transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="fa fa-plus mr-2"></i> Tambah
                </a>
            @endif
        </div>
    </div>

    <!-- VIEW 1: TABLE / LIST (Default) -->
    <div id="listView" class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden fade-in">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Visual</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Detail Post</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($posts as $post)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="h-16 w-24 rounded-lg overflow-hidden border border-gray-200 relative">
                                    <img class="h-full w-full object-cover transform group-hover:scale-110 transition-transform duration-300"
                                         src="{{ asset('storage/posts/'.$post->image) }}" alt="Img">
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">{{ $post->title }}</div>
                                <div class="text-xs text-gray-500 mt-1 line-clamp-1">{!! strip_tags($post->content) !!}</div>
                                <div class="mt-2 text-xs text-gray-400">
                                    <i class="fa fa-calendar mr-1"></i> {{ $post->created_at->format('d M Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                    {{ $post->category->name ?? 'Tanpa Kategori' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($post->status == 'publish')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-900 text-white border border-transparent shadow-sm">
                                        <span class="w-1.5 h-1.5 bg-white rounded-full mr-1.5"></span>
                                        Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-300">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></span>
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center space-x-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('posts.show', $post->id) }}" class="p-2 text-gray-500 hover:text-gray-900 transition-colors" title="Lihat"><i class="fa fa-eye"></i></a>
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('posts.edit', $post->id) }}" class="p-2 text-gray-500 hover:text-gray-900 transition-colors" title="Edit"><i class="fa fa-pencil"></i></a>
                                        <button onclick="openDeleteModal('{{ route('posts.destroy', $post->id) }}', '{{ $post->title }}')" class="p-2 text-gray-500 hover:text-red-600 transition-colors" title="Hapus"><i class="fa fa-trash"></i></button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-10 text-center text-gray-500">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $posts->links() }}
        </div>
    </div>

    <!-- VIEW 2: GRID / CARD (Hidden by default) -->
    <div id="gridView" class="hidden fade-in">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($posts as $post)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden group hover:shadow-md transition-all duration-300 flex flex-col h-full relative">
                    <div class="absolute top-3 left-3 z-10">
                        @if ($post->status == 'publish')
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold bg-gray-900/90 text-white backdrop-blur-sm shadow-sm">Published</span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold bg-white/90 text-gray-600 backdrop-blur-sm shadow-sm border border-gray-200">Draft</span>
                        @endif
                    </div>
                    <div class="h-48 overflow-hidden relative">
                        <img class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
                             src="{{ asset('storage/posts/'.$post->image) }}" alt="{{ $post->title }}">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <a href="{{ route('posts.show', $post->id) }}" class="bg-white text-gray-900 rounded-full p-3 shadow-lg hover:scale-110 transition-transform">
                                <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs font-bold tracking-wide uppercase text-gray-500 border border-gray-200 px-2 py-0.5 rounded-md">
                                {{ $post->category->name ?? 'General' }}
                            </span>
                            <span class="text-xs text-gray-400"><i class="fa fa-calendar mr-1"></i> {{ $post->created_at->format('d M') }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2 leading-tight group-hover:text-gray-600 transition-colors">
                            {{ Str::limit($post->title, 40) }}
                        </h3>
                        <p class="text-gray-500 text-sm line-clamp-3 mb-4 flex-1">
                            {!! strip_tags($post->content) !!}
                        </p>
                        @if(auth()->user()->isAdmin())
                            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                                <a href="{{ route('posts.edit', $post->id) }}" class="text-sm font-medium text-gray-600 hover:text-black transition-colors">Edit Post</a>
                                <button onclick="openDeleteModal('{{ route('posts.destroy', $post->id) }}', '{{ $post->title }}')" class="text-gray-400 hover:text-red-600 transition-colors"><i class="fa fa-trash"></i></button>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-gray-500">Data tidak ditemukan.</div>
            @endforelse
        </div>
        <div class="mt-6">{{ $posts->links() }}</div>
    </div>

    <!-- Modal Delete -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-800 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fa fa-trash text-gray-800"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Hapus Post</h3>
                            <div class="mt-2"><p class="text-sm text-gray-500">Yakin hapus <strong id="deletePostTitle"></strong>? Tindakan ini permanen.</p></div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                    <form id="deleteForm" action="" method="POST" class="w-full sm:w-auto sm:ml-3">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-900 text-base font-medium text-white hover:bg-black focus:outline-none sm:text-sm">Hapus</button>
                    </form>
                    <button onclick="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openDeleteModal(url, title) {
            document.getElementById('deleteForm').action = url;
            document.getElementById('deletePostTitle').innerText = title;
            document.getElementById('deleteModal').classList.remove('hidden');
        }
        function closeModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
        function switchView(view) {
            const listView = document.getElementById('listView');
            const gridView = document.getElementById('gridView');
            const btnList = document.getElementById('btnList');
            const btnGrid = document.getElementById('btnGrid');

            if (view === 'list') {
                listView.classList.remove('hidden');
                gridView.classList.add('hidden');
                btnList.classList.add('bg-gray-100', 'text-gray-900');
                btnList.classList.remove('text-gray-500', 'hover:bg-gray-50');
                btnGrid.classList.remove('bg-gray-100', 'text-gray-900');
                btnGrid.classList.add('text-gray-500', 'hover:bg-gray-50');
            } else {
                listView.classList.add('hidden');
                gridView.classList.remove('hidden');
                btnGrid.classList.add('bg-gray-100', 'text-gray-900');
                btnGrid.classList.remove('text-gray-500', 'hover:bg-gray-50');
                btnList.classList.remove('bg-gray-100', 'text-gray-900');
                btnList.classList.add('text-gray-500', 'hover:bg-gray-50');
            }
            localStorage.setItem('preferredView', view);
        }
        document.addEventListener("DOMContentLoaded", function() {
            const savedView = localStorage.getItem('preferredView');
            if(savedView) { switchView(savedView); }
        });
    </script>
@endpush
