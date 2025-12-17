<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
class CartController extends Controller {
    public function index() {
        return view('cart.index');
    }

    /**
     * Return a small HTML fragment showing the current cart (for navbar preview).
     */
    public function preview(Request $request)
    {
        // If user is authenticated, prefer DB-backed cart
        if (Auth::check()) {
            $cartModel = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $cart = $cartModel->toArrayPayload();
        } else {
            $cart = $request->session()->get('cart', []);
        }

        // cart expected format: array of items with keys: id, name, qty, price, total
        return view('cart._preview', compact('cart'));
    }

    /**
     * Add a product to the session cart.
     * Accepts JSON or form data: product_id, quantity
     */
    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::find($data['product_id']);

        // If user authenticated, persist to DB cart
        if (Auth::check()) {
            $cartModel = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $item = CartItem::firstOrNew(['cart_id' => $cartModel->id, 'product_id' => $product->id]);
            $item->qty = ($item->exists ? $item->qty : 0) + $data['quantity'];
            $item->price = $product->price;
            $item->save();

            $cart = $cartModel->toArrayPayload();
        } else {
            $cart = $request->session()->get('cart', []);
            $id = (int) $product->id;
            if (isset($cart[$id])) {
                $cart[$id]['qty'] = ($cart[$id]['qty'] ?? 0) + $data['quantity'];
                $cart[$id]['total'] = $cart[$id]['qty'] * $cart[$id]['price'];
            } else {
                $cart[$id] = [
                    'id' => $id,
                    'name' => $product->name,
                    'qty' => $data['quantity'],
                    'price' => $product->price,
                    'total' => $product->price * $data['quantity'],
                    'image' => is_array($product->images) && count($product->images) ? $product->images[0] : null,
                ];
            }
            $request->session()->put('cart', $cart);
        }

        // if AJAX/JSON requested, return HTML fragment + count and payload
        if ($request->wantsJson() || $request->ajax()) {
            $preview = view('cart._preview', ['cart' => $cart])->render();
            $count = array_reduce($cart, function ($s, $it) { return $s + (($it['qty'] ?? 1)); }, 0);
            return response()->json(['success' => true, 'html' => $preview, 'count' => $count, 'cart' => $cart]);
        }

        return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng');
    }

    /**
     * Update quantity for an item in the session cart.
     * Accepts: product_id, quantity
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer'
        ]);

        // If authenticated, update DB-backed cart
        if (Auth::check()) {
            $cartModel = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $item = CartItem::where('cart_id', $cartModel->id)->where('product_id', $data['product_id'])->first();
            if ($item) {
                if ((int)$data['quantity'] <= 0) {
                    $item->delete();
                } else {
                    $item->qty = (int)$data['quantity'];
                    $item->save();
                }
            }
            $cart = $cartModel->toArrayPayload();
        } else {
            $cart = $request->session()->get('cart', []);
            $id = (int) $data['product_id'];
            if (isset($cart[$id])) {
                if ((int)$data['quantity'] <= 0) {
                    unset($cart[$id]);
                } else {
                    $cart[$id]['qty'] = (int)$data['quantity'];
                    $cart[$id]['total'] = $cart[$id]['qty'] * $cart[$id]['price'];
                }
                $request->session()->put('cart', $cart);
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            $preview = view('cart._preview', ['cart' => $cart])->render();
            $count = array_reduce($cart, function ($s, $it) { return $s + (($it['qty'] ?? 1)); }, 0);
            return response()->json(['success' => true, 'html' => $preview, 'count' => $count, 'cart' => $cart]);
        }

        return redirect()->back();
    }

    /**
     * Remove an item from the session cart.
     * Accepts: product_id
     */
    public function remove(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);

        // If authenticated, remove from DB cart
        if (Auth::check()) {
            $cartModel = Cart::firstOrCreate(['user_id' => Auth::id()]);
            CartItem::where('cart_id', $cartModel->id)->where('product_id', $data['product_id'])->delete();
            $cart = $cartModel->toArrayPayload();
        } else {
            $cart = $request->session()->get('cart', []);
            $id = (int) $data['product_id'];
            if (isset($cart[$id])) {
                unset($cart[$id]);
                $request->session()->put('cart', $cart);
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            $preview = view('cart._preview', ['cart' => $cart])->render();
            $count = array_reduce($cart, function ($s, $it) { return $s + (($it['qty'] ?? 1)); }, 0);
            return response()->json(['success' => true, 'html' => $preview, 'count' => $count, 'cart' => $cart]);
        }

        return redirect()->back();
    }

    /**
     * Return current cart as JSON payload (for frontend fetch).
     */
    public function json(Request $request)
    {
        if (Auth::check()) {
            $cartModel = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $cart = $cartModel->toArrayPayload();
        } else {
            $cart = $request->session()->get('cart', []);
        }

        $count = array_reduce($cart, function ($s, $it) { return $s + (($it['qty'] ?? 1)); }, 0);
        return response()->json(['success' => true, 'cart' => $cart, 'count' => $count]);
    }
}
