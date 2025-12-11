<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class AuthController extends Controller {
    public function loginForm() {
        return view('auth.login');
    }
    public function registerForm() {
        return view('auth.register');
    }
    public function forgotForm() {
        return view('auth.forgot');
    }
    public function resetForm() {
        return view('auth.reset');
    }
}
