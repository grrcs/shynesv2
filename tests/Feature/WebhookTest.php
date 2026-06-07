<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Order;
use App\Models\PaymentOption;

class WebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        PaymentOption::create([
            'name' => 'WijayaPay QRIS',
            'code' => 'QRIS',
            'description' => 'Test QRIS',
            'tax_percentage' => 0,
            'is_active' => true,
        ]);
    }

    public function test_webhook_with_missing_ref_id_returns_400(): void
    {
        $response = $this->postJson('/payment/wijayapay/callback', [
            'status' => 'paid',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_webhook_with_unknown_order_returns_error(): void
    {
        $response = $this->postJson('/payment/wijayapay/callback', [
            'status' => 'paid',
            'ref_id' => 'NONEXISTENT-123',
            'trx_reference' => 'WPTRX999999',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_webhook_processes_successful_payment(): void
    {
        $user = User::factory()->create();
        $paymentOption = PaymentOption::first();

        $order = Order::create([
            'user_id' => $user->id,
            'payment_option_id' => $paymentOption->id,
            'total_price' => 100000,
            'status' => 'pending',
            'invoice_number' => 'TEST-WEBHOOK-' . time(),
        ]);

        $response = $this->postJson('/payment/wijayapay/callback', [
            'status' => 'paid',
            'ref_id' => $order->invoice_number,
            'trx_reference' => 'WPTRX123456',
            'amount_received' => 99250,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $order->refresh();
        $this->assertEquals('completed', $order->status);
    }

    public function test_webhook_processes_expired_payment(): void
    {
        $user = User::factory()->create();
        $paymentOption = PaymentOption::first();

        $order = Order::create([
            'user_id' => $user->id,
            'payment_option_id' => $paymentOption->id,
            'total_price' => 100000,
            'status' => 'pending',
            'invoice_number' => 'TEST-EXPIRED-' . time(),
        ]);

        $response = $this->postJson('/payment/wijayapay/callback', [
            'status' => 'expired',
            'ref_id' => $order->invoice_number,
            'trx_reference' => 'WPTRX123456',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $order->refresh();
        $this->assertEquals('expired', $order->status);
    }

    public function test_webhook_processes_failed_payment(): void
    {
        $user = User::factory()->create();
        $paymentOption = PaymentOption::first();

        $order = Order::create([
            'user_id' => $user->id,
            'payment_option_id' => $paymentOption->id,
            'total_price' => 100000,
            'status' => 'pending',
            'invoice_number' => 'TEST-FAILED-' . time(),
        ]);

        $response = $this->postJson('/payment/wijayapay/callback', [
            'status' => 'failed',
            'ref_id' => $order->invoice_number,
            'trx_reference' => 'WPTRX123456',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $order->refresh();
        $this->assertEquals('cancelled', $order->status);
    }
}
