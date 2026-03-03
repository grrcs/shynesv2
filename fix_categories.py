import re

with open('resources/views/categories/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Replace cards styling
content = content.replace('bg-white rounded-xl p-6 shadow-sm border border-gray-100', 'bg-white dark:bg-primary rounded-xl p-6 shadow-sm border border-thin dark:border-gray-800 transition-colors')
content = content.replace('text-gray-500', 'text-secondary dark:text-gray-400')
content = content.replace('text-gray-900', 'text-primary dark:text-white')
content = content.replace('bg-gray-50', 'bg-gray-50 dark:bg-[#151515]')
content = content.replace('text-gray-600', 'text-gray-600 dark:text-gray-300')
content = content.replace('bg-gray-900 rounded-full', 'bg-green-500 rounded-full') # Status light
content = content.replace('bg-gray-900 text-white', 'bg-primary text-white dark:bg-white dark:text-primary')

# Replace table container
content = content.replace('bg-white rounded-xl shadow-sm border border-gray-200', 'bg-white dark:bg-primary rounded-xl shadow-sm border border-thin dark:border-gray-800 transition-colors')
content = content.replace('divide-gray-200', 'divide-gray-200 dark:divide-gray-800')

# Table header
content = content.replace('<thead class="bg-gray-50 dark:bg-[#151515]">', '<thead class="bg-gray-50 dark:bg-transparent border-b border-thin dark:border-gray-800">')

# Table row hover
content = content.replace('hover:bg-gray-50 transition-colors', 'hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors')
content = content.replace('text-gray-400 hover:text-primary', 'text-secondary hover:text-black dark:text-gray-400 dark:hover:text-white')
content = content.replace('text-gray-400 hover:text-red-600', 'text-red-400 hover:text-red-600 dark:text-red-500 dark:hover:text-red-300')

# Add Modal & Button logic
content = content.replace(
    '<a href="{{ route(\'categories.create\') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-primary text-white dark:bg-white dark:text-primary text-sm font-medium rounded-lg hover:bg-black transition-all shadow-md">',
    '<button onclick="openCategoryModal()" class="inline-flex items-center justify-center px-5 py-2.5 bg-primary text-white dark:bg-white dark:text-primary hover:bg-black dark:hover:bg-gray-200 text-sm font-medium rounded-lg transition-all shadow-md">'
)
content = content.replace('</a>\n        @endif', '</button>\n        @endif')

modal_html = """
    <!-- Modal Tambah Kategori -->
    <div id="categoryModal" class="fixed inset-0 z-50 hidden bg-black dark:bg-gray-200 bg-opacity-50 flex items-center justify-center transition-opacity">
        <div class="bg-white dark:bg-primary rounded-xl shadow-lg border border-thin dark:border-gray-800 w-full max-w-md p-6 relative">
            <button onclick="closeCategoryModal()" class="absolute top-4 right-4 text-secondary hover:text-primary dark:text-gray-400 dark:hover:text-white transition-colors">
                <i class="fa fa-times text-xl"></i>
            </button>
            <h3 class="text-xl font-bold text-primary dark:text-white mb-4">Tambah Kategori Baru</h3>
            <form id="categoryForm" onsubmit="submitCategory(event)">
                <div class="mb-4">
                    <label class="block text-sm font-bold text-secondary dark:text-gray-300 mb-1">Nama Kategori</label>
                    <input type="text" id="cat_name" required class="w-full px-4 py-3 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white dark:bg-primary outline-none transition-colors focus:ring-2 focus:ring-gray-500">
                    <p id="cat_name_error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-bold text-secondary dark:text-gray-300 mb-1">Deskripsi (Opsional)</label>
                    <textarea id="cat_desc" rows="3" class="w-full px-4 py-3 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white dark:bg-primary outline-none transition-colors focus:ring-2 focus:ring-gray-500 resize-y"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeCategoryModal()" class="px-5 py-2 bg-gray-100 dark:bg-gray-800 text-secondary dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 font-bold transition-colors">Batal</button>
                    <button type="submit" id="cat_submit_btn" class="px-5 py-2 bg-primary text-white dark:bg-white dark:text-primary rounded-lg hover:bg-black dark:hover:bg-gray-200 font-bold flex items-center transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
"""

scripts_html = """
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
"""

content = content.replace('@endsection', modal_html + '\n@endsection\n' + scripts_html)

with open('resources/views/categories/index.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
