@extends('layouts.app')

@section('title', 'Edit Produk - Shyness OS')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-primary dark:text-white">Edit Produk</h2>
                <p class="text-sm text-secondary dark:text-gray-400">Perbarui informasi, harga, atau stok barang.</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-sm text-secondary hover:text-primary dark:text-gray-400 dark:hover:text-white font-medium transition-colors"><i class="fa fa-arrow-left mr-1"></i> Kembali</a>
        </div>

        <div class="bg-white dark:bg-primary border-thin dark:border-gray-800 transition-colors p-8">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Grid Gambar & Info Dasar -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <!-- Kiri: Gambar -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">Foto Utama Produk</label>

                            <!-- Preview Gambar Lama -->
                            <div class="mb-3 relative rounded-lg overflow-hidden border border-gray-200 w-full h-64">
                                <img src="{{ asset('storage/products/'.$product->image) }}" class="w-full h-full object-cover" alt="Preview">
                            </div>

                            <input type="file" name="image" accept="image/*" class="block w-full text-sm text-secondary dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-secondary dark:text-gray-300 hover:file:dark:bg-gray-800 cursor-pointer">
                            <p class="text-xs text-gray-400 mt-1">*Kosongkan jika tidak ingin mengubah foto utama.</p>
                            @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">Foto Tambahan / Video (Saat ini)</label>
                            @if($product->media && $product->media->count() > 0)
                                <div class="grid grid-cols-3 gap-2 mb-3">
                                    @foreach($product->media as $media)
                                        <div class="relative rounded overflow-hidden border border-gray-200 aspect-square">
                                            @if($media->file_type == 'image')
                                                <img src="{{ asset('storage/products/'.$media->file_path) }}" class="w-full h-full object-cover">
                                            @else
                                                <video src="{{ asset('storage/products_video/'.$media->file_path) }}" class="w-full h-full object-cover" controls></video>
                                            @endif
                                            <!-- Optional: You might want to add a form to delete specific media. For now just displaying it -->
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-secondary dark:text-gray-400 mb-3">Belum ada foto/video tambahan.</p>
                            @endif

                            <label class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">Tambah Foto Baru (Bisa lebih dari 1)</label>
                            <input type="file" name="additional_images[]" multiple accept="image/*" class="block w-full text-sm text-secondary dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-secondary dark:text-gray-300 hover:file:dark:bg-gray-800 cursor-pointer mb-4">

                            <label class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">Tambah Video Baru (Opsional)</label>
                            <input type="file" name="product_video" accept="video/*" class="block w-full text-sm text-secondary dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-secondary dark:text-gray-300 hover:file:dark:bg-gray-800 cursor-pointer">
                        </div>
                    </div>

                    <!-- Kanan: Form Data -->
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">Nama Produk</label>
                            <input type="text" name="title" class="w-full px-4 py-3 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors focus:ring-2 focus:ring-black outline-none" placeholder="Nama Produk" value="{{ old('title', $product->title) }}">
                            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-bold text-secondary dark:text-gray-300">Kategori</label>
                                <button type="button" onclick="openCategoryModal()" class="text-xs text-blue-600 hover:text-blue-800 hover:underline"><i class="fa fa-plus mr-1"></i>Kategori Baru</button>
                            </div>
                            <select name="category_id" id="category_select" class="w-full px-4 py-3 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors focus:ring-2 focus:ring-black outline-none dark:bg-primary">
                                <option value="" disabled>-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">Harga (Rp)</label>
                                <input type="number" name="price" class="w-full px-4 py-3 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors focus:ring-2 focus:ring-black outline-none" placeholder="0" value="{{ old('price', $product->price) }}">
                                @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">Stok</label>
                                <input type="number" name="stock" class="w-full px-4 py-3 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors focus:ring-2 focus:ring-black outline-none" placeholder="0" value="{{ old('stock', $product->stock) }}">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">Link E-Commerce (Opsional)</label>
                            <input type="text" name="link_shopee" class="w-full px-4 py-3 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors focus:ring-2 focus:ring-black outline-none" placeholder="https://..." value="{{ old('link_shopee', $product->link_shopee) }}">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-3 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors focus:ring-2 focus:ring-black outline-none dark:bg-primary">
                                <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active (Dijual)</option>
                                <option value="sold_out" {{ old('status', $product->status) == 'sold_out' ? 'selected' : '' }}>Sold Out (Habis)</option>
                                <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive (Disembunyikan)</option>
                            </select>
                        </div>

                        <!-- Diskon / Promo -->
                        <div class="border border-thin dark:border-gray-800 p-4 rounded-xl space-y-4">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" name="is_discount_active" value="1" {{ old('is_discount_active', $product->is_discount_active) ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-black focus:ring-black">
                                <span class="text-sm font-bold text-secondary dark:text-gray-300">Aktifkan Harga Diskon?</span>
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-secondary dark:text-gray-300 mb-2">Harga Set. Diskon</label>
                                    <input type="number" name="discount_price" class="w-full px-3 py-2 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors focus:ring-2 focus:ring-black" placeholder="Cth: 90000" value="{{ old('discount_price', $product->discount_price) }}">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-secondary dark:text-gray-300 mb-2">Kuota Max (Item)</label>
                                    <input type="number" name="discount_limit" class="w-full px-3 py-2 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors focus:ring-2 focus:ring-black" placeholder="Cth: 5" value="{{ old('discount_limit', $product->discount_limit) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">Deskripsi Produk</label>
                    <textarea name="description" id="content" rows="6" class="w-full px-4 py-3 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors focus:ring-2 focus:ring-black outline-none resize-y" placeholder="Tuliskan deskripsi produk di sini...">{{ old('description', $product->description) }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 border-t border-gray-100 flex gap-3">
                    <button type="submit" class="w-full py-3 bg-primary text-white dark:bg-white dark:text-primary font-bold rounded-lg hover:bg-black dark:bg-gray-200 transition-all shadow-md">
                        <i class="fa fa-save mr-2"></i> UPDATE PRODUK
                    </button>
                    <a href="{{ route('products.index') }}" class="w-full py-3 bg-gray-100 dark:bg-gray-800 text-secondary dark:text-gray-300 font-bold rounded-lg hover:dark:bg-gray-800 transition-all text-center">
                        BATAL
                    </a>
                </div>
            </form>
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
                    // Berhasil, tambahkan option baru ke select
                    const select = document.getElementById('category_select');
                    const option = document.createElement('option');
                    option.value = data.category.id;
                    option.text = data.category.name;
                    option.selected = true;
                    select.appendChild(option);
                    closeCategoryModal();
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

