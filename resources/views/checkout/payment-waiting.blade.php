@extends('layouts.app')

@section('title', 'Menunggu Pembayaran')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-lg">
    <div id="paymentWaiting" class="bg-white rounded-lg shadow-lg p-8 text-center">
        <div id="waitingState">
            <div class="animate-pulse mb-6">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <h2 class="text-xl font-bold mb-2">Menunggu Pembayaran</h2>
            <p class="text-gray-600 mb-4">Order #{{ $order->invoice_number }}</p>
            <p class="text-2xl font-bold text-blue-600 mb-4">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
            
            <div id="countdown" class="mb-6">
                <p class="text-sm text-gray-500">Sisa waktu:</p>
                <p id="timer" class="text-lg font-mono font-bold text-red-600">--:--</p>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <p class="text-sm text-gray-600">Status: <span id="statusText" class="font-semibold">Menunggu...</span></p>
                <div class="mt-2 h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div id="progressBar" class="h-full bg-blue-500 transition-all duration-300" style="width: 0%"></div>
                </div>
            </div>

            @if(isset($qrDataUri) && $qrDataUri)
                <div class="mb-4">
                    <img src="{{ $qrDataUri }}" alt="QRIS" class="mx-auto" style="max-width: 300px;">
                    <p class="text-sm text-gray-500 mt-2">Scan QR Code untuk membayar</p>
                </div>
            @elseif(isset($paymentUrl) && $paymentUrl && !($isQris ?? false))
                <div class="mb-4">
                    <p class="text-lg font-mono font-bold tracking-wider">{{ $paymentUrl }}</p>
                    <p class="text-sm text-gray-500 mt-2">Gunakan nomor di atas untuk transfer</p>
                </div>
            @endif
        </div>

        <div id="successState" class="hidden">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-green-600 mb-2">Pembayaran Berhasil!</h2>
            <p class="text-gray-600 mb-4">Terima kasih atas pembayaran Anda.</p>
            <a href="{{ route('orders.show', $order->id) }}" class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700">
                Lihat Detail Pesanan
            </a>
        </div>

        <div id="expiredState" class="hidden">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-red-600 mb-2">Pembayaran Kedaluwarsa</h2>
            <p class="text-gray-600 mb-4">Waktu pembayaran telah habis. Silakan buat pesanan baru.</p>
            <a href="{{ route('cart.index') }}" class="inline-block bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-700">
                Kembali ke Keranjang
            </a>
        </div>
    </div>
</div>

<script type="module">
import PaymentStatusPoller from '/js/payment-status-poller.js';

const orderId = {{ $order->id }};
const expirySeconds = {{ $expirySeconds ?? 900 }};
let remainingSeconds = expirySeconds;

// Countdown timer
const timerEl = document.getElementById('timer');
const countdownInterval = setInterval(() => {
    remainingSeconds--;
    if (remainingSeconds <= 0) {
        clearInterval(countdownInterval);
        timerEl.textContent = '00:00';
        return;
    }
    const mins = Math.floor(remainingSeconds / 60);
    const secs = remainingSeconds % 60;
    timerEl.textContent = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
}, 1000);

// Status poller
const poller = new PaymentStatusPoller(orderId, {
    interval: 5000,
    maxAttempts: Math.ceil(expirySeconds / 5),
    
    onStatusChange(data) {
        document.getElementById('statusText').textContent = data.order_status || data.status;
        
        if (data.remaining_seconds) {
            remainingSeconds = data.remaining_seconds;
        }

        if (data.order_status === 'completed' || data.status === 'SUCCESS') {
            showState('success');
            clearInterval(countdownInterval);
        }
    },

    onExpiry(data) {
        showState('expired');
        clearInterval(countdownInterval);
    },

    onError(error) {
        console.error('Poll error:', error);
    }
});

// Update progress bar
setInterval(() => {
    const progress = poller.getProgress();
    document.getElementById('progressBar').style.width = `${progress.percentage}%`;
}, 5000);

poller.start();

function showState(state) {
    document.getElementById('waitingState').classList.add('hidden');
    document.getElementById('successState').classList.add('hidden');
    document.getElementById('expiredState').classList.add('hidden');
    document.getElementById(`${state}State`).classList.remove('hidden');
    poller.stop();
}
</script>
@endsection
