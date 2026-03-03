<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentOption;
use Illuminate\Support\Facades\DB;
use Exception;

class CheckoutService
{
    /**
     * Process checkout from cart items
     *
     * @param \Illuminate\Database\Eloquent\Collection $cartItems
     * @param int $userId
     * @param int|null $paymentOptionId
     * @param int|null $addressId
     * @return Order
     * @throws Exception
     */
    public function processCheckout($cartItems, int $userId, ?int $paymentOptionId = null, ?int $addressId = null): Order
    {
        if ($cartItems->isEmpty()) {
            throw new Exception('Keranjang belanja kosong!');
        }

        return DB::transaction(function () use ($cartItems, $userId, $paymentOptionId, $addressId) {
            $subtotal = 0;
            $taxAmount = 0;
            $paymentOption = null;

            // Get payment option if provided
            if ($paymentOptionId) {
                $paymentOption = PaymentOption::find($paymentOptionId);
                if (!$paymentOption || !$paymentOption->is_active) {
                    throw new Exception('Opsi pembayaran tidak valid atau tidak aktif!');
                }
            }

            // Get shipping address
            $address = null;
            if ($addressId) {
                $address = Address::where('id', $addressId)->where('user_id', $userId)->first();
                if (!$address) {
                    throw new Exception('Alamat pengiriman tidak ditemukan atau tidak valid!');
                }
            } else {
                throw new Exception('Alamat pengiriman wajib dipilih!');
            }

            // Calculate subtotal and verify stock
            foreach ($cartItems as $item) {
                if ($item->product->stock < $item->quantity) {
                    throw new Exception("Stok produk {$item->product->title} tidak mencukupi!");
                }
                if ($item->product->is_discount_active && $item->product->discount_limit !== null && $item->product->discount_limit < $item->quantity) {
                    throw new Exception("Melebihi limit promo untuk {$item->product->title}!");
                }
                
                $price = ($item->product->is_discount_active && $item->product->discount_price) 
                            ? $item->product->discount_price 
                            : $item->product->price;
                            
                $subtotal += $price * $item->quantity;
            }

            // Calculate tax amount based on payment option
            if ($paymentOption) {
                $taxAmount = $subtotal * ($paymentOption->tax_percentage / 100);
            }

            $totalPrice = $subtotal + $taxAmount;

            // Create Order
            $order = Order::create([
                'user_id' => $userId,
                'payment_option_id' => $paymentOptionId,
                'total_price' => $totalPrice,
                'tax_amount' => $taxAmount,
                'status' => 'pending', 
                'invoice_number' => 'INV-' . time() . '-' . $userId,
                'shipping_recipient_name' => $address->recipient_name,
                'shipping_phone_number' => $address->phone_number,
                'shipping_address' => $address->full_address,
                'shipping_city' => $address->city,
                'shipping_province' => $address->province,
                'shipping_postal_code' => $address->postal_code,
            ]);

            // Create Order Items and decrease stock
            foreach ($cartItems as $item) {
                $price = ($item->product->is_discount_active && $item->product->discount_price) 
                            ? $item->product->discount_price 
                            : $item->product->price;
                            
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->title,
                    'price' => $price,
                    'quantity' => $item->quantity,
                ]);

                // Decrease normal stock
                $item->product->decrement('stock', $item->quantity);
                
                // Track discount limit
                if ($item->product->is_discount_active && $item->product->discount_limit !== null) {
                    $item->product->decrement('discount_limit', $item->quantity);
                    if ($item->product->discount_limit <= 0) {
                        $item->product->update(['is_discount_active' => false]);
                    }
                }
            }

            // Empty the cart
            $cartItems->each->delete();

            return $order;
        });
    }
}
