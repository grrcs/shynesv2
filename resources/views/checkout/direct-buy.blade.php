@extends('layouts.app')

@section('title', 'Beli Langsung - Shyness')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-12 border-b border-thin dark:border-gray-800 pb-6 text-center transition-colors">
        <h1 class="text-3xl font-serif font-medium text-primary dark:text-white mb-2 transition-colors">Beli Langsung</h1>
        <p class="text-xs tracking-widest uppercase text-secondary">Lengkapi detail pesanan Anda</p>
    </div>

    @php
        $discountActive = $product->is_discount_active && $product->discount_price;
        $finalPrice = $discountActive ? $product->discount_price : $product->price;
    @endphp

    <!-- Product Summary -->
    <div class="bg-gray-50 dark:bg-[#151515] border border-thin dark:border-gray-800 rounded-xl p-6 mb-8 flex items-center gap-6">
        <div class="w-20 h-24 bg-gray-100 dark:bg-gray-800 overflow-hidden shrink-0">
            <img src="{{ $product->image_url }}" alt="{{ $product->title }}" class="w-full h-full object-cover">
        </div>
        <div class="flex-grow">
            <h3 class="font-bold text-primary dark:text-white">{{ $product->title }}</h3>
            <p class="text-xs text-secondary mt-1">{{ $product->category->name ?? 'Essentials' }}</p>
            <div class="mt-2 flex items-center gap-3">
                <span class="font-bold text-lg text-primary dark:text-white">
                    Rp {{ number_format($finalPrice, 0, ',', '.') }}
                </span>
                @if($discountActive)
                    <span class="text-xs text-secondary line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                @endif
                <span class="text-xs text-secondary">x{{ $priceToUse == $finalPrice ? request()->query('quantity', 1) : 1 }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Address & Payment Options -->
        <div class="md:col-span-2 space-y-10">
            <!-- Shipping Address -->
            <div>
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-thin dark:border-gray-800 transition-colors">
                    <h3 class="text-xs tracking-widest uppercase font-medium text-primary dark:text-gray-300">Alamat Pengiriman</h3>
                    <a href="{{ route('addresses.create') }}" class="text-xs tracking-widest uppercase text-secondary hover:text-black dark:hover:text-white transition-colors">
                        <i class="fa w-4 fa-plus"></i> Tambah Alamat
                    </a>
                </div>

                @if($addresses->isEmpty())
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-500 p-4 rounded-lg text-center">
                        <p class="text-red-700 dark:text-red-300 text-sm mb-2">Anda belum menambahkan alamat pengiriman.</p>
                        <a href="{{ route('addresses.create') }}" class="inline-block mt-2 px-4 py-2 bg-primary text-white dark:bg-white dark:text-primary text-xs font-bold rounded">
                            Tambah Alamat Sekarang
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($addresses as $address)
                            <label class="block cursor-pointer group">
                                <input type="radio" name="address_id" value="{{ $address->id }}"
                                       class="sr-only address-radio"
                                       {{ $address->is_primary ? 'checked' : '' }}>
                                <div class="p-5 bg-white dark:bg-primary border border-gray-200 dark:border-gray-800 rounded-xl transition-all duration-300 group-has-[:checked]:border-black group-has-[:checked]:ring-1 group-has-[:checked]:ring-black group-has-[:checked]:bg-gray-50 dark:group-has-[:checked]:border-white dark:group-has-[:checked]:ring-white dark:group-has-[:checked]:bg-[#151515]">
                                    <div class="flex items-start gap-4">
                                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center mt-0.5 transition-colors group-has-[:checked]:border-black dark:group-has-[:checked]:border-white">
                                            <div class="w-2.5 h-2.5 rounded-full bg-black dark:bg-white scale-0 transition-transform duration-300 group-has-[:checked]:scale-100"></div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <h4 class="font-bold text-primary dark:text-white text-sm capitalize">{{ $address->label }}</h4>
                                                @if($address->is_primary)
                                                    <span class="bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 text-[10px] px-2 py-0.5 rounded font-bold uppercase tracking-widest">Utama</span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-primary dark:text-gray-200 font-medium mt-1">{{ $address->recipient_name }} | {{ $address->phone_number }}</p>
                                            <p class="text-xs text-secondary dark:text-gray-400 mt-1 line-clamp-2 leading-relaxed">{{ $address->full_address }}, {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Payment Options -->
            <div>
                <h3 class="text-xs tracking-widest uppercase font-medium text-primary dark:text-gray-300 mb-6 pb-4 border-b border-thin dark:border-gray-800 transition-colors">Metode Pembayaran</h3>

                @if($paymentOptions->isEmpty())
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-500 p-4 rounded-lg text-center">
                        <p class="text-yellow-700 dark:text-yellow-300">Tidak ada opsi pembayaran yang tersedia saat ini.</p>
                    </div>
                @else
                    <form id="paymentForm">
                        @csrf
                        <div class="space-y-4">
                            @foreach($paymentOptions as $option)
                                <label class="payment-option-label block cursor-pointer group">
                                    <input type="radio" name="payment_option_id" value="{{ $option->id }}"
                                           class="sr-only"
                                           data-tax="{{ $option->tax_percentage }}"
                                           {{ $loop->first ? 'checked' : '' }}>
                                    <div class="p-6 bg-white dark:bg-primary border border-gray-200 dark:border-gray-800 rounded-xl transition-all duration-300 group-has-[:checked]:border-black group-has-[:checked]:ring-1 group-has-[:checked]:ring-black group-has-[:checked]:bg-gray-50 dark:group-has-[:checked]:border-white dark:group-has-[:checked]:ring-white dark:group-has-[:checked]:bg-[#151515]">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-start gap-4">
                                                <div class="w-6 h-6 rounded-full border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center mt-1 transition-colors group-has-[:checked]:border-black dark:group-has-[:checked]:border-white">
                                                    <div class="w-3 h-3 rounded-full bg-black dark:bg-white scale-0 transition-transform duration-300 group-has-[:checked]:scale-100"></div>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-primary dark:text-white text-lg">{{ $option->name }}</h4>
                                                    @if($option->description)
                                                        <p class="text-sm text-secondary dark:text-gray-400 mt-1">{{ $option->description }}</p>
                                                    @endif
                                                    @if($option->tax_percentage > 0)
                                                        <p class="text-xs text-red-500 dark:text-red-400 mt-2">
                                                            <i class="fa fa-info-circle mr-1"></i>
                                                            Biaya tambahan: {{ number_format($option->tax_percentage, 2) }}%
                                                        </p>
                                                    @else
                                                        <p class="text-xs text-green-500 dark:text-green-400 mt-2">
                                                            <i class="fa fa-check-circle mr-1"></i>
                                                            Tanpa biaya tambahan
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </form>
                @endif
            </div>
        </div>

        <!-- Order Summary -->
        <div class="md:col-span-1">
            <h3 class="text-xs tracking-widest uppercase font-medium text-primary dark:text-gray-300 mb-6 pb-4 border-b border-thin dark:border-gray-800 transition-colors">Ringkasan Pesanan</h3>

            <div class="bg-white dark:bg-primary border border-thin dark:border-gray-800 rounded-xl p-6 sticky top-8">
                <!-- Coupon Input -->
                <div class="mb-6 pb-6 border-b border-thin dark:border-gray-800">
                    <div class="flex gap-2">
                        <input type="text" id="couponCode" name="coupon_code" placeholder="Masukkan kode kupon"
                               class="flex-1 px-4 py-3 text-sm border border-gray-200 dark:border-gray-800 rounded-lg bg-gray-50 dark:bg-[#151515] text-primary dark:text-white placeholder-secondary focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-white">
                        <button type="button" id="applyCouponBtn"
                                class="px-6 py-3 text-xs tracking-widest uppercase font-medium text-white bg-primary dark:bg-white dark:text-primary hover:bg-black dark:hover:bg-gray-200 transition-colors">
                            Terapkan
                        </button>
                    </div>
                    <div id="couponMessage" class="mt-2 text-xs hidden"></div>
                    <input type="hidden" name="coupon_code" id="appliedCouponCode">
                </div>

                <div class="space-y-4 mb-6 text-sm">
                    <div class="flex justify-between">
                        <span class="text-secondary dark:text-gray-400">Subtotal</span>
                        <span class="font-medium text-primary dark:text-white">Rp <span id="subtotal">{{ number_format($subtotal, 0, ',', '.') }}</span></span>
                    </div>
                    <div id="couponDiscountRow" class="flex justify-between text-green-600 dark:text-green-400 hidden">
                        <span>Diskon Kupon</span>
                        <span>- Rp <span id="couponDiscount">0</span></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-secondary dark:text-gray-400">Pajak & Biaya</span>
                        <span class="font-medium text-primary dark:text-white">Rp <span id="tax">0</span></span>
                    </div>
                    <div class="pt-4 border-t border-thin dark:border-gray-800">
                        <div class="flex justify-between text-base font-bold">
                            <span>Total</span>
                            <span>Rp <span id="total">{{ number_format($subtotal, 0, ',', '.') }}</span></span>
                        </div>
                    </div>
                </div>

                <!-- Confirm Button -->
                <form id="orderForm" action="{{ route('orders.directBuy') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="{{ request()->query('quantity', 1) }}">
                    <input type="hidden" name="payment_option_id" id="selectedPaymentOption">
                    <input type="hidden" name="address_id" id="selectedAddressOption">
                    <input type="hidden" name="coupon_code" id="couponCodeHidden">
                    <button type="submit" id="confirmPayment" class="w-full py-4 text-xs tracking-widest uppercase font-medium text-white bg-primary dark:bg-white dark:text-primary hover:bg-black dark:hover:bg-gray-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <i class="fa fa-lock mr-2"></i> Bayar Sekarang
                    </button>
                </form>

                <p class="text-xs text-secondary dark:text-gray-400 text-center mt-4">
                    <i class="fa fa-shield-alt mr-1"></i>
                    Pembayaran aman & terenkripsi
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const subtotalAmount = {{ $subtotal }};
    let appliedCoupon = null;
    let discountAmount = 0;

    function formatRupiah(amount) {
        return amount.toLocaleString('id-ID');
    }

    function updateOrderSummary() {
        const selectedOption = document.querySelector('input[name="payment_option_id"]:checked');
        if (!selectedOption) return;

        const taxPercentage = parseFloat(selectedOption.dataset.tax) || 0;
        const taxableAmount = subtotalAmount - discountAmount;
        const taxAmount = Math.max(0, taxableAmount * (taxPercentage / 100));
        const totalAmount = taxableAmount + taxAmount;

        document.getElementById('tax').textContent = formatRupiah(Math.round(taxAmount));
        document.getElementById('total').textContent = formatRupiah(Math.round(totalAmount));

        if (discountAmount > 0) {
            document.getElementById('couponDiscount').textContent = formatRupiah(discountAmount);
            document.getElementById('couponDiscountRow').classList.remove('hidden');
        } else {
            document.getElementById('couponDiscountRow').classList.add('hidden');
        }

        document.getElementById('confirmPayment').disabled = false;
    }

    document.getElementById('applyCouponBtn').addEventListener('click', function() {
        const couponCode = document.getElementById('couponCode').value.trim();
        const couponMessage = document.getElementById('couponMessage');

        if (!couponCode) {
            couponMessage.textContent = 'Masukkan kode kupon terlebih dahulu';
            couponMessage.className = 'mt-2 text-xs text-red-500';
            couponMessage.classList.remove('hidden');
            return;
        }

        this.disabled = true;
        this.textContent = 'Memproses...';

        fetch('{{ route("orders.applyCoupon") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                coupon_code: couponCode,
                subtotal: subtotalAmount
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                couponMessage.textContent = data.error;
                couponMessage.className = 'mt-2 text-xs text-red-500';
                couponMessage.classList.remove('hidden');
                appliedCoupon = null;
                discountAmount = 0;
                document.getElementById('appliedCouponCode').value = '';
                document.getElementById('couponCodeHidden').value = '';
            } else if (data.success) {
                couponMessage.textContent = `Kupon "${data.coupon.name}" berhasil diterapkan! Diskon: ${data.coupon.formatted_discount}`;
                couponMessage.className = 'mt-2 text-xs text-green-500';
                couponMessage.classList.remove('hidden');
                appliedCoupon = data.coupon;
                discountAmount = data.coupon.discount_amount;
                document.getElementById('appliedCouponCode').value = couponCode;
                document.getElementById('couponCodeHidden').value = couponCode;
                document.getElementById('couponCode').disabled = true;
                this.textContent = 'Terpakai';
                this.disabled = true;
            }
            updateOrderSummary();
        })
        .catch(error => {
            couponMessage.textContent = 'Terjadi kesalahan. Coba lagi.';
            couponMessage.className = 'mt-2 text-xs text-red-500';
            couponMessage.classList.remove('hidden');
        })
        .finally(() => {
            if (!appliedCoupon) {
                this.disabled = false;
                this.textContent = 'Terapkan';
            }
        });
    });

    updateOrderSummary();

    document.querySelectorAll('input[name="payment_option_id"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('selectedPaymentOption').value = this.value;
            updateOrderSummary();
        });
    });

    document.querySelectorAll('input[name="address_id"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('selectedAddressOption').value = this.value;
        });
    });

    const initialSelectedOption = document.querySelector('input[name="payment_option_id"]:checked');
    if (initialSelectedOption) {
        document.getElementById('selectedPaymentOption').value = initialSelectedOption.value;
    }

    const initialAddressOption = document.querySelector('input[name="address_id"]:checked');
    if (initialAddressOption) {
        document.getElementById('selectedAddressOption').value = initialAddressOption.value;
    }

    document.getElementById('orderForm').addEventListener('submit', function(e) {
        const selectedOption = document.querySelector('input[name="payment_option_id"]:checked');
        const selectedAddress = document.querySelector('input[name="address_id"]:checked');

        if (!selectedAddress) {
            e.preventDefault();
            alert('Silakan pilih atau tambahkan alamat pengiriman terlebih dahulu.');
            return;
        }

        if (!selectedOption) {
            e.preventDefault();
            alert('Silakan pilih metode pembayaran terlebih dahulu.');
            return;
        }
    });
</script>
@endpush
