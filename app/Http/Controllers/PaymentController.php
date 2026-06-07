<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PakasirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\PaymentOption;

class PaymentController extends Controller
{
    protected PakasirService $pakasirService;

    public function __construct(PakasirService $pakasirService)
    {
        $this->pakasirService = $pakasirService;
    }

    public function getPaymentOptions()
    {
        $paymentOptions = PaymentOption::where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $paymentOptions
        ]);
    }

    public function showPaymentOptions()
    {
        $cartItems = collect();
        $addresses = collect();

        if (auth()->check()) {
            $cartItems = auth()->user()->cartItems()->with('product')->get();
            $addresses = auth()->user()->addresses()->orderByDesc('is_primary')->latest()->get();
        }

        $paymentOptions = PaymentOption::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('checkout.payment-options', compact('paymentOptions', 'cartItems', 'addresses'));
    }

    public function createPayment(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $paymentChannel = $request->input('payment_channel', 'qris');
        $method = $this->toPakasirMethod($paymentChannel);
        $result = $this->pakasirService->createTransaction(
            $order->invoice_number,
            (int) $order->total_price,
            $method
        );

        if ($result['success']) {
            $order->update([
                'payment_channel' => $paymentChannel,
                'payment_token' => $result['payment_number'] ?? null,
                'payment_url' => $result['payment_number'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'payment_url' => $result['payment_number'] ?? null,
                'payment_token' => $result['payment_number'] ?? null,
                'data' => $result['raw'] ?? null,
                'message' => 'Payment created successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Failed to create payment'
        ], 500);
    }

    public function checkStatus(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if (!$order->invoice_number) {
            return response()->json(['success' => false, 'message' => 'Order has no payment reference'], 400);
        }

        $expiryMinutes = $this->getExpiryMinutes($order);
        $isExpired = $order->status === 'pending'
            && $order->created_at->addMinutes($expiryMinutes)->isPast();

        if ($isExpired && $order->status === 'pending') {
            $order->update(['status' => 'expired']);
            return response()->json([
                'success' => true,
                'status' => 'expired',
                'expired' => true,
                'order_status' => 'expired',
                'message' => 'Payment has expired',
            ]);
        }

        $statusResult = $this->pakasirService->checkStatus(
            $order->invoice_number,
            (int) $order->total_price
        );

        if ($statusResult['success'] && $statusResult['status'] === 'SUCCESS' && $order->status === 'pending') {
            $order->update(['status' => 'completed']);
        }

        $remainingSeconds = max(0, $order->created_at->addMinutes($expiryMinutes)->diffInSeconds(now(), false) * -1);

        return response()->json([
            'success' => $statusResult['success'],
            'status' => $statusResult['status'] ?? 'unknown',
            'order_status' => $order->fresh()->status,
            'expired' => $isExpired,
            'remaining_seconds' => (int) $remainingSeconds,
            'message' => $statusResult['message'] ?? null,
        ]);
    }

    private function getExpiryMinutes(Order $order): int
    {
        $paymentOption = $order->paymentOption;
        if ($paymentOption && str_contains(strtolower($paymentOption->code ?? ''), 'va')) {
            return 60;
        }
        return 15;
    }

    public function paymentSuccess(Request $request)
    {
        $orderId = $request->query('order_id') ?? $request->query('trxNo');

        if ($orderId) {
            $order = Order::where('invoice_number', $orderId)->first();
            if ($order) {
                $statusResult = $this->pakasirService->checkStatus(
                    $order->invoice_number,
                    (int) $order->total_price
                );

                if ($statusResult['success'] && $statusResult['status'] === 'SUCCESS') {
                    $order->update(['status' => 'completed']);

                    if (auth()->check() && $order->user_id === auth()->id()) {
                        auth()->user()->cartItems()->delete();
                    }

                    return redirect()->route('orders.show', $order->id)
                        ->with('success', 'Pembayaran berhasil!');
                }
            }
        }

        if (auth()->check()) {
            return redirect()->route('orders.my')->with('error', 'Pembayaran tidak valid atau gagal.');
        }

        return redirect()->route('login')->with('error', 'Silakan login untuk melihat status pembayaran.');
    }

    public function paymentCancel(Request $request)
    {
        $orderId = $request->query('order_id') ?? $request->query('trxNo');

        if ($orderId) {
            $order = Order::where('invoice_number', $orderId)->first();
            if ($order) {
                $this->pakasirService->cancelTransaction(
                    $order->invoice_number,
                    (int) $order->total_price
                );
                $order->update(['status' => 'cancelled']);
            }
        }

        if (auth()->check()) {
            return redirect()->route('cart.index')->with('error', 'Pembayaran dibatalkan.');
        }

        return redirect()->route('login')->with('error', 'Pembayaran dibatalkan. Silakan login untuk mencoba lagi.');
    }

    public function webhook(Request $request)
    {
        $payload = $request->all();

        Log::info('Pakasir Webhook Received', [
            'payload' => $payload,
        ]);

        $result = $this->pakasirService->processWebhook($payload);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Webhook processed successfully',
            ]);
        }

        Log::warning('Pakasir Webhook Processing Failed', [
            'payload' => $payload,
            'result' => $result,
        ]);

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Failed to process webhook',
        ], 400);
    }

    public function showPaymentWaiting(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $order->load('paymentOption');

        $paymentUrl = $order->payment_url;
        $isQris = $order->paymentOption && $order->paymentOption->code === 'QRIS';

        if ($isQris && $paymentUrl) {
            $qrDataUri = $this->pakasirService->generateQRDataUri($paymentUrl);
        } else {
            $qrDataUri = null;
        }

        $paymentCode = $order->paymentOption->code ?? 'QRIS';
        if (str_contains(strtolower($paymentCode), 'va')) {
            $expirySeconds = 60 * 60;
        } else {
            $expirySeconds = 15 * 60;
        }

        $elapsed = now()->diffInSeconds($order->created_at);
        $expirySeconds = max(0, $expirySeconds - $elapsed);

        return view('checkout.payment-waiting', compact('order', 'paymentUrl', 'expirySeconds', 'qrDataUri', 'isQris'));
    }

    private function toPakasirMethod(string $code): string
    {
        return match (strtoupper($code)) {
            'QRIS' => 'qris',
            'BRIVA' => 'bri_va',
            'BCAVA' => 'bni_va',
            'BNIVA' => 'bni_va',
            'MANDIRIVA' => 'permata_va',
            'BSIVA' => 'bni_va',
            'CIMBVA' => 'cimb_niaga_va',
            'PERMATAVA' => 'permata_va',
            'MAYBANKVA' => 'maybank_va',
            'BNCVA' => 'bnc_va',
            'SAMPOERNAVA' => 'sampoerna_va',
            'ATMBERSAMAVA' => 'atm_bersama_va',
            'ARTHAGRAHAVA' => 'artha_graha_va',
            default => 'qris',
        };
    }
}
