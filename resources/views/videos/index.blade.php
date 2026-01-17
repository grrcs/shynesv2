@extends('layouts.app')

@section('title', 'Konten Video - Shyness OS')

@section('content')
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Konten Video</h2>
            <p class="text-sm text-gray-500">Koleksi Reels, TikTok, dan Video Promosi.</p>
        </div>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('videos.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-black transition-all shadow-md">
                <i class="fa fa-upload mr-2"></i> Upload Video
            </a>
        @endif
    </div>

    <!-- Grid Video -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($videos as $video)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden group flex flex-col">
                <!-- Video Player -->
                <div class="aspect-w-16 aspect-h-9 bg-black relative">
                    <video controls class="w-full h-56 object-cover">
                        <source src="{{ asset('storage/videos/'.$video->video_file) }}" type="video/mp4">
                        Browser Anda tidak mendukung tag video.
                    </video>
                </div>

                <!-- Info -->
                <div class="p-5 flex-1 flex flex-col">
                    <h3 class="text-lg font-bold text-gray-900 mb-1 leading-tight">{{ $video->title }}</h3>
                    <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $video->caption ?? 'Tidak ada caption' }}</p>

                    <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-center">
                        <span class="text-xs text-gray-400">{{ $video->created_at->diffForHumans() }}</span>
                        @if(auth()->user()->isAdmin())
                            <form onsubmit="return confirm('Hapus video ini?');" action="{{ route('videos.destroy', $video->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium"><i class="fa fa-trash mr-1"></i> Hapus</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <i class="fa-solid fa-video text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Belum ada video</h3>
                <p class="mt-1 text-sm text-gray-500">Silakan upload video konten baru.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $videos->links() }}
    </div>
@endsection
