<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentOption;
use App\Models\LoyaltyPoint;
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
     * @param string|null $couponCode
     * @return Order
     * @throws Exception
     */
    public function processCheckout($cartItems, int $userId, ?int $paymentOptionId = null, ?int $addressId = null, ?string $couponCode = null): Order
    {
        if ($cartItems->isEmpty()) {
            throw new Exception('Keranjang belanja kosong!');
        }

        return DB::transaction(function () use ($cartItems, $userId, $paymentOptionId, $addressId, $couponCode) {
            $subtotal = 0;
            $taxAmount = 0;
            $discountAmount = 0;
            $paymentOption = null;
            $coupon = null;

            // Get payment option if provided
            if ($paymentOptionId) {
                $paymentOption = PaymentOption::find($paymentOptionId);
                if (!$paymentOption || !$paymentOption->is_active) {
                    throw new Exception('Opsi pembayaran tidak valid atau tidak aktif!');
                }
            }

            // Validate and get coupon if provided
            if ($couponCode) {
                $coupon = \App\Models\Coupon::where('code', $couponCode)->first();
                if (!$coupon) {
                    throw new Exception('Kupon tidak ditemukan!');
                }
                if (!$coupon->isValidForUser($userId)) {
                    throw new Exception('Kupon tidak valid atau telah mencapai batas penggunaan!');
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
                // Check variant stock if variant exists
                if ($item->product_variant_id && $item->variant) {
                    if ($item->variant->stock < $item->quantity) {
                        throw new Exception("Stok varian {$item->display_name} tidak mencukupi! Tersedia: {$item->variant->stock}");
                    }
                } else {
                    // Check product stock
                    if ($item->product->stock < $item->quantity) {
                        throw new Exception("Stok produk {$item->product->title} tidak mencukupi!");
                    }
                }
                
                // Check discount limit (only for products without variants)
                if (!$item->product_variant_id && $item->product->is_discount_active && $item->product->discount_limit !== null && $item->product->discount_limit < $item->quantity) {
                    throw new Exception("Melebihi limit promo untuk {$item->product->title}!");
                }
                
                $subtotal += $item->final_price * $item->quantity;
            }

            // Calculate coupon discount
            if ($coupon) {
                if (!$coupon->isValidForOrder($subtotal)) {
                    throw new Exception("Kupon tidak valid untuk pesanan ini! Minimum order: Rp " . number_format($coupon->minimum_order_amount, 0, ',', '.'));
                }
                $discountAmount = $coupon->calculateDiscount($subtotal);
            }

            // Calculate tax amount based on payment option
            if ($paymentOption) {
                $taxAmount = ($subtotal - $discountAmount) * ($paymentOption->tax_percentage / 100);
            }

            $totalPrice = $subtotal - $discountAmount + $taxAmount;

            // Create Order
            $order = Order::create([
                'user_id' => $userId,
                'payment_option_id' => $paymentOptionId,
                'coupon_id' => $coupon ? $coupon->id : null,
                'total_price' => max(0, $totalPrice),
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'status' => 'pending', 
                'invoice_number' => 'INV-' . time() . '-' . $userId,
                'shipping_recipient_name' => $address->recipient_name,
                'shipping_phone_number' => $address->phone_number,
                'shipping_address' => $address->full_address,
                'shipping_city' => $address->city,
                'shipping_province' => $address->province,
                'shipping_postal_code' => $address->postal_code,
            ]);

            // Mark coupon as used if applied
            if ($coupon) {
                $coupon->markAsUsedBy($userId, $order->id);
            }

            // Award loyalty points
            $pointsEarned = LoyaltyPoint::calculatePoints($subtotal - $discountAmount);
            if ($pointsEarned > 0) {
                $order->user->addPoints(
                    $pointsEarned,
                    $order->id,
                    "Poin dari pesanan #{$order->invoice_number}"
                );
            }

            // Create Order Items and decrease stock
            foreach ($cartItems as $item) {
                $price = $item->final_price;
                $productId = $item->product_id;
                $variantId = $item->product_variant_id ?? null;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'product_variant_id' => $variantId,
                    'product_name' => $item->display_name,
                    'price' => $price,
                    'quantity' => $item->quantity,
                ]);

                // Decrease stock
                if ($variantId && $item->variant) {
                    // Decrease variant stock
                    $item->variant->decrementStock($item->quantity);
                } else {
                    // Decrease product stock
                    $item->product->decrement('stock', $item->quantity);
                }
                
                // Track discount limit (only for products, not variants)
                if (!$variantId && $item->product->is_discount_active && $item->product->discount_limit !== null) {
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
