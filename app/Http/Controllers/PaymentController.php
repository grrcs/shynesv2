<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\WijayaPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\PaymentOption;

class PaymentController extends Controller
{
    protected WijayaPayService $wijayaPayService;

    public function __construct(WijayaPayService $wijayaPayService)
    {
        $this->wijayaPayService = $wijayaPayService;
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
        // Check authorization
        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Get payment channel from request or order's payment option
        $paymentChannel = $request->input('payment_channel');

        $result = $this->wijayaPayService->createPayment($order, $paymentChannel);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'payment_url' => $result['payment_url'] ?? null,
                'payment_token' => $result['payment_token'] ?? null,
                'data' => $result['data'] ?? null,
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
        // Check authorization
        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if (!$order->invoice_number) {
            return response()->json([
                'success' => false,
                'message' => 'Order has no payment reference'
            ], 400);
        }

        // Check if order has expired (15 minutes for QRIS, 60 minutes for VA)
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

        $statusResult = $this->wijayaPayService->checkPaymentStatus($order->invoice_number);

        // If WijayaPay says paid, update order
        if ($statusResult['success'] && $statusResult['status'] === 'SUCCESS' && $order->status === 'pending') {
            $order->update(['status' => 'completed']);
        }

        // Calculate remaining time
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
        // VA gets longer expiry than QRIS
        $paymentOption = $order->paymentOption;
        if ($paymentOption && str_contains(strtolower($paymentOption->code ?? ''), 'va')) {
            return 60; // 60 minutes for Virtual Account
        }
        return 15; // 15 minutes for QRIS
    }

    public function paymentSuccess(Request $request)
    {
        $refId = $request->query('ref_id') ?? $request->query('trxNo');

        if ($refId) {
            $statusResult = $this->wijayaPayService->checkPaymentStatus($refId);

            if ($statusResult['success'] && $statusResult['status'] === 'SUCCESS') {
                $order = Order::where('invoice_number', $refId)->first();
                if ($order) {
                    $order->update(['status' => 'completed']);

                    // Clear cart if user is authenticated
                    if (auth()->check() && $order->user_id === auth()->id()) {
                        auth()->user()->cartItems()->delete();
                    }

                    // Redirect to order detail
                    return redirect()->route('orders.show', $order->id)
                        ->with('success', 'Pembayaran berhasil!');
                }
            }
        }

        // Fallback: redirect to home or login
        if (auth()->check()) {
            return redirect()->route('orders.my')->with('error', 'Pembayaran tidak valid atau gagal.');
        }

        return redirect()->route('login')->with('error', 'Silakan login untuk melihat status pembayaran.');
    }

    public function paymentCancel(Request $request)
    {
        $refId = $request->query('ref_id') ?? $request->query('trxNo');

        if ($refId) {
            $order = Order::where('invoice_number', $refId)->first();
            if ($order) {
                $order->update(['status' => 'cancelled']);
            }
        }

        // Redirect based on auth status
        if (auth()->check()) {
            return redirect()->route('cart.index')->with('error', 'Pembayaran dibatalkan.');
        }

        return redirect()->route('login')->with('error', 'Pembayaran dibatalkan. Silakan login untuk mencoba lagi.');
    }

    public function webhook(Request $request)
    {
        $payload = $request->all();

        Log::info('WijayaPay Webhook Received', [
            'payload' => $payload,
        ]);

        $result = $this->wijayaPayService->processWebhook($payload);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Webhook processed successfully',
            ]);
        }

        Log::warning('WijayaPay Webhook Processing Failed', [
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
        // Check authorization
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $order->load('paymentOption');

        // Get payment URL from order data
        $paymentUrl = null;
        if ($order->payment_token) {
            // Try to get fresh status/payment info
            $statusResult = $this->wijayaPayService->checkPaymentStatus($order->invoice_number);
            if ($statusResult['success'] && isset($statusResult['payment_url'])) {
                $paymentUrl = $statusResult['payment_url'];
            }
        }

        // Calculate expiry seconds based on payment type
        $paymentCode = $order->paymentOption->code ?? 'QRIS';
        if (str_contains(strtolower($paymentCode), 'va')) {
            $expirySeconds = 60 * 60; // 60 minutes for VA
        } else {
            $expirySeconds = 15 * 60; // 15 minutes for QRIS
        }

        // Adjust for time already elapsed
        $elapsed = now()->diffInSeconds($order->created_at);
        $expirySeconds = max(0, $expirySeconds - $elapsed);

        return view('checkout.payment-waiting', compact('order', 'paymentUrl', 'expirySeconds'));
    }
}
