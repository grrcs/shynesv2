<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori - Shyness OS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { font-family: 'Inter', sans-serif; background-color: #f9fafb; }</style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    <!-- Navbar Minimalis -->
    <nav class="bg-white border-b border-gray-200 px-4 py-3 shadow-sm">
        <div class="max-w-4xl mx-auto flex items-center">
            <a href="{{ route('categories.index') }}" class="flex items-center gap-2 hover:opacity-80 transition group">
                <img src="{{ asset('storage/images/shyness.png') }}" class="h-8 w-auto" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=S&background=000&color=fff';">
                <span class="font-bold text-lg">SHYNESS</span>
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-xl mx-auto px-4 py-12">
        <div class="mb-6">
            <a href="{{ route('categories.index') }}" class="text-sm text-gray-500 hover:text-black mb-2 inline-block"><i class="fa fa-arrow-left mr-1"></i> Kembali ke Daftar</a>
            <h2 class="text-3xl font-bold text-gray-900">Buat Kategori Baru</h2>
            <p class="text-gray-500 mt-1">Tambahkan label untuk mengelompokkan produk Anda.</p>
        </div>

        <div class="bg-white rounded-xl shadow border border-gray-200 p-8">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf

                <!-- Input Nama -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Kategori</label>
                    <input type="text" name="name"
                        class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 focus:bg-white focus:ring-2 focus:ring-black focus:border-black transition-all outline-none @error('name') border-red-500 @enderror"
                        placeholder="Contoh: T-Shirt, Pants, Aksesoris" value="{{ old('name') }}" autofocus>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Input Deskripsi -->
                <div class="mb-8">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi (Opsional)</label>
                    <textarea name="description" rows="3"
                        class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 focus:bg-white focus:ring-2 focus:ring-black focus:border-black transition-all outline-none"
                        placeholder="Keterangan singkat tentang kategori ini...">{{ old('description') }}</textarea>
                </div>

                <!-- Tombol -->
                <button type="submit" class="w-full py-3.5 px-6 rounded-lg bg-gray-900 text-white font-bold hover:bg-black transform active:scale-95 transition-all shadow-lg">
                    SIMPAN KATEGORI
                </button>
            </form>
        </div>
    </div>

</body>
</html>
