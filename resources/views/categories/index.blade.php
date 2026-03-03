@extends('layouts.app')

@section('title', 'Kategori - Shyness OS')

@section('content')
    <!-- STATISTIK CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card 1: Total Kategori -->
        <div class="bg-white dark:bg-primary rounded-xl p-6 shadow-sm border border-thin dark:border-gray-800 transition-colors flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-secondary dark:text-gray-400">Total Kategori</p>
                <p class="text-3xl font-bold text-primary dark:text-white mt-1">{{ $categories->total() }}</p>
            </div>
            <div class="p-3 bg-gray-50 dark:bg-[#151515] rounded-full text-gray-600 dark:text-gray-300">
                <i class="fa fa-tags text-xl"></i>
            </div>
        </div>
        <!-- Card 2: Kategori Terbaru -->
        <div class="bg-white dark:bg-primary rounded-xl p-6 shadow-sm border border-thin dark:border-gray-800 transition-colors flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-secondary dark:text-gray-400">Terbaru</p>
                <p class="text-sm font-bold text-primary dark:text-white mt-1 truncate w-32">
                    {{ $categories->first() ? $categories->first()->name : '-' }}
                </p>
            </div>
            <div class="p-3 bg-gray-50 dark:bg-[#151515] rounded-full text-gray-600 dark:text-gray-300">
                <i class="fa fa-clock text-xl"></i>
            </div>
        </div>
        <!-- Card 3: Status -->
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
            <h2 class="text-2xl font-bold text-primary dark:text-white">Kategori Produk</h2>
            <p class="text-sm text-secondary dark:text-gray-400">Kelompokkan produk Anda agar lebih rapi.</p>
        </div>
        @if(auth()->user()->isAdmin())
            <button onclick="openCategoryModal()" class="inline-flex items-center justify-center px-5 py-2.5 bg-primary text-white dark:bg-white dark:text-primary hover:bg-black dark:hover:bg-gray-200 text-sm font-medium rounded-lg transition-all shadow-md">
                <i class="fa fa-plus mr-2"></i> Buat Kategori
            </button>
        @endif
    </div>

    <!-- Tabel Kategori -->
    <div class="bg-white dark:bg-primary rounded-xl shadow-sm border border-thin dark:border-gray-800 transition-colors overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
            <thead class="bg-gray-50 dark:bg-transparent border-b border-thin dark:border-gray-800">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase tracking-wider w-16">#</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase tracking-wider">Nama Kategori</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-secondary dark:text-gray-400 uppercase tracking-wider">Deskripsi</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-secondary dark:text-gray-400 uppercase tracking-wider w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                @forelse ($categories as $category)
                    <tr class="hover:bg-gray-50 dark:bg-[#151515] transition-colors group">
                        <td class="px-6 py-4 text-sm text-secondary dark:text-gray-400 font-mono">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-primary dark:text-white">{{ $category->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-secondary dark:text-gray-400">{{ $category->description ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center space-x-2">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('categories.edit', $category->id) }}" class="p-2 text-secondary hover:text-black dark:text-gray-400 dark:hover:text-white dark:text-white transition-colors"><i class="fa fa-pencil"></i></a>

                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin hapus kategori ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-400 hover:text-red-600 dark:text-red-500 dark:hover:text-red-300 transition-colors"><i class="fa fa-trash"></i></button>
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
        <div class="bg-gray-50 dark:bg-[#151515] px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $categories->links() }}
        </div>
    </div>

    <!-- Modal Tambah Kategori -->
    <div id="categoryModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center transition-opacity">
        <div class="bg-white dark:bg-primary border border-thin dark:border-gray-800 rounded-xl shadow-lg w-full max-w-md p-6 relative transition-colors">
            <button type="button" onclick="closeCategoryModal()" class="absolute top-4 right-4 text-gray-400 hover:text-black dark:hover:text-white transition-colors">
                <i class="fa fa-times text-xl"></i>
            </button>
            <h3 class="text-xl font-bold text-primary dark:text-white mb-4">Tambah Kategori Baru</h3>
            <form id="categoryForm" onsubmit="submitCategory(event)">
                <div class="mb-4">
                    <label class="block text-sm font-bold text-secondary dark:text-gray-300 mb-1">Nama Kategori</label>
                    <input type="text" id="cat_name" required class="w-full px-4 py-3 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors focus:ring-2 focus:ring-black dark:focus:ring-white">
                    <p id="cat_name_error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-bold text-secondary dark:text-gray-300 mb-1">Deskripsi (Opsional)</label>
                    <textarea id="cat_desc" rows="3" class="w-full px-4 py-3 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors focus:ring-2 focus:ring-black dark:focus:ring-white resize-y"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeCategoryModal()" class="px-5 py-2 bg-gray-100 dark:bg-[#151515] hover:bg-gray-200 dark:hover:bg-gray-800 text-secondary dark:text-gray-300 font-bold transition-colors">Batal</button>
                    <button type="submit" id="cat_submit_btn" class="px-5 py-2 bg-primary text-white dark:bg-white dark:text-primary font-bold hover:bg-black dark:hover:bg-gray-200 transition-colors flex items-center">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function openCategoryModal() {
        document.getElementById('categoryModal').classList.remove('hidden');
        setTimeout(() => document.getElementById('cat_name').focus(), 100);
    }
    function closeCategoryModal() {
        document.getElementById('categoryModal').classList.add('hidden');
        document.getElementById('categoryForm').reset();
        document.getElementById('cat_name_error').classList.add('hidden');
    }
    async function submitCategory(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('cat_submit_btn');
        const nameInput = document.getElementById('cat_name');
        const descInput = document.getElementById('cat_desc');
        const errorText = document.getElementById('cat_name_error');
        
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i> Menyimpan...';
        errorText.classList.add('hidden');

        try {
            const response = await fetch('{{ route("categories.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    name: nameInput.value, 
                    description: descInput.value 
                })
            });

            const data = await response.json();

            if (!response.ok) {
                if (data.errors && data.errors.name) {
                    errorText.innerText = data.errors.name[0];
                    errorText.classList.remove('hidden');
                } else {
                    if (typeof toastr !== 'undefined') toastr.error(data.message || 'Terjadi kesalahan.');
                    else alert(data.message || 'Terjadi kesalahan.');
                }
            } else {
                closeCategoryModal();
                if (typeof toastr !== 'undefined') {
                    toastr.success('Kategori berhasil ditambahkan');
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    window.location.reload();
                }
            }
        } catch (error) {
            if (typeof toastr !== 'undefined') toastr.error('Terjadi kesalahan koneksi.');
            else alert('Terjadi kesalahan koneksi.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }
</script>
@endpush

