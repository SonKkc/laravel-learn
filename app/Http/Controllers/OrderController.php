<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller {
    public function myOrders(Request $request) {
        // Return only the authenticated user's orders, oldest -> newest, paginated
        $perPage = 12;
        $orders = Auth::user()
            ->orders()
            ->with('items.product')
            ->orderBy('created_at', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        return view('orders.my', compact('orders'));
    }

    public function show(Order $order) {
        return view('orders.show', compact('order'));
    }
}
