<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        // Require authenticated user (route should also enforce middleware)
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $cartModel = \App\Models\Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cart = $cartModel->toArrayPayload();
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống. Vui lòng thêm sản phẩm trước khi thanh toán.');
        }
        $addresses = Auth::user()->addresses()->orderBy('id', 'desc')->get();

        return view('checkout.index', ['cart' => $cart, 'addresses' => $addresses]);
    }

    public function store(Request $request)
    {
        // Conditional validation: if the user provides an existing address_id (saved address),
        // shipping fields are not required. Otherwise require the shipping fields.
        $rules = [
            'first_name' => ['required_without:address_id', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['required_without:address_id', 'string', 'max:50'],
            'street_address' => ['required_without:address_id', 'string', 'max:255'],
            'city' => ['required_without:address_id', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'zip_code' => ['nullable', 'string', 'max:20'],
            'payment_method' => ['required', 'string'],
            // Ensure the address_id, if provided, exists and belongs to the current user
            'address_id' => ['nullable', 'integer', Rule::exists('addresses', 'id')->where(function ($query) {
                $query->where('user_id', Auth::id());
            })],
        ];

        $data = $request->validate($rules);

        // Require auth for checkout
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // gather DB-backed cart for authenticated user
        $cartModel = \App\Models\Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cart = $cartModel->toArrayPayload();

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống');
        }

        // compute totals server-side
        $subtotal = 0;
        foreach ($cart as $it) {
            $subtotal += ($it['total'] ?? ($it['price'] * ($it['quantity'] ?? 1)));
        }

        $shipping = 0; // MVP: flat 0 or implement later
        $tax = 0;
        $grand = $subtotal + $shipping + $tax;

        // Use DB transaction + lock products to avoid oversell
        try {
            $orderId = DB::transaction(function () use ($cart, $data, $shipping, $grand, $cartModel) {
                // Create order
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'grand_total' => $grand,
                    'currency' => 'USD',
                    'payment_method' => $data['payment_method'],
                    'payment_status' => $data['payment_method'] === 'cod' ? 'pending' : 'pending',
                    'status' => 'new',
                    'shipping_amount' => $shipping,
                ]);

                // For each cart item, lock product row and verify + decrement stock
                foreach ($cart as $it) {
                    $id = (int)$it['id'];
                    $qty = (int)($it['quantity'] ?? 1);

                    $product = Product::where('id', $id)->lockForUpdate()->first();
                    if (!$product) {
                        throw ValidationException::withMessages(['cart' => "Sản phẩm #{$id} không tồn tại"]);
                    }

                    $available = (int)($product->quantity ?? 0);
                    if ($available < $qty) {
                        throw ValidationException::withMessages(['cart' => "Sản phẩm {$product->name} chỉ còn {$available} sản phẩm, không đủ để đặt."]);
                    }

                    // decrement stock
                    $product->quantity = $available - $qty;
                    $product->save();

                    // create order item
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $id,
                        'quantity' => $qty,
                        'unit_amount' => $it['price'],
                        'total_amount' => $it['total'] ?? ($it['price'] * $qty),
                    ]);
                }

                // Save address into addresses table (create a new record for this order)
                if (!empty($data['address_id'])) {
                    // use existing saved address (ensure ownership) instead of duplicating it
                    $saved = Address::where('id', $data['address_id'])->where('user_id', Auth::id())->first();
                    if ($saved) {
                        // Create an order-only copy (do not set user_id) so the user's saved addresses are not duplicated or modified
                        $addressAttrs = $saved->only(['first_name','last_name','phone','street_address','city','state','zip_code']);
                        $addressAttrs['order_id'] = $order->id;
                        $addressAttrs['user_id'] = null;
                        $address = Address::create($addressAttrs);
                    } else {
                        // fallback to provided data
                        $address = Address::create([
                            'order_id' => $order->id,
                            'user_id' => Auth::id(),
                            'first_name' => $data['first_name'],
                            'last_name' => $data['last_name'] ?? null,
                            'phone' => $data['phone'],
                            'street_address' => $data['street_address'],
                            'city' => $data['city'],
                            'state' => $data['state'] ?? null,
                            'zip_code' => $data['zip_code'] ?? null,
                        ]);
                    }
                } else {
                    $address = Address::create([
                        'order_id' => $order->id,
                        'user_id' => Auth::id(),
                        'first_name' => $data['first_name'],
                        'last_name' => $data['last_name'] ?? null,
                        'phone' => $data['phone'],
                        'street_address' => $data['street_address'],
                        'city' => $data['city'],
                        'state' => $data['state'] ?? null,
                        'zip_code' => $data['zip_code'] ?? null,
                    ]);
                }

                // clear DB cart items
                if ($cartModel) {
                    $cartModel->items()->delete();
                }

                return $order->id;
            });

            return redirect()->route('checkout.success')->with('order_id', $orderId);
        } catch (ValidationException $ve) {
            // validation error like insufficient stock
            return redirect()->route('cart.index')->withErrors($ve->errors())->with('error', collect($ve->errors())->flatten()->first());
        }
    }

    public function success(Request $request)
    {
        $orderId = session('order_id') ?? $request->get('order_id');
        return view('checkout.success', ['order_id' => $orderId]);
    }

    public function cancel(Request $request)
    {
        return view('checkout.cancel');
    }
}

