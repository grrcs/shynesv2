@extends('layouts.app')

@section('title', 'Upload Video - Shyness OS')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-primary dark:text-white">Upload Video Baru</h2>
                <p class="text-sm text-secondary dark:text-gray-400">Unggah konten video (MP4) untuk ditampilkan di website.</p>
            </div>
            <a href="{{ route('videos.index') }}" class="text-sm text-secondary hover:text-primary dark:text-gray-400 dark:hover:text-white font-medium transition-colors"><i class="fa fa-arrow-left mr-1"></i> Kembali</a>
        </div>

        <div class="bg-white dark:bg-primary border-thin dark:border-gray-800 transition-colors p-8">
            <form action="{{ route('videos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Input File Video -->
                <div>
                    <label class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">File Video</label>
                    <label for="video_file" class="flex flex-col justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-black transition-colors bg-transparent text-primary dark:text-white dark:bg-transparent cursor-pointer text-center w-full">
                        <div class="space-y-1">
                            <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                            <div class="text-sm text-gray-600">
                                <span class="font-medium text-black hover:underline">Upload file</span>
                                <span class="pl-1">atau drag and drop</span>
                            </div>
                            <p class="text-xs text-secondary dark:text-gray-400">MP4, MOV up to 20MB</p>
                        </div>
                        <input id="video_file" name="video_file" type="file" class="hidden" accept="video/mp4,video/x-m4v,video/*" data-preview-container="#video-preview-container">
                    </label>
                    <div id="video-preview-container" class="mt-2 text-center flex justify-center"></div>
                    @error('video_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Input Judul -->
                <div>
                    <label class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">Judul Video</label>
                    <input type="text" name="title" class="w-full px-4 py-3 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors focus:ring-2 focus:ring-black outline-none" placeholder="Contoh: Behind The Scenes Photoshoot" value="{{ old('title') }}">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Input Caption -->
                <div>
                    <label class="block text-sm font-bold text-secondary dark:text-gray-300 mb-2">Caption (Opsional)</label>
                    <textarea name="caption" rows="3" class="w-full px-4 py-3 bg-transparent border-thin dark:border-gray-800 text-primary dark:text-white outline-none transition-colors focus:ring-2 focus:ring-black outline-none" placeholder="Deskripsi singkat video...">{{ old('caption') }}</textarea>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <button type="submit" class="w-full py-3 bg-primary text-white dark:bg-white dark:text-primary font-bold rounded-lg hover:bg-black dark:bg-gray-200 transition-all">UPLOAD SEKARANG</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
// Any scripts here
</script>
@endpush

