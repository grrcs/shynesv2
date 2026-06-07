<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Order;
use App\Models\PaymentOption;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test payment option
        PaymentOption::create([
            'name' => 'WijayaPay QRIS',
            'code' => 'QRIS',
            'description' => 'Test QRIS',
            'tax_percentage' => 0,
            'is_active' => true,
        ]);
    }

    public function test_create_payment_with_valid_order(): void
    {
        // Mock HTTP response from WijayaPay
        Http::fake([
            'app.wijayapay.com/*' => Http::response([
                'success' => true,
                'data' => [
                    'payment_name' => 'QRIS',
                    'payment_method' => 'QRIS',
                    'total_bayar' => 100000,
                    'total_fee' => 750,
                    'total_diterima' => 99250,
                    'ref_id' => 'TEST-123',
                    'trx_reference' => 'WPTRX123456',
                    'expired' => '2026-12-15 13:34:47',
                    'qr_image' => 'https://wijayapay.com/qris/test123.png',
                    'qr_string' => '00020101021226640017...',
                    'callback_url' => 'https://yourwebsite.com/callback/wijayapay',
                ]
            ], 200)
        ]);

        $user = User::factory()->create();
        $paymentOption = PaymentOption::first();
        
        $order = Order::create([
            'user_id' => $user->id,
            'payment_option_id' => $paymentOption->id,
            'total_price' => 100000,
            'status' => 'pending',
            'invoice_number' => 'TEST-' . time(),
        ]);

        $response = $this->actingAs($user)
            ->postJson("/payment/wijayapay/create/{$order->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }

    public function test_create_payment_unauthorized_user(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $paymentOption = PaymentOption::first();
        
        $order = Order::create([
            'user_id' => $user1->id,
            'payment_option_id' => $paymentOption->id,
            'total_price' => 100000,
            'status' => 'pending',
            'invoice_number' => 'TEST-' . time(),
        ]);

        $response = $this->actingAs($user2)
            ->postJson("/payment/wijayapay/create/{$order->id}");

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
    }

    public function test_check_payment_status(): void
    {
        Http::fake([
            'app.wijayapay.com/*' => Http::response([
                'data' => [
                    'payment_name' => 'QRIS',
                    'payment_method' => 'QRIS',
                    'total_bayar' => 100000,
                    'ref_id' => 'TEST-123',
                    'trx_reference' => 'WPTRX123456',
                ],
                'status_pembayaran' => 'paid',
            ], 200)
        ]);

        $user = User::factory()->create();
        $paymentOption = PaymentOption::first();
        
        $order = Order::create([
            'user_id' => $user->id,
            'payment_option_id' => $paymentOption->id,
            'total_price' => 100000,
            'status' => 'pending',
            'invoice_number' => 'TEST-' . time(),
        ]);

        $response = $this->actingAs($user)
            ->getJson("/payment/wijayapay/status/{$order->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'status' => 'SUCCESS',
            ]);
    }

    public function test_payment_success_updates_order_status(): void
    {
        Http::fake([
            'app.wijayapay.com/*' => Http::response([
                'data' => [
                    'payment_name' => 'QRIS',
                    'payment_method' => 'QRIS',
                    'total_bayar' => 100000,
                    'ref_id' => 'TEST-123',
                    'trx_reference' => 'WPTRX123456',
                ],
                'status_pembayaran' => 'paid',
            ], 200)
        ]);

        $user = User::factory()->create();
        $paymentOption = PaymentOption::first();
        
        $order = Order::create([
            'user_id' => $user->id,
            'payment_option_id' => $paymentOption->id,
            'total_price' => 100000,
            'status' => 'pending',
            'invoice_number' => 'TEST-' . time(),
        ]);

        $response = $this->actingAs($user)
            ->get("/payment/wijayapay/success?ref_id={$order->invoice_number}");

        $response->assertRedirect();
        
        $order->refresh();
        $this->assertEquals('completed', $order->status);
    }

    public function test_payment_cancel_updates_order_status(): void
    {
        $user = User::factory()->create();
        $paymentOption = PaymentOption::first();
        
        $order = Order::create([
            'user_id' => $user->id,
            'payment_option_id' => $paymentOption->id,
            'total_price' => 100000,
            'status' => 'pending',
            'invoice_number' => 'TEST-' . time(),
        ]);

        $response = $this->actingAs($user)
            ->get("/payment/wijayapay/cancel?ref_id={$order->invoice_number}");

        $response->assertRedirect();
        
        $order->refresh();
        $this->assertEquals('cancelled', $order->status);
    }
}
