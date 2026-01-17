<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Video - Shyness OS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { font-family: 'Inter', sans-serif; background-color: #f9fafb; }</style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    <!-- Navbar (Menggunakan Smart Navbar) -->
    <nav class="bg-white border-b border-gray-200 px-4 py-3 shadow-sm sticky top-0 z-20">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <a href="{{ route('posts.index') }}" class="flex items-center gap-2 hover:opacity-80 transition group">
                    <img src="{{ asset('storage/images/shyness.png') }}" alt="Logo" class="h-9 w-auto object-contain" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=S&background=000&color=fff';">
                    <h1 class="font-bold text-xl text-gray-900 tracking-tight">SHYNESS</h1>
                </a>
            </div>

            <div class="flex items-center gap-6">
                <a href="{{ route('posts.index') }}" class="text-sm transition-colors {{ request()->routeIs('posts.*') ? 'font-bold text-gray-900 border-b-2 border-gray-900 pb-0.5' : 'font-medium text-gray-500 hover:text-gray-900' }}">Postingan</a>
                <a href="{{ route('products.index') }}" class="text-sm transition-colors {{ request()->routeIs('products.*') ? 'font-bold text-gray-900 border-b-2 border-gray-900 pb-0.5' : 'font-medium text-gray-500 hover:text-gray-900' }}">Produk</a>
                <a href="{{ route('categories.index') }}" class="text-sm transition-colors {{ request()->routeIs('categories.*') ? 'font-bold text-gray-900 border-b-2 border-gray-900 pb-0.5' : 'font-medium text-gray-500 hover:text-gray-900' }}">Kategori</a>
                <a href="{{ route('videos.index') }}" class="text-sm transition-colors {{ request()->routeIs('videos.*') ? 'font-bold text-gray-900 border-b-2 border-gray-900 pb-0.5' : 'font-medium text-gray-500 hover:text-gray-900' }}">Video</a>

                <div class="flex items-center gap-2 ml-2 pl-6 border-l border-gray-200">
                    <div class="hidden md:block text-xs text-right"><div class="font-bold text-gray-700">Admin</div><div class="text-gray-400">Panel</div></div>
                    <img src="https://ui-avatars.com/api/?name=Admin&background=1f2937&color=fff" class="w-8 h-8 rounded-full border border-gray-300">
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-3xl mx-auto px-4 py-10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Upload Video Baru</h2>
                <p class="text-sm text-gray-500">Unggah konten video (MP4) untuk ditampilkan di website.</p>
            </div>
            <a href="{{ route('videos.index') }}" class="text-sm text-gray-600 hover:text-black font-medium"><i class="fa fa-arrow-left mr-1"></i> Kembali</a>
        </div>

        <div class="bg-white rounded-xl shadow border border-gray-200 p-8">
            <form action="{{ route('videos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Input File Video -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">File Video</label>
                    <label for="video_file" class="flex flex-col justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-black transition-colors bg-gray-50 cursor-pointer text-center w-full">
                        <div class="space-y-1">
                            <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                            <div class="text-sm text-gray-600">
                                <span class="font-medium text-black hover:underline">Upload file</span>
                                <span class="pl-1">atau drag and drop</span>
                            </div>
                            <p class="text-xs text-gray-500">MP4, MOV up to 20MB</p>
                        </div>
                        <input id="video_file" name="video_file" type="file" class="hidden" accept="video/mp4,video/x-m4v,video/*">
                    </label>
                    @error('video_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Input Judul -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Judul Video</label>
                    <input type="text" name="title" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-black outline-none" placeholder="Contoh: Behind The Scenes Photoshoot" value="{{ old('title') }}">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Input Caption -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Caption (Opsional)</label>
                    <textarea name="caption" rows="3" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-black outline-none" placeholder="Deskripsi singkat video...">{{ old('caption') }}</textarea>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <button type="submit" class="w-full py-3 bg-gray-900 text-white font-bold rounded-lg hover:bg-black transition-all">UPLOAD SEKARANG</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
