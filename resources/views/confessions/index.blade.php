@extends('layouts.app')

@section('title', 'Confession / Menfess - Shyness OS')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="text-center mb-10">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Confession Wall</h2>
        <p class="text-gray-500">Kirim pesan rahasia atau salam-salam (Menfess) di sini. Aman & Anonim.</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 mb-12 transform hover:scale-[1.01] transition-transform">
        <div class="p-1 bg-gradient-to-r from-pink-500 via-red-500 to-yellow-500"></div>
        <div class="p-8">
            <h3 class="font-bold text-gray-900 mb-6 text-xl">Kirim Pesan Baru</h3>
            <form action="{{ route('confessions.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dari (Opsional)</label>
                    <input type="text" name="sender_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500" placeholder="Biarkan kosong untuk Anonim">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pesan Anda</label>
                    <textarea name="content" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500" placeholder="Tuliskan unek-unek atau salam mu di sini..." required></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-gray-900 text-white font-bold rounded-xl hover:bg-black transition-all shadow-lg hover:shadow-xl">
                        <i class="fa fa-paper-plane mr-2"></i> Kirim Menfess
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Feed -->
    <div class="space-y-6">
        @forelse($confessions as $confession)
            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <i class="fa fa-quote-right text-6xl text-gray-800"></i>
                </div>
                <p class="text-gray-800 text-lg mb-4 font-serif italic relative z-10">"{{ $confession->content }}"</p>
                <div class="flex items-center justify-between text-sm text-gray-500 border-t border-gray-100 pt-4 relative z-10">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-100 to-gray-300 flex items-center justify-center text-gray-600 font-bold">
                            {{ substr($confession->sender_name, 0, 1) }}
                        </div>
                        <span class="font-bold text-gray-900">{{ $confession->sender_name }}</span>
                    </div>
                    <span>{{ $confession->created_at->diffForHumans() }}</span>
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-gray-400">
                <i class="fa fa-ghost text-4xl mb-4"></i>
                <p>Belum ada confession. Jadilah yang pertama!</p>
            </div>
        @endforelse

        <div class="mt-8">
            {{ $confessions->links() }}
        </div>
    </div>
</div>
@endsection
