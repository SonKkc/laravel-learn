@extends('layouts.default')
@section('content')
    <h1 class="text-2xl font-semibold mb-4">Giỏ hàng</h1>

    {{-- Reuse cart preview partial to render items and subtotal --}}
    @include('cart._preview', ['cart' => $cart ?? session('cart', [])])
@endsection
