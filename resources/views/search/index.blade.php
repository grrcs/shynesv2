@extends('layouts.app')

@section('title', 'Hasil Pencarian: ' . ($query ?: 'Semua') . ' - Shyness OS')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">
            Hasil Pencarian untuk: <span class="text-blue-600">"{{ $query }}"</span>
        </h2>
    </div>

    @if($products->isEmpty() && $posts->isEmpty() && $videos->isEmpty())
        <div class="bg-white rounded-xl p-12 text-center border border-gray-200">
            <div class="text-gray-300 text-6xl mb-4"><i class="fa fa-search"></i></div>
            <h3 class="text-lg font-medium text-gray-900">Tidak ditemukan hasil.</h3>
            <p class="text-gray-500 mt-2">Coba kata kunci lain.</p>
        </div>
    @else
        <!-- Products Results -->
        @if($products->isNotEmpty())
            <div class="mb-12">
                <h3 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">Produk ({{ $products->count() }})</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                             <a href="{{ route('products.show', $product->id) }}" class="block p-4">
                                <img src="{{ $product->image_url }}" class="w-full h-48 object-cover rounded-md mb-4 bg-gray-100">
                                <h4 class="font-bold text-gray-900 mb-1 truncate">{{ $product->title }}</h4>
                                <div class="text-blue-600 font-mono text-sm font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                             </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Posts Results -->
        @if($posts->isNotEmpty())
            <div class="mb-12">
                <h3 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">Postingan ({{ $posts->count() }})</h3>
                <div class="space-y-4">
                    @foreach($posts as $post)
                        <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-shadow">
                            <a href="{{ route('posts.show', $post->id) }}" class="block">
                                <h4 class="font-bold text-gray-900 mb-1">{{ $post->title }}</h4>
                                <p class="text-gray-600 text-sm line-clamp-2">{!! strip_tags($post->content) !!}</p>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Videos Results -->
        @if($videos->isNotEmpty())
            <div class="mb-12">
                <h3 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">Video ({{ $videos->count() }})</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($videos as $video)
                        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                            <div class="aspect-w-16 aspect-h-9 bg-black">
                                <iframe src="{{ $video->url }}" width="100%" height="200" frameborder="0" allowfullscreen></iframe>
                            </div>
                            <div class="p-4">
                                <h4 class="font-bold text-gray-900 truncate">{{ $video->title }}</h4>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif
</div>
@endsection
