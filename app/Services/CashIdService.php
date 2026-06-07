<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CashIdService
{
    private string $apiKey;
    private string $secretKey;
    private string $webhookSecret;
    private string $mode;

    public function __construct()
    {
        $this->apiKey = config('cashid.api_key');
        $this->secretKey = config('cashid.secret_key');
        $this->webhookSecret = config('cashid.webhook_secret');
        $this->mode = config('cashid.mode', 'sandbox');
    }

    public function createPayment(Order $order, ?string $paymentChannel = null): array
    {
        $baseUrl = config('app.url');
        $timestamp = $this->getTimestamp();
        $requestId = (string) Str::uuid();
        
        // Use provided channel, or get from order's payment option, or default to QRIS
        if (!$paymentChannel) {
            $paymentChannel = $order->paymentOption->code ?? config('cashid.payment_channel', 'QRIS');
        }
        
        $duration = config('cashid.duration', 1440);

        $body = [
            'amount' => (int) $order->total_price,
            'trxNo' => $order->invoice_number,
            'duration' => $duration,
            'successCallbackUrl' => $baseUrl . '/payment/cashid/success',
            'cancelCallbackUrl' => $baseUrl . '/payment/cashid/cancel',
            'paymentChannel' => $paymentChannel,
            'customer' => [
                'id' => (string) $order->user_id,
                'phoneNumber' => $this->getCustomerPhone($order),
            ],
        ];

        $jsonBody = json_encode($body);
        $url = '/pg/fo/payment';
        $signature = $this->generateSignature($jsonBody, $url, $requestId, $timestamp);

        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
                'X-REQUEST-ID' => $requestId,
                'X-TIMESTAMP' => $timestamp,
                'X-SIGNATURE' => $signature,
                'Content-Type' => 'application/json',
            ])->post($this->getApiUrl('payment'), $body);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['data'])) {
                $order->update([
                    'payment_reference' => $order->invoice_number,
                    'payment_channel' => $paymentChannel,
                    'payment_token' => $responseData['data']['paymentToken'] ?? null,
                ]);

                return [
                    'success' => true,
                    'payment_url' => $responseData['data']['paymentUrl'] ?? null,
                    'payment_token' => $responseData['data']['paymentToken'] ?? null,
                ];
            }

            Log::error('Cash.id Payment Creation Failed', [
                'order_id' => $order->id,
                'response' => $responseData,
            ]);

            return [
                'success' => false,
                'message' => $responseData['message'] ?? 'Failed to create payment',
                'response' => $responseData,
            ];
        } catch (\Exception $e) {
            Log::error('Cash.id Payment Creation Error', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function checkPaymentStatus(string $trxNo): array
    {
        $timestamp = $this->getTimestamp();
        $requestId = (string) Str::uuid();
        $url = '/pg/fo/payment/status';

        $body = ['trxNo' => $trxNo];
        $jsonBody = json_encode($body);
        $signature = $this->generateSignature($jsonBody, $url, $requestId, $timestamp);

        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
                'X-REQUEST-ID' => $requestId,
                'X-TIMESTAMP' => $timestamp,
                'X-SIGNATURE' => $signature,
                'Content-Type' => 'application/json',
            ])->post($this->getApiUrl('status'), $body);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['data'])) {
                return [
                    'success' => true,
                    'status' => $responseData['data']['status'] ?? null,
                    'payment_method' => $responseData['data']['paymentMethod'] ?? null,
                    'reference_no' => $responseData['data']['referenceNo'] ?? null,
                    'amount' => $responseData['data']['amount'] ?? null,
                ];
            }

            return [
                'success' => false,
                'message' => $responseData['message'] ?? 'Failed to check payment status',
            ];
        } catch (\Exception $e) {
            Log::error('Cash.id Check Status Error', [
                'trxNo' => $trxNo,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function verifyWebhookSignature(array $payload, string $signature, string $timestamp, string $externalId): bool
    {
        $stringToSign = $timestamp . ':' . $externalId;
        $expectedSignature = base64_encode(
            hash_hmac('sha256', $stringToSign, $this->webhookSecret, true)
        );

        return hash_equals($expectedSignature, $signature);
    }

    public function processWebhook(array $payload): array
    {
        $status = $payload['latestTransactionStatus'] ?? null;
        $originalTrxNo = $payload['originalReferenceNo'] ?? null;
        $partnerRefNo = $payload['originalPartnerReferenceNo'] ?? null;

        $order = Order::where('invoice_number', $originalTrxNo)
            ->orWhere('invoice_number', $partnerRefNo)
            ->first();

        if (!$order) {
            return ['success' => false, 'message' => 'Order not found'];
        }

        switch ($status) {
            case '00':
                $order->update(['status' => 'completed']);
                break;
            case '05':
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

    private function generateSignature(string $body, string $url, string $requestId, string $timestamp): string
    {
        $bodyHash = base64_encode(hash('sha256', $body, true));
        $stringToSign = $bodyHash . ':' . $this->apiKey . ':' . $requestId . ':' . $url . ':' . $timestamp;

        $hmacHash = hash_hmac('sha256', $stringToSign, $this->secretKey, true);
        return base64_encode($hmacHash);
    }

    private function getTimestamp(): string
    {
        return now()->format('Y-m-d\TH:i:s.') . substr(now()->format('v'), 0, 3) . '+07:00';
    }

    private function getApiUrl(string $type): string
    {
        $urls = config('cashid.urls.' . $this->mode);
        return $urls[$type] ?? '';
    }

    private function getCustomerPhone(Order $order): string
    {
        $user = $order->user;
        if ($user && isset($user->phone)) {
            return '62' . ltrim($user->phone, '0');
        }
        return '6281234567890';
    }
}
