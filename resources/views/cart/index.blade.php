@extends('layouts.default')
@section('content')
<section class="py-12">
    <div x-data="cartConfirm()" x-cloak class="container mx-auto px-4">
        <h1 class="text-2xl font-semibold mb-6">Giỏ hàng của bạn</h1>

        @php $items = $cart ?? session('cart', []); @endphp

        @if(empty($items) || count($items) === 0)
            <div class="rounded-lg bg-white p-8 shadow text-center">
                <p class="text-gray-600 mb-4">Giỏ hàng trống.</p>
                <a href="{{ url('/') }}" class="inline-block rounded-md border border-main-red text-main-red px-4 py-2">Tiếp tục mua sắm</a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="rounded-lg bg-white p-6 shadow">
                        <h2 class="text-lg font-semibold mb-4">Sản phẩm trong giỏ</h2>
                        <ul id="cart-items" class="divide-y">
                            @php $subtotal = 0; @endphp
                            @foreach($items as $item)
                                @php
                                    $qty = $item['qty'] ?? 1;
                                    $lineTotal = $item['total'] ?? ($item['price'] * $qty);
                                    $subtotal += $lineTotal;
                                    $img = $item['image'] ?? '';
                                    $imgSrc = \Illuminate\Support\Str::startsWith($img, 'static/') ? asset($img) : asset('storage/' . ltrim($img, '/'));
                                @endphp

                                <li data-product-id="{{ $item['id'] }}" data-price="{{ $item['price'] ?? 0 }}" class="flex items-center justify-between py-4">
                                    <div class="flex items-center gap-4 min-w-0">
                                        @if(!empty($img))
                                            <img src="{{ $imgSrc }}" alt="{{ $item['name'] ?? '' }}" class="h-20 w-20 rounded object-cover">
                                        @else
                                            <div class="h-20 w-20 rounded bg-gray-100 flex items-center justify-center text-gray-400">No
                                            </div>
                                        @endif

                                        <div class="min-w-0">
                                            <a href="{{ route('products.show', $item['id']) }}" class="font-medium truncate hover:underline">{{ $item['name'] ?? 'Sản phẩm' }}</a>
                                            <div class="text-sm text-gray-500 mt-1">{{ number_format($item['price'] ?? 0, 0, ',', '.') }} đ</div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center rounded-md border overflow-hidden">
                                            <button
                                                type="button"
                                                x-on:click.prevent="handleChange($event, '{{ route('cart.update') }}', {{ $item['id'] }}, -1)"
                                                class="px-3 py-1 bg-gray-100 text-sm"
                                                aria-label="Giảm số lượng"
                                            >−</button>
                                            <div class="px-4 py-1 text-sm" aria-live="polite" data-cart-qty="{{ $item['id'] }}">{{ $qty }}</div>
                                            <button
                                                type="button"
                                                x-on:click.prevent="handleChange($event, '{{ route('cart.update') }}', {{ $item['id'] }}, 1)"
                                                class="px-3 py-1 bg-gray-100 text-sm"
                                                aria-label="Tăng số lượng"
                                            >+</button>
                                        </div>

                                        <div class="text-sm font-semibold" data-cart-line-total="{{ $item['id'] }}">{{ number_format($lineTotal, 0, ',', '.') }} đ</div>

                                        <button
                                            type="button"
                                            x-bind:disabled="processing"
                                            x-on:click.prevent="openConfirm('remove', '{{ route('cart.remove') }}', {{ $item['id'] }}, $event)"
                                            class="inline-flex items-center justify-center h-9 w-9 rounded bg-red-500 text-white"
                                            aria-label="Xoá sản phẩm"
                                        >✕</button>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <aside class="lg:col-span-1">
                    <div id="cart-summary" class="rounded-lg bg-white p-6 shadow space-y-4">
                        <h3 class="text-lg font-semibold">Tóm tắt đơn hàng</h3>
                        <div class="flex items-center justify-between text-sm text-gray-600">
                            <div>Tạm tính</div>
                            <div data-cart-subtotal="true">{{ number_format($subtotal, 0, ',', '.') }} đ</div>
                        </div>
                        <div class="flex items-center justify-between text-sm text-gray-600">
                            <div>Phí vận chuyển</div>
                            <div>Miễn phí</div>
                        </div>
                        <div class="border-t pt-3 flex items-center justify-between">
                            <div class="text-base font-semibold">Tổng cộng</div>
                            <div class="text-base font-semibold" data-cart-total="true">{{ number_format($subtotal, 0, ',', '.') }} đ</div>
                        </div>

                        @auth
                            <a href="{{ route('checkout.index') }}" class="inline-block w-full rounded-md bg-main-red px-4 py-3 text-center text-white">Tiến hành thanh toán</a>
                        @else
                            <a href="{{ route('login') }}?redirect={{ urlencode(route('checkout.index')) }}" class="inline-block w-full rounded-md bg-main-red px-4 py-3 text-center text-white">Đăng nhập để thanh toán</a>
                        @endauth

                        <a href="{{ url('/') }}" class="block text-center text-sm text-gray-600">Tiếp tục mua sắm</a>
                    </div>
                </aside>
            </div>
        @endif

        @include('partials.cart-confirm')

    </div>
</section>

<script>
function cartConfirm() {
    return {
        confirmOpen: false,
        action: null,
        url: null,
        product_id: null,
        quantity: null,
        processing: false,

        openConfirm(action, url, product_id, event) {
            this.action = action;
            this.url = url;
            this.product_id = product_id;
            this.quantity = null;
            this.confirmOpen = true;
        },

        handleChange(event, url, product_id, delta) {
            // read current quantity from DOM and compute new quantity
            try {
                const li = document.querySelector('#cart-items [data-product-id="' + product_id + '"]');
                if (!li) return;
                const qtyEl = li.querySelector('[data-cart-qty]');
                const current = qtyEl ? parseInt(qtyEl.textContent || '0', 10) : 0;
                const newQty = Math.max(0, current + Number(delta));
                if (newQty <= 0) {
                    this.openConfirm('update', url, product_id, event);
                    this.quantity = newQty;
                    return;
                }
                this.doImmediateUpdate(event, url, product_id, newQty);
            } catch (e) {
                console.error(e);
            }
        },

        async doImmediateUpdate(event, url, product_id, quantity) {
            // per-button spinner
            const el = event.currentTarget || event.target;
            const spinner = el ? el.querySelector('svg.spinner-inline') : null;
            try {
                if (spinner) spinner.style.display = 'inline-block';
                if (el) el.disabled = true;
                await window.cartPerformUpdate({ url: url, product_id: product_id, quantity: quantity });
            } catch (e) {
                // errors handled in cartPerformUpdate
            } finally {
                if (spinner) spinner.style.display = 'none';
                if (el) el.disabled = false;
            }
        },

        async confirmProceed(e) {
            try {
                this.processing = true;
                if (this.action === 'remove') {
                    await window.cartPerformRemove({ url: this.url, product_id: this.product_id });
                } else if (this.action === 'update') {
                    await window.cartPerformUpdate({ url: this.url, product_id: this.product_id, quantity: this.quantity ?? 0 });
                }
            } catch (err) {
                // handled elsewhere
            } finally {
                this.processing = false;
                this.confirmOpen = false;
            }
        }
    }
}
</script>
@endsection
