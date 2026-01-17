@extends('layouts.app')

@section('title', 'Keranjang Belanja - Shyness OS')

@section('content')
<div class="max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
        <i class="fa fa-shopping-cart mr-3"></i> Keranjang Belanja
    </h2>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            {{ session('error') }}
        </div>
    @endif

    @if($cartItems->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Produk</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Harga</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Jumlah</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Subtotal</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php $total = 0; @endphp
                        @foreach($cartItems as $item)
                            @php 
                                $subtotal = $item->product->price * $item->quantity;
                                $total += $subtotal;
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                            <img class="h-full w-full object-cover" src="{{ asset('storage/products/'.$item->product->image) }}" alt="{{ $item->product->title }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="font-medium text-gray-900">{{ $item->product->title }}</div>
                                            <div class="text-sm text-gray-500">{{ $item->product->category->name ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">
                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex justify-center items-center space-x-2">
                                        @csrf @method('PUT')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}"
                                               class="w-16 px-2 py-1 border border-gray-300 rounded-md text-center focus:ring-black focus:border-black sm:text-sm">
                                        <button type="submit" class="text-gray-400 hover:text-blue-600">
                                            <i class="fa fa-sync-alt"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-bold text-gray-900">
                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 transition-colors" onclick="return confirm('Hapus item ini?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-6 bg-gray-50 border-t border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-lg font-bold text-gray-900">
                    Total: <span class="text-2xl ml-2">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <!-- Checkout Button - Will link to Checkout page later -->
                <a href="{{ route('orders.checkout') }}" class="inline-flex items-center px-8 py-3 bg-gray-900 text-white font-bold rounded-xl hover:bg-black transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fa fa-credit-card mr-2"></i> Checkout
                </a>
            </div>
        </div>
    @else
        <div class="text-center py-12 bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="text-gray-300 text-6xl mb-4"><i class="fa fa-shopping-cart"></i></div>
            <h3 class="text-lg font-medium text-gray-900">Keranjang Belanja Kosong</h3>
            <p class="text-gray-500 mt-2 mb-6">Anda belum menambahkan produk apapun.</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                Mulai Belanja
            </a>
        </div>
    @endif
</div>
@endsection
