<?php
namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;
class OrderController extends Controller {
    public function myOrders() {
        $orders = Order::all(); // Thay bằng lấy orders của user đang đăng nhập
        return view('orders.my', compact('orders'));
    }
    public function show(Order $order) {
        return view('orders.show', compact('order'));
    }
}
