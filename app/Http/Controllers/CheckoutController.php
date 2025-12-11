<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class CheckoutController extends Controller {
    public function index() {
        return view('checkout.index');
    }
    public function success() {
        return view('checkout.success');
    }
    public function cancel() {
        return view('checkout.cancel');
    }
}
