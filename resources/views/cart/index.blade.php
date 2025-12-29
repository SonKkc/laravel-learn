@extends('layouts.default')
@section('content')
    <section class="py-12">
        <div x-data="cartConfirm()" x-cloak class="container mx-auto px-4">
            <h1 class="mb-6 text-2xl font-semibold">Your Cart</h1>

            @php $items = $cart ?? session('cart', []); @endphp

            @if (empty($items) || count($items) === 0)
                <div class="rounded-lg bg-white p-8 text-center shadow">
                    <p class="mb-4 text-gray-600">Your cart is empty.</p>
                    <a href="{{ url('/') }}" class="border-main-red text-main-red inline-block rounded-md border px-4 py-2">Continue shopping</a>
                </div>
            @else
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                    <div class="lg:col-span-2">
                        <div class="rounded-lg bg-white p-6 shadow">
                            <h2 class="mb-4 text-lg font-semibold">Items in cart</h2>
                            <ul id="cart-items" class="divide-y">
                                @php $subtotal = 0; @endphp
                                @foreach ($items as $item)
                                    @php
                                        $qty = $item['qty'] ?? 1;
                                        $lineTotal = $item['total'] ?? $item['price'] * $qty;
                                        $subtotal += $lineTotal;
                                        $img = $item['image'] ?? '';
                                        $imgSrc = \Illuminate\Support\Str::startsWith($img, 'static/') ? asset($img) : asset('storage/' . ltrim($img, '/'));
                                    @endphp

                                    <li data-product-id="{{ $item['id'] }}" data-price="{{ $item['price'] ?? 0 }}" class="flex items-center justify-between py-4">
                                        <div class="flex min-w-0 items-center gap-4">
                                            @if (!empty($img))
                                                <img src="{{ $imgSrc }}" alt="{{ $item['name'] ?? '' }}" class="h-20 w-20 rounded object-cover">
                                            @else
                                                <div class="flex h-20 w-20 items-center justify-center rounded bg-gray-100 text-gray-400">No image
                                                </div>
                                            @endif

                                            <div class="min-w-0">
                                                <a href="{{ route('products.show', $item['id']) }}" class="truncate font-medium hover:underline">{{ $item['name'] ?? 'Product' }}</a>
                                                <p class="text-sm" aria-live="polite" data-cart-qty="{{ $item['id'] }}">x{{ $qty }}</p>
                                                <div class="mt-1 text-sm text-gray-500">{{ formatUSD($item['price'] ?? 0) }}</div>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <div class="mr-2 text-sm text-gray-700" data-cart-line-total="{{ $item['id'] }}" data-cart-subtotal="true">{{ formatUSD($lineTotal) }}</div>
                                            <button type="button" x-on:click.prevent="handleChange($event, '{{ route('cart.update') }}', {{ $item['id'] }}, -1)" class="inline-flex h-6 w-6 items-center justify-center rounded bg-gray-100 text-xs">−</button>
                                            <button type="button" x-on:click.prevent="handleChange($event, '{{ route('cart.update') }}', {{ $item['id'] }}, 1)" class="inline-flex h-6 w-6 items-center justify-center rounded bg-gray-100 text-xs">+</button>
                                            <button type="button" x-on:click.prevent="openConfirm('remove', '{{ route('cart.remove') }}', {{ $item['id'] }}, $event)" class="inline-flex h-6 w-6 items-center justify-center rounded bg-red-500 text-xs text-white">✕</button>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <aside class="lg:col-span-1">
                        <div id="cart-summary" class="space-y-4 rounded-lg bg-white p-6 shadow">
                            <h3 class="text-lg font-semibold">Order summary</h3>
                                <div class="flex items-center justify-between text-sm text-gray-600">
                                <div>Subtotal</div>
                                <div data-cart-subtotal="true">{{ formatUSD($subtotal) }}</div>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-600">
                                <div>Shipping fee</div>
                                <div>Free</div>
                            </div>
                            <div class="flex items-center justify-between border-t pt-3">
                                <div class="text-base font-semibold">Total</div>
                                <div class="text-base font-semibold" data-cart-total="true">{{ formatUSD($subtotal) }}</div>
                            </div>

                            @auth
                                <a href="{{ route('checkout.index') }}" class="bg-main-red inline-block w-full rounded-md px-4 py-3 text-center text-white">Proceed to Checkout</a>
                            @else
                                <a href="{{ route('login') }}?redirect={{ urlencode(route('checkout.index')) }}" class="bg-main-red inline-block w-full rounded-md px-4 py-3 text-center text-white">Login to checkout</a>
                            @endauth

                            <a href="{{ url('/') }}" class="block text-center text-sm text-gray-600">Continue shopping</a>
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
                        await window.cartPerformUpdate({
                            url: url,
                            product_id: product_id,
                            quantity: quantity
                        });
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
                            await window.cartPerformRemove({
                                url: this.url,
                                product_id: this.product_id
                            });
                        } else if (this.action === 'update') {
                            await window.cartPerformUpdate({
                                url: this.url,
                                product_id: this.product_id,
                                quantity: this.quantity ?? 0
                            });
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
