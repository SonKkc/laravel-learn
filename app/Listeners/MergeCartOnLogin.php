<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use App\Models\CartItem;

class MergeCartOnLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;
        if (!$user) return;

        $sessionCart = session('cart', []);

        // Get or create a cart for user
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        // Merge session cart into DB cart
        foreach ($sessionCart as $pid => $item) {
            $productId = (int) ($item['id'] ?? $pid);
            $qty = (int) ($item['qty'] ?? 0);
            if ($qty <= 0) continue;
            $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $productId)->first();
            if ($cartItem) {
                $cartItem->qty = $cartItem->qty + $qty;
                $cartItem->save();
            } else {
                // price snapshot: try to use item's price if present
                $price = isset($item['price']) ? $item['price'] : null;
                $ci = new CartItem(['product_id' => $productId, 'qty' => $qty, 'price' => $price ?? 0]);
                $cart->items()->save($ci);
            }
        }

        // After merge, update session cart from DB
        session(['cart' => $cart->toArrayPayload()]);
    }
}
