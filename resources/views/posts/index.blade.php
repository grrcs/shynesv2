@extends('layouts.app')

@section('title', 'Posts - Shyness')

@section('content')
    <div class="mb-12 border-b border-thin dark:border-gray-800 pb-6 flex flex-col md:flex-row md:items-end justify-between gap-4 transition-colors">
        <div>
            <h1 class="text-3xl font-serif font-medium text-primary dark:text-white mb-2 transition-colors">Journal & Posts</h1>
            <p class="text-xs tracking-widest uppercase text-secondary">Content Management</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-4">
            <!-- SEARCH BAR -->
            <form action="{{ route('posts.index') }}" method="GET" class="relative group w-full sm:w-auto">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-secondary text-xs"></i>
                </div>
                <input type="text" name="search" placeholder="Search journal..."
                    class="pl-10 pr-4 py-2.5 w-full sm:w-64 bg-transparent border border-thin dark:border-gray-700 text-sm focus:outline-none focus:border-black dark:focus:border-white transition-colors dark:text-white placeholder-gray-400"
                    value="{{ request('search') }}">
            </form>

            <div class="flex items-center gap-4 w-full sm:w-auto mt-4 sm:mt-0">
                <!-- TOGGLE VIEW BUTTONS -->
                <div class="flex border border-thin dark:border-gray-700 p-1">
                    <button id="btnList" onclick="switchView('list')" class="w-8 h-8 flex items-center justify-center text-xs transition-colors bg-black text-white dark:bg-white dark:text-black">
                        <i class="fa-solid fa-list"></i>
                    </button>
                    <button id="btnGrid" onclick="switchView('grid')" class="w-8 h-8 flex items-center justify-center text-xs transition-colors text-secondary hover:text-black dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-800">
                        <i class="fa-solid fa-border-all"></i>
                    </button>
                </div>

                @if(auth()->user()->isAdmin())
                    <a href="{{ route('posts.create') }}" class="px-6 py-2.5 bg-primary text-white dark:bg-white dark:text-primary text-xs tracking-widest uppercase font-medium hover:bg-black dark:hover:bg-gray-200 transition-colors shrink-0">
                        <i class="fa-solid fa-plus mr-2"></i> New Post
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- STATISTIK CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="p-6 border border-thin dark:border-gray-800 bg-white dark:bg-primary transition-colors flex flex-col">
            <span class="text-xs tracking-widest uppercase text-secondary mb-4">Total Posts</span>
            <span class="text-4xl font-serif text-primary dark:text-white">{{ $posts->total() }}</span>
        </div>
        <div class="p-6 border border-thin dark:border-gray-800 bg-white dark:bg-primary transition-colors flex flex-col">
            <span class="text-xs tracking-widest uppercase text-secondary mb-4">Latest Entry</span>
            <span class="text-lg font-serif text-primary dark:text-white truncate" title="{{ $posts->first() ? $posts->first()->title : '-' }}">
                {{ $posts->first() ? $posts->first()->title : '-' }}
            </span>
        </div>
        <div class="p-6 border border-thin dark:border-gray-800 bg-white dark:bg-primary transition-colors flex flex-col">
            <span class="text-xs tracking-widest uppercase text-secondary mb-4">System Status</span>
            <div class="flex items-center gap-2 mt-auto">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                <span class="text-sm text-primary dark:text-white font-medium">Online</span>
            </div>
        </div>
    </div>

    <!-- VIEW 1: TABLE / LIST (Default) -->
    <div id="listView" class="bg-gray-50 dark:bg-[#151515] border border-thin dark:border-gray-800 transition-colors duration-300">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-thin dark:border-gray-800 text-xs tracking-widest uppercase text-secondary">
                        <th class="p-6 font-medium w-32">Visual</th>
                        <th class="p-6 font-medium">Entry Detail</th>
                        <th class="p-6 font-medium w-40">Category</th>
                        <th class="p-6 font-medium w-32">Status</th>
                        <th class="p-6 font-medium text-center w-32">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-light text-primary dark:text-gray-300">
                    @forelse ($posts as $post)
                        <tr class="border-b border-thin dark:border-gray-800 last:border-0 hover:bg-white dark:hover:bg-gray-900 transition-colors group">
                            <td class="p-6">
                                <a href="{{ route('posts.show', $post->id) }}" class="block w-24 h-32 overflow-hidden bg-gray-100 dark:bg-gray-800">
                                    <img class="w-full h-full object-cover filter grayscale hover:grayscale-0 transition-all duration-500 group-hover:scale-105"
                                         src="{{ asset('storage/posts/'.$post->image) }}" alt="Post image">
                                </a>
                            </td>
                            <td class="p-6 align-top pt-6">
                                <div class="font-serif text-lg text-primary dark:text-white mb-2">{{ $post->title }}</div>
                                <div class="text-xs text-secondary line-clamp-2 md:line-clamp-3 mb-4 leading-relaxed max-w-xl">
                                    {!! strip_tags($post->content) !!}
                                </div>
                                <div class="text-xs text-gray-400 dark:text-gray-500 font-mono tracking-widest uppercase">
                                    {{ $post->created_at->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="p-6 align-top pt-6">
                                <span class="text-xs border border-thin dark:border-gray-700 px-3 py-1 tracking-widest uppercase rounded-sm text-secondary bg-white dark:bg-black">
                                    {{ $post->category->name ?? 'General' }}
                                </span>
                            </td>
                            <td class="p-6 align-top pt-6">
                                @if ($post->status == 'publish')
                                    <span class="inline-flex items-center text-xs tracking-widest uppercase text-primary dark:text-white">
                                        <span class="w-1.5 h-1.5 bg-primary dark:bg-white rounded-full mr-2"></span> Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-xs tracking-widest uppercase text-secondary">
                                        <span class="w-1.5 h-1.5 bg-gray-300 dark:bg-gray-600 rounded-full mr-2"></span> Draft
                                    </span>
                                @endif
                            </td>
                            <td class="p-6 align-top pt-6 text-center">
                                <div class="flex justify-center space-x-4 opacity-50 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('posts.show', $post->id) }}" class="text-secondary hover:text-black dark:hover:text-white transition-colors" title="View"><i class="fa-solid fa-eye"></i></a>
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('posts.edit', $post->id) }}" class="text-secondary hover:text-black dark:hover:text-white transition-colors" title="Edit"><i class="fa-solid fa-pen-nib"></i></a>
                                        <button onclick="openDeleteModal('{{ route('posts.destroy', $post->id) }}', '{{ addslashes($post->title) }}')" class="text-secondary hover:text-red-500 transition-colors" title="Delete"><i class="fa-solid fa-xmark"></i></button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-12 text-center text-sm font-light text-secondary">No entries found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($posts->hasPages())
            <div class="p-6 border-t border-thin dark:border-gray-800 bg-white dark:bg-primary transition-colors">
                {{ $posts->links() }}
            </div>
        @endif
    </div>

    <!-- VIEW 2: GRID / CARD (Hidden by default) -->
    <div id="gridView" class="hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($posts as $post)
                <div class="group border border-thin dark:border-gray-800 bg-white dark:bg-[#111111] hover:border-black dark:hover:border-white transition-colors duration-300 flex flex-col">
                    <a href="{{ route('posts.show', $post->id) }}" class="block h-72 overflow-hidden bg-gray-100 dark:bg-gray-900 w-full relative">
                        <img class="w-full h-full object-cover filter grayscale hover:grayscale-0 transition-all duration-700"
                             src="{{ asset('storage/posts/'.$post->image) }}" alt="{{ $post->title }}">
                        <div class="absolute top-4 right-4 flex gap-2">
                             @if ($post->status == 'publish')
                                <span class="px-2 py-1 bg-white dark:bg-black text-black dark:text-white text-[10px] tracking-widest uppercase font-medium">Published</span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 dark:bg-gray-800 text-gray-500 text-[10px] tracking-widest uppercase font-medium">Draft</span>
                            @endif
                        </div>
                    </a>
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-[10px] tracking-widest uppercase text-secondary">
                                {{ $post->category->name ?? 'General' }}
                            </span>
                            <span class="text-[10px] text-gray-400 font-mono tracking-widest">{{ $post->created_at->format('M d, Y') }}</span>
                        </div>
                        <h3 class="text-xl font-serif text-primary dark:text-white mb-3 line-clamp-2">
                            <a href="{{ route('posts.show', $post->id) }}" class="hover:opacity-70 transition-opacity">{{ $post->title }}</a>
                        </h3>
                        <p class="text-sm font-light text-secondary line-clamp-3 mb-6 flex-1 leading-relaxed">
                            {!! strip_tags($post->content) !!}
                        </p>
                        
                        @if(auth()->user()->isAdmin())
                            <div class="pt-4 border-t border-thin dark:border-gray-800 flex items-center justify-between text-xs tracking-widest uppercase font-medium">
                                <a href="{{ route('posts.edit', $post->id) }}" class="text-secondary hover:text-primary dark:hover:text-white transition-colors">Edit</a>
                                <button onclick="openDeleteModal('{{ route('posts.destroy', $post->id) }}', '{{ addslashes($post->title) }}')" class="text-secondary hover:text-red-500 transition-colors">Delete</button>
                            </div>
                        @else
                            <a href="{{ route('posts.show', $post->id) }}" class="text-xs tracking-widest uppercase font-medium text-primary dark:text-white hover:opacity-70 transition-opacity mt-auto">Read More &rarr;</a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-1 border border-thin dark:border-gray-800 md:col-span-2 lg:col-span-3 text-center py-24 bg-gray-50 dark:bg-[#151515] transition-colors text-secondary text-sm font-light">
                    No entries found.
                </div>
            @endforelse
        </div>
        @if($posts->hasPages())
            <div class="mt-8 border-t border-thin dark:border-gray-800 pt-6">
                {{ $posts->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Delete -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
            
            <div class="inline-block align-bottom bg-white dark:bg-primary text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-thin dark:border-gray-700">
                <div class="px-8 pt-8 pb-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-50 dark:bg-red-900/20 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fa-solid fa-xmark text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-6 sm:text-left flex-1">
                            <h3 class="text-xl font-serif text-primary dark:text-white" id="modal-title">Delete Entry</h3>
                            <div class="mt-4">
                                <p class="text-sm font-light text-secondary leading-relaxed">
                                    Are you sure you want to delete <strong id="deletePostTitle" class="font-medium text-black dark:text-white"></strong>? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-8 py-6 bg-gray-50 dark:bg-[#151515] sm:flex sm:flex-row-reverse gap-3 border-t border-thin dark:border-gray-700">
                    <form id="deleteForm" action="" method="POST" class="w-full sm:w-auto">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-red-600 text-white text-xs tracking-widest uppercase font-medium hover:bg-black transition-colors focus:outline-none">
                            Permanently Delete
                        </button>
                    </form>
                    <button onclick="closeModal()" type="button" class="mt-3 sm:mt-0 w-full sm:w-auto px-6 py-2.5 bg-white dark:bg-transparent border border-thin dark:border-gray-600 text-primary dark:text-white text-xs tracking-widest uppercase font-medium hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors focus:outline-none">
                        Cancel
                    </button>
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

            const activeClassesList = ['bg-black', 'text-white', 'dark:bg-white', 'dark:text-black'];
            const inactiveClassesList = ['text-secondary', 'hover:text-black', 'dark:hover:text-white', 'hover:bg-gray-50', 'dark:hover:bg-gray-800'];

            if (view === 'list') {
                listView.classList.remove('hidden');
                gridView.classList.add('hidden');
                
                btnList.classList.add(...activeClassesList);
                btnList.classList.remove(...inactiveClassesList);
                
                btnGrid.classList.remove(...activeClassesList);
                btnGrid.classList.add(...inactiveClassesList);
            } else {
                listView.classList.add('hidden');
                gridView.classList.remove('hidden');
                
                btnGrid.classList.add(...activeClassesList);
                btnGrid.classList.remove(...inactiveClassesList);
                
                btnList.classList.remove(...activeClassesList);
                btnList.classList.add(...inactiveClassesList);
            }
            localStorage.setItem('preferredPostView', view);
        }
        document.addEventListener("DOMContentLoaded", function() {
            const savedView = localStorage.getItem('preferredPostView');
            if(savedView) { switchView(savedView); } else { switchView('list'); }
        });
    </script>
@endpush
