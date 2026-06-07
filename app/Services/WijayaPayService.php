<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WijayaPayService
{
    private string $codeMerchant;
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->codeMerchant = config('wijayapay.code_merchant');
        $this->apiKey = config('wijayapay.api_key');
        $this->baseUrl = config('wijayapay.base_url');
    }

    /**
     * Create a payment order via WijayaPay API
     */
    public function createPayment(Order $order, ?string $paymentChannel = null): array
    {
        // Use provided channel, or get from order's payment option, or default config
        if (!$paymentChannel) {
            $paymentChannel = $order->paymentOption->code ?? config('wijayapay.payment_channel', 'QRIS');
        }

        $refId = $order->invoice_number;
        $signature = $this->generateSignature($refId);
        $callbackUrl = config('app.url') . config('wijayapay.callback_url');

        try {
            $response = Http::withHeaders([
                'X-Signature' => $signature,
            ])->asForm()->post($this->baseUrl . '/api/transaction/create', [
                'code_merchant' => $this->codeMerchant,
                'api_key' => $this->apiKey,
                'code_payment' => $paymentChannel,
                'nominal' => (int) $order->total_price,
                'ref_id' => $refId,
                'callback_url' => $callbackUrl,
            ]);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['success']) && $responseData['success'] === true) {
                $data = $responseData['data'];

                $order->update([
                    'payment_reference' => $data['trx_reference'] ?? $refId,
                    'payment_channel' => $paymentChannel,
                    'payment_token' => $data['trx_reference'] ?? null,
                    'payment_url' => $paymentUrl,
                ]);

                // Build payment URL based on channel type
                $paymentUrl = null;
                if (isset($data['qr_image'])) {
                    $paymentUrl = $data['qr_image'];
                } elseif (isset($data['nomor_va'])) {
                    $paymentUrl = $data['nomor_va'];
                } elseif (isset($data['nomor_pembayaran'])) {
                    $paymentUrl = $data['nomor_pembayaran'];
                }

                return [
                    'success' => true,
                    'payment_url' => $paymentUrl,
                    'payment_token' => $data['trx_reference'] ?? null,
                    'data' => $data,
                ];
            }

            Log::error('WijayaPay Payment Creation Failed', [
                'order_id' => $order->id,
                'response' => $responseData,
            ]);

            return [
                'success' => false,
                'message' => $responseData['message'] ?? 'Failed to create payment',
                'response' => $responseData,
            ];
        } catch (\Exception $e) {
            Log::error('WijayaPay Payment Creation Error', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check payment status via WijayaPay API
     */
    public function checkPaymentStatus(string $refId): array
    {
        try {
            $response = Http::get($this->baseUrl . '/api/get-status', [
                'code_merchant' => $this->codeMerchant,
                'api_key' => $this->apiKey,
                'ref_id' => $refId,
            ]);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['data'])) {
                $statusPembayaran = $responseData['status_pembayaran'] ?? $responseData['data']['status_pembayaran'] ?? null;

                return [
                    'success' => true,
                    'status' => $this->mapStatus($statusPembayaran),
                    'raw_status' => $statusPembayaran,
                    'payment_method' => $responseData['data']['payment_method'] ?? null,
                    'amount' => $responseData['data']['total_bayar'] ?? null,
                    'trx_reference' => $responseData['data']['trx_reference'] ?? null,
                ];
            }

            return [
                'success' => false,
                'message' => $responseData['message'] ?? 'Failed to check payment status',
            ];
        } catch (\Exception $e) {
            Log::error('WijayaPay Check Status Error', [
                'ref_id' => $refId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Process incoming webhook/callback from WijayaPay
     */
    public function processWebhook(array $payload): array
    {
        $status = $payload['status'] ?? null;
        $refId = $payload['ref_id'] ?? null;
        $trxReference = $payload['trx_reference'] ?? null;

        if (!$refId) {
            return ['success' => false, 'message' => 'Missing ref_id in payload'];
        }

        $order = Order::where('invoice_number', $refId)->first();

        if (!$order) {
            return ['success' => false, 'message' => 'Order not found'];
        }

        // Verify signature if present
        $signature = $payload['signature'] ?? null;
        if ($signature) {
            $expectedSignature = $this->generateSignature($refId);
            if (!hash_equals($expectedSignature, $signature)) {
                Log::warning('WijayaPay Webhook: Invalid signature', [
                    'ref_id' => $refId,
                ]);
                return ['success' => false, 'message' => 'Invalid signature'];
            }
        }

        switch ($status) {
            case 'paid':
                $order->update(['status' => 'completed']);
                break;
            case 'expired':
                $order->update(['status' => 'expired']);
                break;
            case 'failed':
                $order->update(['status' => 'cancelled']);
                break;
            default:
                return ['success' => false, 'message' => 'Unknown status: ' . $status];
        }

        return [
            'success' => true,
            'message' => 'Webhook processed successfully',
            'order_id' => $order->id,
            'status' => $status,
        ];
    }

    /**
     * Get available payment channels from WijayaPay
     */
    public function getPaymentChannels(): array
    {
        try {
            $response = Http::get($this->baseUrl . '/api/get-payment', [
                'code_merchant' => $this->codeMerchant,
                'api_key' => $this->apiKey,
            ]);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['success']) && $responseData['success'] === true) {
                return [
                    'success' => true,
                    'data' => $responseData['data'] ?? [],
                ];
            }

            return [
                'success' => false,
                'message' => $responseData['message'] ?? 'Failed to get payment channels',
            ];
        } catch (\Exception $e) {
            Log::error('WijayaPay Get Channels Error', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Generate X-Signature: md5(code_merchant + api_key + ref_id)
     */
    private function generateSignature(string $refId): string
    {
        return md5($this->codeMerchant . $this->apiKey . $refId);
    }

    /**
     * Map WijayaPay status to internal status
     */
    private function mapStatus(?string $status): string
    {
        return match ($status) {
            'paid' => 'SUCCESS',
            'pending' => 'PENDING',
            'expired' => 'EXPIRED',
            'failed' => 'FAILED',
            default => 'UNKNOWN',
        };
    }
}
