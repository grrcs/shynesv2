<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt {{ $order->invoice_number }} - Shyness</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- We inline standard fonts for print if needed -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500&family=Playfair+Display:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #111111;
            --secondary: #71717A;
            --thin-border: #E5E7EB;
        }
        body { 
            font-family: 'DM Sans', sans-serif;
            font-weight: 300;
            color: var(--primary);
            padding: 40px; 
            max-width: 800px; 
            margin: 0 auto;
            line-height: 1.6;
        }
        h1, h2, h3 {
            font-family: 'Playfair Display', serif;
            font-weight: 400;
            margin: 0;
        }
        .header { 
            text-align: center; 
            margin-bottom: 40px; 
            padding-bottom: 20px; 
        }
        .header h1 {
            font-size: 2rem;
            letter-spacing: 0.05em;
            margin-bottom: 5px;
        }
        .header p {
            color: var(--secondary);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin: 0;
        }
        .meta { 
            display: flex; 
            justify-content: space-between;
            margin-bottom: 40px; 
            font-size: 0.9rem;
        }
        .meta-col {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .meta-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--secondary);
            margin-bottom: 2px;
        }
        .items { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 40px; 
            font-size: 0.9rem;
        }
        .items th { 
            text-align: left; 
            border-bottom: 1px solid var(--primary); 
            padding: 10px 0; 
            font-size: 0.75rem;
            font-weight: 400;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--secondary);
        }
        .items td { 
            padding: 15px 0; 
            border-bottom: 1px solid var(--thin-border);
        }
        .total-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 40px;
        }
        .total-table {
            width: 300px;
            font-size: 0.9rem;
        }
        .total-table td {
            padding: 8px 0;
        }
        .total-row { 
            font-weight: 500; 
            border-top: 1px solid var(--primary); 
        }
        .total-row td {
            padding-top: 15px;
        }
        .footer { 
            text-align: center; 
            margin-top: 60px; 
            font-size: 0.8rem; 
            color: var(--secondary);
            border-top: 1px solid var(--thin-border);
            padding-top: 20px;
        }
        .no-print {
            text-align: center; 
            margin-top: 40px; 
            padding-top: 20px;
            border-top: 1px dashed var(--thin-border);
        }
        .btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            font-family: 'DM Sans', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.75rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 1px solid var(--primary);
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body onload="window.print()">

    @if(session('error'))
    <div style="background:#FEE2E2;border:1px solid #EF4444;color:#991B1B;padding:16px;border-radius:8px;margin-bottom:24px;font-size:14px;">
        {{ session('error') }}
    </div>
    @endif
    @if(session('success'))
    <div style="background:#D1FAE5;border:1px solid #10B981;color:#065F46;padding:16px;border-radius:8px;margin-bottom:24px;font-size:14px;">
        {{ session('success') }}
    </div>
    @endif

    <div class="header">
        <h1>SHYNESS</h1>
        <p>Curated Essentials</p>
    </div>

    <div class="meta">
        <div class="meta-col">
            <div>
                <div class="meta-label">Invoice</div>
                <div>{{ $order->invoice_number }}</div>
            </div>
            <div style="margin-top: 15px;">
                <div class="meta-label">Date</div>
                <div>{{ $order->created_at->format('M d, Y') }}</div>
            </div>
        </div>
        <div class="meta-col" style="text-align: right;">
            <div>
                <div class="meta-label">Customer</div>
                <div>{{ $order->user->name }}</div>
            </div>
            <div style="margin-top: 15px;">
                <div class="meta-label">Status</div>
                <div style="text-transform: uppercase; font-weight: 500;">{{ $order->status }}</div>
            </div>
        </div>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th style="width: 50%;">Item</th>
                <th style="text-align: center; width: 15%;">Qty</th>
                <th style="text-align: right; width: 15%;">Price</th>
                <th style="text-align: right; width: 20%;">Total</th>
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

    <div class="total-section">
        <table class="total-table">
            <tr>
                <td style="color: var(--secondary);">Subtotal</td>
                <td style="text-align: right;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td>Total Amount</td>
                <td style="text-align: right;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Thank you for shopping with Shyness.</p>
        <p>IG: @shyness.id &bull; www.shyness.com</p>
    </div>

    <div class="no-print">
        <button onclick="window.print()" class="btn" style="margin-right: 10px;">Print Receipt</button>
        <a href="{{ url()->previous() }}" class="btn btn-outline">Close</a>
    </div>

</body>
</html>
