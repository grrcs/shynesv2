<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resi #{{ $order->invoice_number }}</title>
    <style>
        body { font-family: monospace; padding: 20px; max-width: 600px; margin: 0 auto; border: 1px solid #eee; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px dashed #000; padding-bottom: 10px; }
        .meta { margin-bottom: 20px; display: flex; justify-content: space-between; }
        .items { w-full; border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        .items th { text-align: left; border-bottom: 1px solid #000; padding: 5px 0; }
        .items td { padding: 5px 0; }
        .total { text-align: right; font-weight: bold; border-top: 1px solid #000; padding-top: 10px; }
        .footer { text-align: center; margin-top: 30px; font-size: 0.8em; color: #555; }
        @media print {
            body { border: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h2>SHYNESS STORE</h2>
        <p>Jl. Contoh No. 123, Kota Digital</p>
    </div>

    <div class="meta">
        <div>
            <strong>Invoice:</strong> {{ $order->invoice_number }}<br>
            <strong>Tanggal:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
        </div>
        <div style="text-align: right;">
            <strong>Pelanggan:</strong> {{ $order->user->name }}<br>
            <strong>Status:</strong> {{ strtoupper($order->status) }}
        </div>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>Produk</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Harga</th>
                <th style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td style="text-align: center;">{{ $item->quantity }}</td>
                <td style="text-align: right;">{{ number_format($item->price, 0, ',', '.') }}</td>
                <td style="text-align: right;">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        TOTAL BAYAR: Rp {{ number_format($order->total_price, 0, ',', '.') }}
    </div>

    <div class="footer">
        <p>Terima kasih telah berbelanja di Shyness Store.</p>
        <p>Instagram: @shyness.id</p>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Cetak Resi</button>
        <a href="{{ url()->previous() }}" style="margin-left: 10px;">Kembali</a>
    </div>

</body>
</html>
