<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = ['user_id', 'token'];

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function toArrayPayload(): array
    {
        $out = [];
        foreach ($this->items()->with('product')->get() as $it) {
            $product = $it->product;
            $out[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'qty' => (int) $it->qty,
                'price' => (float) $it->price,
                'total' => (float) $it->price * (int) $it->qty,
                'image' => is_array($product->images) && count($product->images) ? $product->images[0] : null,
            ];
        }
        return $out;
    }
}
