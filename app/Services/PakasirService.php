<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Output\QRMarkupSVG;

class PakasirService
{
    private string $slug;
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->slug = config('pakasir.slug');
        $this->apiKey = config('pakasir.api_key');
        $this->baseUrl = config('pakasir.base_url');
    }

    public function createTransaction(string $orderId, int $amount, string $method = 'qris'): array
    {
        try {
            $response = Http::post("{$this->baseUrl}/api/transactioncreate/{$method}", [
                'project' => $this->slug,
                'order_id' => $orderId,
                'amount' => $amount,
                'api_key' => $this->apiKey,
            ]);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['payment'])) {
                $payment = $responseData['payment'];

                $paymentNumber = $payment['payment_number'] ?? null;
                $totalPayment = $payment['total_payment'] ?? $amount;
                $fee = $payment['fee'] ?? 0;
                $expiredAt = $payment['expired_at'] ?? null;

                return [
                    'success' => true,
                    'payment_number' => $paymentNumber,
                    'payment_url' => $paymentNumber,
                    'total_payment' => $totalPayment,
                    'fee' => $fee,
                    'expired_at' => $expiredAt,
                    'raw' => $payment,
                ];
            }

            Log::error('Pakasir Transaction Creation Failed', [
                'order_id' => $orderId,
                'response' => $responseData,
            ]);

            return [
                'success' => false,
                'message' => $responseData['message'] ?? 'Failed to create transaction',
                'response' => $responseData,
            ];
        } catch (\Exception $e) {
            Log::error('Pakasir Transaction Creation Error', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function checkStatus(string $orderId, int $amount): array
    {
        try {
            $response = Http::get("{$this->baseUrl}/api/transactiondetail", [
                'project' => $this->slug,
                'order_id' => $orderId,
                'amount' => $amount,
                'api_key' => $this->apiKey,
            ]);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['transaction'])) {
                $transaction = $responseData['transaction'];
                $status = $transaction['status'] ?? 'pending';

                return [
                    'success' => true,
                    'status' => $this->mapStatus($status),
                    'raw_status' => $status,
                    'payment_method' => $transaction['payment_method'] ?? null,
                    'amount' => $transaction['amount'] ?? null,
                    'completed_at' => $transaction['completed_at'] ?? null,
                ];
            }

            return [
                'success' => false,
                'message' => $responseData['message'] ?? 'Failed to check status',
            ];
        } catch (\Exception $e) {
            Log::error('Pakasir Check Status Error', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function cancelTransaction(string $orderId, int $amount): array
    {
        try {
            $response = Http::post("{$this->baseUrl}/api/transactioncancel", [
                'project' => $this->slug,
                'order_id' => $orderId,
                'amount' => $amount,
                'api_key' => $this->apiKey,
            ]);

            $responseData = $response->json();

            return [
                'success' => $response->successful(),
                'response' => $responseData,
            ];
        } catch (\Exception $e) {
            Log::error('Pakasir Cancel Transaction Error', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function processWebhook(array $payload): array
    {
        $status = $payload['status'] ?? null;
        $orderId = $payload['order_id'] ?? null;
        $amount = $payload['amount'] ?? null;

        if (!$orderId || !$amount) {
            return ['success' => false, 'message' => 'Missing order_id or amount'];
        }

        $order = Order::where('invoice_number', $orderId)->first();

        if (!$order) {
            return ['success' => false, 'message' => 'Order not found'];
        }

        switch ($status) {
            case 'completed':
                $order->update(['status' => 'completed']);
                break;
            case 'expired':
                $order->update(['status' => 'expired']);
                break;
            case 'canceled':
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

    public function generateQRDataUri(string $data, int $size = 300): string
    {
        $options = new QROptions([
            'outputInterface' => QRMarkupSVG::class,
            'scale' => 10,
            'outputBase64' => true,
            'svgAddXmlHeader' => false,
        ]);

        $qrcode = new QRCode($options);
        return $qrcode->render($data);
    }

    public function getPaymentUrl(string $orderId, int $amount, ?string $redirect = null, bool $qrisOnly = false): string
    {
        $url = "{$this->baseUrl}/pay/{$this->slug}/{$amount}?order_id={$orderId}";

        if ($redirect) {
            $url .= '&redirect=' . urlencode($redirect);
        }

        if ($qrisOnly) {
            $url .= '&qris_only=1';
        }

        return $url;
    }

    private function mapStatus(?string $status): string
    {
        return match ($status) {
            'completed' => 'SUCCESS',
            'pending' => 'PENDING',
            'expired' => 'EXPIRED',
            'canceled' => 'FAILED',
            default => 'UNKNOWN',
        };
    }

    private function mapPaymentMethod(string $code): string
    {
        return match (strtolower($code)) {
            'qris' => 'QRIS',
            'bni_va' => 'BNIVA',
            'bri_va' => 'BRIVA',
            'cimb_niaga_va' => 'CIMBVA',
            'permata_va' => 'PERMATAVA',
            'maybank_va' => 'MAYBANKVA',
            'bnc_va' => 'BNCVA',
            'sampoerna_va' => 'SAMPOERNAVA',
            'atm_bersama_va' => 'ATMBERSAMAVA',
            'artha_graha_va' => 'ARTHAGRAHAVA',
            default => strtoupper($code),
        };
    }
}
