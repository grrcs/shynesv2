<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Exception;

class CheckoutService
{
    /**
     * Process checkout from cart items
     *
     * @param \Illuminate\Database\Eloquent\Collection $cartItems
     * @param int $userId
     * @return Order
     * @throws Exception
     */
    public function processCheckout($cartItems, int $userId): Order
    {
        if ($cartItems->isEmpty()) {
            throw new Exception('Keranjang belanja kosong!');
        }

        return DB::transaction(function () use ($cartItems, $userId) {
            $totalPrice = 0;

            // Calculate total and verify stock
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
                            
                $totalPrice += $price * $item->quantity;
            }

            // Create Order
            $order = Order::create([
                'user_id' => $userId,
                'total_price' => $totalPrice,
                'status' => 'pending', 
                'invoice_number' => 'INV-' . time() . '-' . $userId,
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
