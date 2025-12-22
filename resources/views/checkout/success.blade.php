@extends('layouts.default')
@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-semibold mb-4">Cảm ơn, đơn hàng đã được đặt</h1>
        @if(!empty($order_id))
            <p>Mã đơn hàng: <strong>{{ $order_id }}</strong></p>
        @endif
        <p>Chúng tôi sẽ liên hệ bạn sớm để xác nhận đơn hàng.</p>
    </div>
@endsection

