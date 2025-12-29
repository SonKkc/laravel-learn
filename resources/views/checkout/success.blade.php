@extends('layouts.default')

@section('content')
    <section class="min-h-[60vh] flex items-center justify-center py-12 px-4">
        <div class="w-full max-w-3xl">
            <div class="bg-gradient-to-r from-white to-gray-50 rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="p-8 md:p-12 text-center">
                    <div class="mx-auto mb-6 inline-flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-br from-green-50 to-white p-1">
                        <div class="flex h-full w-full items-center justify-center rounded-full bg-gradient-to-br from-green-600 to-green-500 shadow-lg">
                            <svg class="h-12 w-12 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </div>

                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">Cảm ơn — đơn hàng của bạn đã được đặt!</h1>
                    <p class="text-gray-600 mb-6">Chúng tôi đã nhận được đơn hàng và sẽ liên hệ bạn sớm để xác nhận.</p>

                    @if(!empty($order_id))
                        <div class="inline-flex items-center gap-2 rounded-full bg-gray-50 px-4 py-2 text-sm font-medium text-gray-700 border border-gray-100 mb-6">
                            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 0 0 2 2h14" /><path stroke-linecap="round" stroke-linejoin="round" d="M7 7V5a5 5 0 0 1 10 0v2" /></svg>
                            <span>Mã đơn hàng:</span>
                            <strong class="text-gray-900">{{ $order_id }}</strong>
                        </div>
                    @endif

                    <div class="flex flex-col gap-3 sm:flex-row sm:justify-center sm:gap-4">
                        <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center rounded-full bg-main-red px-6 py-2 text-white font-semibold shadow hover:bg-main-red-hover transition">Tiếp tục mua sắm</a>
                        @if(!empty($order_id))
                            <a href="{{ route('orders.show', $order_id) }}" class="inline-flex items-center justify-center rounded-full border border-gray-200 bg-white px-6 py-2 text-gray-800 font-semibold shadow-sm hover:shadow transition">Xem đơn hàng</a>
                        @else
                            <a href="{{ route('orders.my') }}" class="inline-flex items-center justify-center rounded-full border border-gray-200 bg-white px-6 py-2 text-gray-800 font-semibold shadow-sm hover:shadow transition">Đơn hàng của tôi</a>
                        @endif
                    </div>
                </div>
                <div class="bg-white/40 border-t border-gray-100 p-4 text-center text-xs text-gray-500">
                    Nếu cần hỗ trợ, liên hệ chúng tôi qua <a href="mailto:support@example.com" class="text-main-red hover:underline">support@gmail.com</a>.
                </div>
            </div>
        </div>
    </section>
@endsection

