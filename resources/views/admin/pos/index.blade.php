@extends('layouts.app')

@section('title', 'POS Kasir - Shyness')

@push('styles')
<style>
    /* Premium POS UI Variables */
    :root {
        --pos-bg: #ffffff;
        --pos-outer-bg: #f8f8f8;
        --pos-border: #e5e5e5;
        --pos-text: #111111;
        --pos-text-muted: #777777;
        --pos-accent: #000000;
        --pos-tab-active-bg: #000000;
        --pos-tab-active-text: #ffffff;
        --pos-card-hover: #fcfcfc;
    }

    /* Fix: Correct selector for dark mode variables */
    html.dark {
        --pos-bg: #000000;
        --pos-outer-bg: #080808;
        --pos-border: #222222;
        --pos-text: #ffffff;
        --pos-text-muted: #888888;
        --pos-accent: #ffffff;
        --pos-tab-active-bg: #ffffff;
        --pos-tab-active-text: #000000;
        --pos-card-hover: #0a0a0a;
    }

    body { background-color: var(--pos-outer-bg) !important; color: var(--pos-text) !important; transition: background-color 0.3s, color 0.3s; }
    
    /* Override standard container for wider POS */
    main { max-width: 100% !important; padding: 2rem !important; }
    
    .pos-outer-wrapper {
        border: 1px solid var(--pos-border);
        border-radius: 4px;
        background-color: var(--pos-bg);
        display: flex;
        height: calc(100vh - 180px);
        overflow: hidden;
        transition: all 0.3s;
    }

    /* Left Sidebar */
    .pos-sidebar {
        width: 420px;
        border-right: 1px solid var(--pos-border);
        display: flex;
        flex-direction: column;
        padding: 24px;
        background-color: var(--pos-bg);
    }

    .warehouse-select {
        border: 1px solid var(--pos-border);
        padding: 12px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        cursor: pointer;
        font-size: 13px;
        color: var(--pos-text);
    }

    .cart-table-head {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--pos-border);
        margin-bottom: 8px;
    }

    .cart-table-head span {
        font-size: 10px;
        letter-spacing: 0.15em;
        color: var(--pos-text-muted);
        text-transform: uppercase;
        font-weight: 500;
    }

    .cart-scroll {
        flex: 1;
        overflow-y: auto;
    }

    .cart-row {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        align-items: center;
        padding: 20px 0;
        border-bottom: 1px solid var(--pos-border);
    }

    .row-meta { display: flex; flex-direction: column; gap: 4px; }
    .row-id { font-size: 13px; font-weight: 700; color: var(--pos-text); }
    .row-name { font-size: 11px; color: var(--pos-text-muted); }
    .row-price { font-size: 13px; }

    .row-qty-ctrl {
        display: flex;
        align-items: center;
        border: 1px solid var(--pos-border);
        width: fit-content;
    }

    .qty-btn {
        background: none;
        border: none;
        color: var(--pos-text);
        width: 32px;
        height: 32px;
        cursor: pointer;
        font-size: 14px;
    }
    .qty-btn-minus { border-right: 1px solid var(--pos-border); }
    .qty-btn-plus { border-left: 1px solid var(--pos-border); }

    .qty-display {
        width: 30px;
        text-align: center;
        font-size: 13px;
    }

    .row-subtotal-box {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
    }
    .row-subtotal { font-size: 13px; font-weight: 500; }
    .remove-item {
        border: 1px solid var(--pos-border);
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--pos-text);
        font-size: 10px;
        background: none;
    }

    .grand-total-box {
        margin-top: 24px;
        border: 1px solid var(--pos-border);
        padding: 16px;
        text-align: center;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        cursor: pointer;
    }

    /* Right Content */
    .pos-main {
        flex: 1;
        padding: 24px;
        display: flex;
        flex-direction: column;
        background-color: var(--pos-bg);
    }

    .tab-row {
        display: flex;
        margin-bottom: 32px;
    }

    .pos-tab {
        padding: 14px 40px;
        font-size: 11px;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        border: 1px solid var(--pos-border);
        background: none;
        color: var(--pos-text);
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s;
    }

    .pos-tab.active {
        background-color: var(--pos-tab-active-bg);
        color: var(--pos-tab-active-text);
        border-color: var(--pos-tab-active-bg);
    }

    .product-header-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .product-header-line h2 {
        font-size: 12px;
        letter-spacing: 0.25em;
        text-transform: uppercase;
        font-weight: 600;
    }

    .search-wrapper {
        position: relative;
        width: 380px;
    }

    .search-wrapper i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--pos-text-muted);
        font-size: 14px;
    }

    .pos-search-input {
        width: 100%;
        background: transparent;
        border: 1px solid var(--pos-border);
        padding: 12px 16px 12px 42px;
        color: var(--pos-text);
        font-size: 13px;
        outline: none;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        overflow-y: auto;
    }

    .p-card {
        border: 1px solid var(--pos-border);
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 16px;
        cursor: pointer;
        transition: 0.2s;
        background-color: var(--pos-bg);
    }

    .p-card:hover { 
        border-color: var(--pos-accent);
        background-color: var(--pos-card-hover);
    }

    .p-img {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .p-img img { width: 100%; height: 100%; object-fit: contain; }

    .p-meta { display: flex; flex-direction: column; gap: 6px; }
    .p-name { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
    .p-id { font-size: 11px; color: var(--pos-text-muted); margin-bottom: 10px; }
    
    .p-price {
        border: 1px solid var(--pos-border);
        padding: 8px 12px;
        font-size: 11px;
        color: var(--pos-text);
        width: fit-content;
    }

    /* Checkout Modal */
    .checkout-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        backdrop-filter: blur(5px);
    }

    html.dark .checkout-overlay { background: rgba(0,0,0,0.85); }

    .checkout-box {
        background: var(--pos-bg);
        border: 1px solid var(--pos-border);
        width: 480px;
        padding: 40px;
    }

    .checkout-title {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.3em;
        text-transform: uppercase;
        text-align: center;
        margin-bottom: 40px;
    }

    .checkout-input {
        width: 100%;
        background: transparent;
        border-bottom: 1px solid var(--pos-border);
        padding: 12px 0;
        color: var(--pos-text);
        font-size: 12px;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        outline: none;
        margin-bottom: 24px;
    }

    .checkout-total-line {
        display: flex;
        justify-content: space-between;
        padding: 24px 0;
        border-top: 1px solid var(--pos-border);
        margin-top: 16px;
    }
    .total-label { font-size: 10px; letter-spacing: 0.1em; color: var(--pos-text-muted); text-transform: uppercase; }
    .total-value { font-size: 14px; font-weight: 700; }

    .checkout-actions {
        display: flex;
        gap: 16px;
        margin-top: 16px;
    }

    .btn-batal {
        flex: 1;
        border: 1px solid var(--pos-border);
        padding: 14px;
        color: var(--pos-text);
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        background: none;
    }

    .btn-bayar {
        flex: 1;
        background: var(--pos-tab-active-bg);
        color: var(--pos-tab-active-text);
        padding: 14px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        border: none;
    }

    /* Custom Scrollbar */
    .cart-scroll::-webkit-scrollbar, .product-grid::-webkit-scrollbar { width: 4px; }
    .cart-scroll::-webkit-scrollbar-thumb, .product-grid::-webkit-scrollbar-thumb { background: var(--pos-border); }

    /* Receipt Print Styles */
    .receipt-container {
        width: 302px; /* 80mm at 96dpi */
        margin: 0 auto;
        padding: 20px;
        font-family: 'Courier New', monospace;
        font-size: 12px;
        line-height: 1.4;
        background: white;
        color: black;
    }
    
    .receipt-header {
        text-align: center;
        border-bottom: 2px dashed #000;
        padding-bottom: 10px;
        margin-bottom: 10px;
    }
    
    .receipt-title {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 4px;
    }
    
    .receipt-info {
        margin: 10px 0;
        font-size: 11px;
    }
    
    .receipt-items {
        border-top: 1px dashed #000;
        border-bottom: 1px dashed #000;
        padding: 10px 0;
        margin: 10px 0;
    }
    
    .receipt-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 4px;
    }
    
    .receipt-total {
        font-size: 14px;
        font-weight: bold;
        text-align: right;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 2px solid #000;
    }
    
    .receipt-footer {
        text-align: center;
        margin-top: 20px;
        padding-top: 10px;
        border-top: 2px dashed #000;
    }

    @media print {
        body * {
            visibility: hidden;
        }
        #receipt-modal, #receipt-modal * {
            visibility: visible;
        }
        #receipt-modal {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .receipt-actions {
            display: none !important;
        }
        .checkout-overlay {
            background: white !important;
        }
        .checkout-box {
            box-shadow: none !important;
            border: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="pos-outer-wrapper">
    <!-- Sidebar -->
    <aside class="pos-sidebar">
        <div class="warehouse-select">
            <span>Gudang Utama</span>
            <i class="fa-solid fa-chevron-down"></i>
        </div>

        <div class="cart-table-head">
            <span>PRODUK</span>
            <span>HARGA</span>
            <span>QTY</span>
            <span style="text-align: right;">SUBTOTAL</span>
        </div>

        <div class="cart-scroll" id="cart-container">
            <!-- Items injected by JS -->
        </div>

        <div class="grand-total-box" onclick="showCheckout()">
            GRAND TOTAL: RP. <span id="grand-total-text">0</span>
        </div>
    </aside>

    <!-- Main -->
    <main class="pos-main">
        <div class="tab-row">
            <button class="pos-tab active">PRODUK</button>
            <button class="pos-tab" onclick="toastr.info('Coming Soon')">TAGIHAN</button>
            <button class="pos-tab" onclick="toastr.info('Coming Soon')">TIKET</button>
        </div>

        <div class="product-header-line">
            <h2>PRODUK</h2>
            <div class="search-wrapper">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="p-search" class="pos-search-input" placeholder="Cari nama atau id produk...">
            </div>
        </div>

        <div class="product-grid">
            @foreach($products as $product)
                <div class="p-card" onclick="addToPosCart({{ $product->id }}, '{{ $product->title }}', {{ $product->is_discount_active && $product->discount_price ? $product->discount_price : $product->price }}, '{{ asset('storage/products/'.$product->image) }}', '{{ $product->sku ?? $product->id }}')">
                    <div class="p-img">
                        <img src="{{ asset('storage/products/'.$product->image) }}" alt="{{ $product->title }}">
                    </div>
                    <div class="p-meta">
                        <span class="p-name">{{ $product->title }}</span>
                        <span class="p-id">{{ $product->sku ?? $product->id }}</span>
                        <div class="p-price">RP. {{ number_format($product->is_discount_active && $product->discount_price ? $product->discount_price : $product->price, 0, ',', '.') }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </main>
</div>

<!-- Modal -->
<div id="checkout-modal" class="checkout-overlay">
    <div class="checkout-box">
        <h3 class="checkout-title">CHECKOUT</h3>
        
        <input type="text" id="c-name" class="checkout-input" placeholder="NAMA CUSTOMER">
        <input type="text" id="c-phone" class="checkout-input" placeholder="NOMOR TELEPON">
        <select id="c-pay" class="checkout-input">
            <option value="">PILIH PEMBAYARAN</option>
            @foreach($paymentOptions as $opt)
                <option value="{{ $opt->id }}">{{ strtoupper($opt->name) }}</option>
            @endforeach
        </select>

        <div class="checkout-total-line">
            <span class="total-label">TOTAL</span>
            <span class="total-value" id="modal-total-text">RP. 0</span>
        </div>

        <div class="checkout-actions">
            <button onclick="hideCheckout()" class="btn-batal">BATAL</button>
            <button onclick="confirmCheckout()" class="btn-bayar">BAYAR</button>
        </div>
    </div>
</div>

<!-- Payment Waiting Modal -->
<div id="payment-waiting-modal" class="checkout-overlay" style="display: none;">
    <div class="checkout-box" style="max-width: 500px;">
        <h3 class="checkout-title">MENUNGGU PEMBAYARAN</h3>
        
        <!-- QRIS Section -->
        <div id="qris-section" style="display: none; text-align: center;">
            <p style="margin-bottom: 16px; font-size: 13px;">Scan QR Code untuk membayar</p>
            <img id="qris-image" src="" style="max-width: 300px; margin: 0 auto; display: block;">
        </div>
        
        <!-- VA Section -->
        <div id="va-section" style="display: none;">
            <p style="font-size: 13px; margin-bottom: 12px;">Transfer ke:</p>
            <div style="border: 1px solid var(--pos-border); padding: 16px; margin: 16px 0;">
                <p style="margin-bottom: 8px;"><strong>Bank:</strong> <span id="va-bank">BCA</span></p>
                <p style="margin-bottom: 8px;"><strong>No. Rekening:</strong> <span id="va-number">-</span></p>
                <p><strong>Jumlah:</strong> <span id="va-amount">Rp 0</span></p>
            </div>
        </div>
        
        <!-- Countdown Timer -->
        <div style="text-align: center; margin: 24px 0;">
            <p style="font-size: 12px; color: var(--pos-text-muted);">Waktu tersisa:</p>
            <p id="countdown-timer" style="font-size: 24px; font-weight: 700;">15:00</p>
        </div>
        
        <!-- Status Message -->
        <p id="payment-status-message" style="text-align: center; margin: 16px 0; font-size: 13px;"></p>
        
        <!-- Actions -->
        <div style="display: flex; gap: 16px; margin-top: 24px;">
            <button onclick="checkPaymentManually()" class="btn-batal" style="flex: 1;">CEK STATUS</button>
            <button onclick="closePaymentWaiting()" class="btn-batal" style="flex: 1;">BATAL</button>
        </div>
    </div>
</div>

<!-- Cash Confirmation Modal -->
<div id="cash-confirm-modal" class="checkout-overlay" style="display: none;">
    <div class="checkout-box" style="max-width: 400px;">
        <h3 class="checkout-title">KONFIRMASI PEMBAYARAN TUNAI</h3>
        
        <div style="text-align: center; margin: 32px 0;">
            <p style="font-size: 14px; color: var(--pos-text-muted); margin-bottom: 16px;">Total Pembayaran</p>
            <p id="cash-total-amount" style="font-size: 36px; font-weight: 700;">Rp 0</p>
        </div>
        
        <div style="display: flex; gap: 16px; margin-top: 32px;">
            <button onclick="cancelCashConfirm()" class="btn-batal" style="flex: 1;">BATAL</button>
            <button onclick="confirmCashReceived()" class="btn-bayar" style="flex: 1;">UANG DITERIMA</button>
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div id="receipt-modal" class="checkout-overlay" style="display: none;">
    <div class="checkout-box" style="max-width: 400px;">
        <div class="receipt-container">
            <div class="receipt-header">
                <div class="receipt-title">SHYNESS STORE</div>
                <div style="font-size: 10px;">Jl. Contoh No. 123, Jakarta</div>
                <div style="font-size: 10px;">Telp: 021-12345678</div>
            </div>
            
            <div class="receipt-info">
                <div>No: <span id="receipt-invoice">-</span></div>
                <div>Tanggal: <span id="receipt-date">-</span></div>
                <div>Kasir: <span id="receipt-cashier">Admin</span></div>
                <div>Customer: <span id="receipt-customer">-</span></div>
            </div>
            
            <div class="receipt-items">
                <div style="display: flex; justify-content: space-between; font-weight: bold; margin-bottom: 8px;">
                    <span>Item</span>
                    <span>Subtotal</span>
                </div>
                <div id="receipt-items-list"></div>
            </div>
            
            <div class="receipt-total">
                <div style="display: flex; justify-content: space-between;">
                    <span>TOTAL:</span>
                    <span id="receipt-total-amount">Rp 0</span>
                </div>
                <div style="font-size: 11px; font-weight: normal; margin-top: 4px;">
                    Pembayaran: <span id="receipt-payment-type">-</span>
                </div>
            </div>
            
            <div class="receipt-footer">
                <div style="font-size: 14px; font-weight: bold;">TERIMA KASIH</div>
                <div style="font-size: 10px; margin-top: 4px;">Barang yang sudah dibeli tidak dapat dikembalikan</div>
            </div>
        </div>
        
        <div class="receipt-actions" style="display: flex; gap: 16px; margin-top: 24px;">
            <button onclick="printReceipt()" class="btn-bayar" style="flex: 1;">PRINT</button>
            <button onclick="closeReceiptAndReload()" class="btn-batal" style="flex: 1;">SELESAI</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let posCart = [];

    function f(n) { return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); }

    function addToPosCart(id, name, price, img, sku) {
        let i = posCart.find(x => x.id === id);
        if (i) i.q++;
        else posCart.push({id, name, price, img, sku, q: 1});
        render();
    }

    function updatePosQty(id, d) {
        let i = posCart.find(x => x.id === id);
        if (i) {
            i.q += d;
            if (i.q < 1) posCart = posCart.filter(x => x.id !== id);
        }
        render();
    }

    function removeFromPosCart(id) {
        posCart = posCart.filter(x => x.id !== id);
        render();
    }

    function render() {
        let c = document.getElementById('cart-container');
        let html = '';
        let t = 0;
        posCart.forEach(i => {
            let s = i.price * i.q;
            t += s;
            html += `
                <div class="cart-row">
                    <div class="row-meta">
                        <span class="row-id">${i.sku}</span>
                        <span class="row-name">${i.name}</span>
                    </div>
                    <span class="row-price">Rp. ${f(i.price)}</span>
                    <div class="row-qty-ctrl">
                        <button class="qty-btn qty-btn-minus" onclick="updatePosQty(${i.id}, -1)">-</button>
                        <span class="qty-display">${i.q}</span>
                        <button class="qty-btn qty-btn-plus" onclick="updatePosQty(${i.id}, 1)">+</button>
                    </div>
                    <div class="subtotal-container">
                        <span class="row-subtotal">Rp. ${f(s)}</span>
                        <button class="remove-item" onclick="removeFromPosCart(${i.id})">×</button>
                    </div>
                </div>
            `;
        });
        c.innerHTML = html;
        document.getElementById('grand-total-text').innerText = f(t);
        document.getElementById('modal-total-text').innerText = 'RP. ' + f(t);
    }

    function showCheckout() { if(posCart.length) document.getElementById('checkout-modal').style.display = 'flex'; }
    function hideCheckout() { document.getElementById('checkout-modal').style.display = 'none'; }

    async function confirmCheckout() {
        let n = document.getElementById('c-name').value;
        let p = document.getElementById('c-phone').value;
        let y = document.getElementById('c-pay').value;
        if (!n || !p || !y) return toastr.error('Lengkapi data');

        let b = event.target;
        b.disabled = true; b.innerText = 'PROSES...';

        try {
            let r = await fetch('{{ route("admin.pos.checkout") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({
                    customer_name: n, customer_phone: p, payment_option_id: y,
                    items: posCart.map(x => ({ 
                        product_id: x.id, 
                        quantity: parseInt(x.q),
                        product_variant_id: x.variant_id || null
                    }))
                })
            });
            let d = await r.json();
            if (d.success) {
                toastr.success('Berhasil');
                hideCheckout(); // Close checkout modal first
                
                // Route based on payment type
                if (d.payment_type === 'cash' || d.payment_type === 'cod') {
                    showCashConfirmModal(d);
                } else if (d.payment_url) {
                    showPaymentWaitingModal(d);
                } else {
                    showReceiptModal(d);
                }
            } else { toastr.error(d.message); b.disabled = false; b.innerText = 'BAYAR'; }
        } catch(e) { toastr.error('Error'); b.disabled = false; b.innerText = 'BAYAR'; }
    }

    document.getElementById('p-search').addEventListener('input', (e) => {
        let q = e.target.value.toLowerCase();
        document.querySelectorAll('.p-card').forEach(x => {
            let n = x.querySelector('.p-name').innerText.toLowerCase();
            let id = x.querySelector('.p-id').innerText.toLowerCase();
            x.style.display = (n.includes(q) || id.includes(q)) ? 'flex' : 'none';
        });
    });

    // Cash Confirmation Modal Implementation
    function showCashConfirmModal(data) {
        currentOrderData = data;
        document.getElementById('cash-total-amount').innerText = 'Rp ' + f(data.total);
        document.getElementById('cash-confirm-modal').style.display = 'flex';
    }

    function cancelCashConfirm() {
        document.getElementById('cash-confirm-modal').style.display = 'none';
        currentOrderData = null;
    }

    function confirmCashReceived() {
        document.getElementById('cash-confirm-modal').style.display = 'none';
        if (currentOrderData) {
            showReceiptModal(currentOrderData);
        }
    }

    // Payment Waiting Modal Implementation
    let paymentPollingInterval = null;
    let countdownInterval = null;
    let currentOrderData = null;

    function showPaymentWaitingModal(data) {
        currentOrderData = data;
        const modal = document.getElementById('payment-waiting-modal');
        
        // Show appropriate section
        if (data.payment_type === 'QRIS') {
            document.getElementById('qris-section').style.display = 'block';
            document.getElementById('va-section').style.display = 'none';
            document.getElementById('qris-image').src = data.payment_url;
        } else if (data.payment_type === 'VA') {
            document.getElementById('qris-section').style.display = 'none';
            document.getElementById('va-section').style.display = 'block';
            document.getElementById('va-bank').innerText = 'BCA';
            document.getElementById('va-number').innerText = data.payment_token || 'N/A';
            document.getElementById('va-amount').innerText = 'Rp ' + f(data.total);
        }
        
        modal.style.display = 'flex';
        
        // Start countdown (15 minutes = 900 seconds)
        startCountdown(900);
        
        // Start polling
        startPaymentPolling(data.order_id);
    }

    function startCountdown(seconds) {
        let remaining = seconds;
        const timerEl = document.getElementById('countdown-timer');
        
        countdownInterval = setInterval(() => {
            remaining--;
            const mins = Math.floor(remaining / 60);
            const secs = remaining % 60;
            timerEl.innerText = `${mins}:${secs.toString().padStart(2, '0')}`;
            
            if (remaining <= 0) {
                clearInterval(countdownInterval);
                document.getElementById('payment-status-message').innerText = 'Pembayaran expired';
                stopPaymentPolling();
            }
        }, 1000);
    }

    function startPaymentPolling(orderId) {
        // Poll every 3 seconds
        paymentPollingInterval = setInterval(async () => {
            try {
                const response = await fetch(`/admin/pos/check-payment/${orderId}`);
                const data = await response.json();
                
                if (data.success && data.status === 'paid') {
                    stopPaymentPolling();
                    closePaymentWaiting();
                    showReceiptModal(currentOrderData);
                }
            } catch (e) {
                console.error('Polling error:', e);
            }
        }, 3000);
    }

    function stopPaymentPolling() {
        if (paymentPollingInterval) {
            clearInterval(paymentPollingInterval);
            paymentPollingInterval = null;
        }
        if (countdownInterval) {
            clearInterval(countdownInterval);
            countdownInterval = null;
        }
    }

    function closePaymentWaiting() {
        stopPaymentPolling();
        document.getElementById('payment-waiting-modal').style.display = 'none';
    }

    async function checkPaymentManually() {
        if (!currentOrderData) return;
        
        try {
            const response = await fetch(`/admin/pos/check-payment/${currentOrderData.order_id}`);
            const data = await response.json();
            
            if (data.success && data.status === 'paid') {
                stopPaymentPolling();
                closePaymentWaiting();
                showReceiptModal(currentOrderData);
            } else {
                document.getElementById('payment-status-message').innerText = 'Pembayaran belum diterima';
            }
        } catch (e) {
            toastr.error('Gagal mengecek status pembayaran');
        }
    }

    function showReceiptModal(data) {
        // Populate receipt data
        document.getElementById('receipt-invoice').innerText = data.invoice_number || '-';
        document.getElementById('receipt-date').innerText = new Date().toLocaleString('id-ID');
        document.getElementById('receipt-cashier').innerText = '{{ Auth::user()->name ?? "Admin" }}';
        document.getElementById('receipt-customer').innerText = data.customer_name || '-';
        document.getElementById('receipt-total-amount').innerText = 'Rp ' + f(data.total);
        
        // Map payment type to readable name
        const paymentTypes = {
            'cash': 'Tunai',
            'cod': 'COD',
            'QRIS': 'QRIS',
            'VA': 'Virtual Account'
        };
        document.getElementById('receipt-payment-type').innerText = paymentTypes[data.payment_type] || data.payment_type;
        
        // Populate items list
        let itemsHtml = '';
        if (posCart && posCart.length > 0) {
            posCart.forEach(item => {
                const subtotal = item.price * item.q;
                itemsHtml += `
                    <div style="margin-bottom: 6px;">
                        <div style="display: flex; justify-content: space-between;">
                            <span>${item.name}</span>
                            <span>Rp ${f(subtotal)}</span>
                        </div>
                        <div style="font-size: 10px; color: #666;">
                            ${item.q} x Rp ${f(item.price)}
                        </div>
                    </div>
                `;
            });
        }
        document.getElementById('receipt-items-list').innerHTML = itemsHtml;
        
        // Show modal
        document.getElementById('receipt-modal').style.display = 'flex';
    }

    function printReceipt() {
        window.print();
    }

    function closeReceiptAndReload() {
        document.getElementById('receipt-modal').style.display = 'none';
        posCart = []; // Clear cart
        location.reload();
    }
</script>
@endpush