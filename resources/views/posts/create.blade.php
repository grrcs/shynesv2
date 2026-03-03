@extends('layouts.app')

@section('title', 'Tambah Post Baru - Shyness OS')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold leading-7 text-primary dark:text-white sm:text-3xl sm:truncate">Tambah Post Baru</h2>
                <p class="mt-1 text-sm text-secondary dark:text-gray-400">Silakan isi form di bawah ini.</p>
            </div>
            <a href="{{ route('posts.index') }}" class="text-sm text-secondary hover:text-primary dark:text-gray-400 dark:hover:text-white font-medium transition-colors"><i class="fa fa-arrow-left mr-1"></i> Kembali</a>
        </div>
        <div class="bg-white dark:bg-primary border-thin dark:border-gray-800 transition-colors overflow-hidden">
            <div class="p-8">
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Grid Layout -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Gambar -->
                        <div>
                            <label class="block text-sm font-semibold text-secondary dark:text-gray-300 mb-2">Upload Gambar</label>
                            <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-600 transition-colors bg-transparent text-primary dark:text-white dark:bg-transparent">
                                <input type="file" name="image" class="block w-full text-sm text-secondary dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-800 dark:file:bg-gray-800 dark:file:text-white cursor-pointer focus:outline-none" data-preview-container="#post-image-preview">
                            </div>
                            <div id="post-image-preview" class="mt-2"></div>
                            @error('image') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Kanan: Status & Kategori -->
                        <div class="space-y-6">
                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-semibold text-secondary dark:text-gray-300 mb-2">Status Publikasi</label>
                                <div class="relative">
                                    <select name="status" class="appearance-none w-full px-4 py-3 bg-transparent dark:bg-primary border border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors focus:ring-2 focus:ring-gray-500 cursor-pointer">
                                        <option value="publish" {{ old('status') == 'publish' ? 'selected' : '' }}>Publish</option>
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-secondary dark:text-gray-400"><i class="fa fa-chevron-down text-xs"></i></div>
                                </div>
                            </div>

                            <!-- KATEGORI -->
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <label for="category_id" class="block text-sm font-semibold text-secondary dark:text-gray-300">Kategori Produk</label>
                                    <button type="button" onclick="openCategoryModal()" class="text-xs text-blue-600 hover:text-blue-800 hover:underline"><i class="fa fa-plus mr-1"></i>Kategori Baru</button>
                                </div>
                                <div class="relative">
                                    <select name="category_id" id="category_select" class="appearance-none w-full px-4 py-3 bg-transparent dark:bg-primary border border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors focus:ring-2 focus:ring-gray-500 cursor-pointer @error('category_id') border-red-500 @enderror">
                                        <option value="" disabled selected>-- Pilih Kategori --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-secondary dark:text-gray-400"><i class="fa fa-chevron-down text-xs"></i></div>
                                </div>
                                @error('category_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Judul -->
                    <div>
                        <label class="block text-sm font-semibold text-secondary dark:text-gray-300 mb-1">Judul Post</label>
                        <input type="text" name="title" class="w-full px-4 py-3 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors focus:ring-2 focus:ring-gray-500 outline-none transition-all @error('title') border-red-500 @enderror" placeholder="Judul..." value="{{ old('title') }}">
                        @error('title') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Konten -->
                    <div>
                        <label class="block text-sm font-semibold text-secondary dark:text-gray-300 mb-1">Konten</label>
                        <textarea name="content" id="content" rows="6" class="w-full px-4 py-3 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors focus:ring-2 focus:ring-gray-500 outline-none transition-all resize-y" placeholder="Tuliskan konten di sini...">{{ old('content') }}</textarea>
                        @error('content') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tombol -->
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <button type="submit" class="inline-flex justify-center py-2 px-6 rounded-lg text-white bg-gray-900 hover:bg-black dark:bg-gray-200 transition-all">SIMPAN</button>
                        <button type="reset" class="inline-flex justify-center py-2 px-6 rounded-lg text-gray-800 dark:bg-gray-800 hover:bg-gray-300 transition-all">RESET</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kategori -->
    <div id="categoryModal" class="fixed inset-0 z-50 hidden bg-black dark:bg-gray-200 bg-opacity-50 flex items-center justify-center transition-opacity">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
            <button onclick="closeCategoryModal()" class="absolute top-4 right-4 text-gray-400 hover:text-primary dark:text-white transition-colors">
                <i class="fa fa-times text-xl"></i>
            </button>
            <h3 class="text-xl font-bold text-primary dark:text-white mb-4">Tambah Kategori Baru</h3>
            <form id="categoryForm" onsubmit="submitCategory(event)">
                <div class="mb-4">
                    <label class="block text-sm font-bold text-secondary dark:text-gray-300 mb-1">Nama Kategori</label>
                    <input type="text" id="cat_name" required class="w-full px-4 py-3 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors focus:ring-2 focus:ring-black outline-none transition-all">
                    <p id="cat_name_error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-bold text-secondary dark:text-gray-300 mb-1">Deskripsi (Opsional)</label>
                    <textarea id="cat_desc" rows="3" class="w-full px-4 py-3 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors focus:ring-2 focus:ring-black outline-none transition-all resize-y"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeCategoryModal()" class="px-5 py-2 bg-gray-100 text-secondary dark:text-gray-300 rounded-lg hover:dark:bg-gray-800 font-bold transition-colors">Batal</button>
                    <button type="submit" id="cat_submit_btn" class="px-5 py-2 bg-primary text-white dark:bg-white dark:text-primary rounded-lg hover:bg-black dark:bg-gray-200 font-bold flex items-center transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script> 
        @if ($errors->any()) toastr.error('Periksa inputan Anda.', 'GAGAL!'); @endif 

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
                        alert(data.message || 'Terjadi kesalahan saat menyimpan kategori.');
                    }
                } else {
                    const select = document.getElementById('category_select');
                    const option = document.createElement('option');
                    option.value = data.category.id;
                    option.text = data.category.name;
                    option.selected = true;
                    select.appendChild(option);
                    closeCategoryModal();
                    toastr.success('Kategori berhasil ditambahkan', 'SUKSES');
                }
            } catch (error) {
                alert('Terjadi kesalahan koneksi.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Simpan';
            }
        }
    </script>
@endsection

@push('scripts')
<script>
// Any scripts here
</script>
@endpush

