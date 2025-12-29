@extends('layouts.default')
@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Checkout</h1>
            <p class="text-sm text-gray-600">Review shipping and payment details before placing your order.</p>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            <div class="lg:col-span-8">
                <form id="checkout-form" method="POST" action="{{ route('checkout.store') }}" class="space-y-6">
                    @csrf
                    <div id="checkout-error-msg" class="hidden mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700 text-sm"></div>
                    <input type="hidden" name="address_id" id="selected-address-id" value="" />

                    <div class="rounded-2xl bg-white p-6 shadow">
                        <h2 class="mb-4 text-lg font-semibold text-gray-800">Shipping information</h2>

                        @if ($errors->any())
                            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                                <ul class="list-inside list-disc text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (isset($addresses) && $addresses->count())
                            <div class="mb-4">
                                <div class="mb-2 text-sm font-medium">Saved addresses</div>
                                <div class="space-y-2">
                                    @foreach ($addresses as $addr)
                                        <label class="flex cursor-pointer items-start gap-3 rounded-lg border p-3 hover:shadow-sm" data-addr='@json($addr)'>
                                            <input type="radio" name="use_address" value="{{ $addr->id }}" class="address-radio mt-1" />
                                            <div class="flex-1 text-sm">
                                                <div class="font-medium">{{ $addr->first_name }} {{ $addr->last_name }}</div>
                                                <div class="text-gray-600">{{ $addr->street_address }}, {{ $addr->city }} {{ $addr->state }} {{ $addr->zip_code }}</div>
                                                <div class="mt-1 text-gray-500">{{ $addr->phone }}</div>
                                            </div>
                                        </label>
                                    @endforeach
                                    <label class="flex items-center gap-3 rounded-lg border p-3">
                                        <input type="radio" name="use_address" value="new" class="address-radio" checked />
                                        <span class="text-sm text-gray-700">Use a new address</span>
                                    </label>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">First name</label>
                                <input name="first_name" placeholder="First name" value="{{ old('first_name') }}" required class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 outline-none focus:ring-2 focus:ring-main-red/20 focus:border-main-red transition" />
                                @error('first_name')
                                    <p class="mt-1 text-xs text-red-600" data-error-for="first_name">{{ $message }}</p>
                                @else
                                    <p class="mt-1 text-xs text-red-600 hidden" data-error-for="first_name"></p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Last name</label>
                                <input name="last_name" placeholder="Last name" value="{{ old('last_name') }}" class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 outline-none focus:ring-2 focus:ring-main-red/20 focus:border-main-red transition" />
                                @error('last_name')
                                    <p class="mt-1 text-xs text-red-600" data-error-for="last_name">{{ $message }}</p>
                                @else
                                    <p class="mt-1 text-xs text-red-600 hidden" data-error-for="last_name"></p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone</label>
                                <input name="phone" placeholder="Phone" value="{{ old('phone') }}" required class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 outline-none focus:ring-2 focus:ring-main-red/20 focus:border-main-red transition" />
                                @error('phone')
                                    <p class="mt-1 text-xs text-red-600" data-error-for="phone">{{ $message }}</p>
                                @else
                                    <p class="mt-1 text-xs text-red-600 hidden" data-error-for="phone"></p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Street address</label>
                                <input name="street_address" placeholder="Street address" value="{{ old('street_address') }}" required class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 outline-none focus:ring-2 focus:ring-main-red/20 focus:border-main-red transition" />
                                @error('street_address')
                                    <p class="mt-1 text-xs text-red-600" data-error-for="street_address">{{ $message }}</p>
                                @else
                                    <p class="mt-1 text-xs text-red-600 hidden" data-error-for="street_address"></p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">City</label>
                                <input name="city" placeholder="City" value="{{ old('city') }}" required class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 outline-none focus:ring-2 focus:ring-main-red/20 focus:border-main-red transition" />
                                @error('city')
                                    <p class="mt-1 text-xs text-red-600" data-error-for="city">{{ $message }}</p>
                                @else
                                    <p class="mt-1 text-xs text-red-600 hidden" data-error-for="city"></p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">State / Province</label>
                                <input name="state" placeholder="State / Province" value="{{ old('state') }}" class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 outline-none focus:ring-2 focus:ring-main-red/20 focus:border-main-red transition" />
                            </div>
                            <div class="sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700">ZIP code</label>
                                <input name="zip_code" placeholder="ZIP code" value="{{ old('zip_code') }}" class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 outline-none focus:ring-2 focus:ring-main-red/20 focus:border-main-red transition" />
                            </div>
                        </div>

                        <script>
                            (function() {
                                function clearFields() {
                                    ['first_name', 'last_name', 'phone', 'street_address', 'city', 'state', 'zip_code'].forEach(function(n) {
                                        var el = document.querySelector('[name="' + n + '"]').closest ? document.querySelector('[name="' + n + '"]').value = '' : null;
                                    });
                                    document.getElementById('selected-address-id').value = '';
                                }

                                function fillFields(addr) {
                                    try {
                                        document.querySelector('input[name="first_name"]').value = addr.first_name || '';
                                        document.querySelector('input[name="last_name"]').value = addr.last_name || '';
                                        document.querySelector('input[name="phone"]').value = addr.phone || '';
                                        document.querySelector('input[name="street_address"]').value = addr.street_address || '';
                                        document.querySelector('input[name="city"]').value = addr.city || '';
                                        document.querySelector('input[name="state"]').value = addr.state || '';
                                        document.querySelector('input[name="zip_code"]').value = addr.zip_code || '';
                                        document.getElementById('selected-address-id').value = addr.id || '';
                                    } catch (e) {
                                        console.error(e);
                                    }
                                }
                                document.querySelectorAll('.address-radio').forEach(function(el) {
                                    el.addEventListener('change', function(e) {
                                        if (this.value === 'new') {
                                            clearFields();
                                            return;
                                        }
                                        var label = this.closest('label');
                                        if (!label) return;
                                        try {
                                            var addr = JSON.parse(label.getAttribute('data-addr'));
                                            fillFields(addr);
                                        } catch (err) {
                                            console.error(err);
                                        }
                                    });
                                });
                            })();
                        </script>

                        <h3 class="mt-6 text-lg font-medium text-gray-800">Payment method</h3>
                        <div class="mt-3 space-y-3">
                            <label class="flex items-center gap-3 rounded-lg border p-3">
                                <input type="radio" name="payment_method" value="cod" checked />
                                <div>
                                    <div class="font-medium">Cash on Delivery (COD)</div>
                                    <div class="text-sm text-gray-500">Pay with cash when your order is delivered.</div>
                                </div>
                            </label>
                            @error('payment_method')
                                <p class="mt-1 text-xs text-red-600" data-error-for="payment_method">{{ $message }}</p>
                            @else
                                <p class="mt-1 text-xs text-red-600 hidden" data-error-for="payment_method"></p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="bg-main-red hover:bg-main-red-hover inline-flex w-full items-center justify-center rounded-full px-6 py-3 font-semibold text-white shadow transition">Place Order</button>
                        </div>
                    </div>
                </form>
            </div>

            <aside class="lg:col-span-4">
                <div class="sticky top-6 rounded-2xl bg-white p-6 shadow">
                    <h3 class="mb-4 text-lg font-semibold">Your Order</h3>
                    <div class="mb-4">
                        @if (empty($cart) || count($cart) === 0)
                            <div class="p-4 text-sm text-gray-600">Your cart is empty.</div>
                        @else
                            <div class="p-2">
                                <ul class="divide-y">
                                    @php $subtotal = 0; @endphp
                                    @foreach ($cart as $item)
                                        @php
                                            $lineTotal = $item['total'] ?? $item['price'] * ($item['qty'] ?? 1);
                                            $subtotal += $lineTotal;
                                        @endphp
                                        <li data-product-id="{{ $item['id'] }}" data-price="{{ $item['price'] ?? 0 }}" class="flex items-center justify-between py-3">
                                            <div class="flex min-w-0 items-center gap-3">
                                                @if (!empty($item['image']))
                                                    @php
                                                        $img = $item['image'] ?? '';
                                                        $imgSrc = \Illuminate\Support\Str::startsWith($img, 'static/') ? asset($img) : asset('storage/' . ltrim($img, '/'));
                                                    @endphp
                                                    <img src="{{ $imgSrc }}" class="h-12 w-12 rounded object-cover" alt="{{ $item['name'] }}">
                                                @endif
                                                <div class="min-w-0">
                                                    <div class="truncate text-sm font-medium text-gray-900">{{ $item['name'] ?? 'Product' }}</div>
                                                    <div class="text-xs text-gray-500">x<span data-cart-qty>{{ $item['qty'] ?? 1 }}</span></div>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <div class="flex items-center gap-2">
                                                    <div class="mr-2 text-sm text-gray-700">{{ formatUSD($item['total'] ?? $item['price'] * ($item['qty'] ?? 1)) }}</div>
                                                    <button type="button" onclick="window.cartPerformUpdate({ url: '{{ route('cart.update') }}', product_id: {{ $item['id'] }}, quantity: {{ max(($item['qty'] ?? 1) - 1, 0) }} })" class="inline-flex h-6 w-6 items-center justify-center rounded bg-gray-100 text-xs">−</button>
                                                    <button type="button" onclick="window.cartPerformUpdate({ url: '{{ route('cart.update') }}', product_id: {{ $item['id'] }}, quantity: {{ ($item['qty'] ?? 1) + 1 }} })" class="inline-flex h-6 w-6 items-center justify-center rounded bg-gray-100 text-xs">+</button>
                                                    <button type="button" onclick="window.cartPerformRemove({ url: '{{ route('cart.remove') }}', product_id: {{ $item['id'] }} })" class="inline-flex h-6 w-6 items-center justify-center rounded bg-red-500 text-xs text-white">✕</button>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="border-t pt-4 text-sm text-gray-700">
                                <div class="mb-2 flex justify-between"><span>Subtotal</span><span class="font-medium" data-checkout-subtotal>{{ formatUSD($subtotal) }}</span></div>
                            </div>
                        @endif
                    </div>
                </div>
            </aside>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('checkout-form');
            if (!form) return;
            const submitBtn = form.querySelector('button[type="submit"]');
            const errorBox = document.getElementById('checkout-error-msg');

            function usingSavedAddress() {
                const sel = form.querySelector('input[name="use_address"]:checked');
                return sel && sel.value !== 'new';
            }

            function validateForm() {
                // If user selected saved address, we allow submit (server will verify)
                if (usingSavedAddress()) return true;
                let ok = true;
                const requiredNames = ['first_name','phone','street_address','city','payment_method'];
                requiredNames.forEach(name => {
                    const el = form.querySelector('[name="' + name + '"]');
                    if (!el) return;
                    if (el.type === 'radio') {
                        const any = form.querySelector('[name="' + name + '"]:checked');
                        if (!any) ok = false;
                    } else if ((el.value || '').trim() === '') {
                        ok = false;
                    }
                });
                return ok;
            }

            function setFieldError(name, msg) {
                try {
                    const el = form.querySelector('[data-error-for="' + name + '"]');
                    if (el) {
                        el.textContent = msg || 'This field is required.';
                        el.classList.remove('hidden');
                    }
                    const input = form.querySelector('[name="' + name + '"]');
                    if (input && input.type !== 'radio') input.classList.add('border-red-300');
                    if (input && input.type === 'radio') {
                        const first = form.querySelector('[name="' + name + '"]');
                        if (first) first.closest('label')?.classList.add('ring-2','ring-red-300');
                    }
                } catch (e) {}
            }

            function clearFieldError(name) {
                try {
                    const el = form.querySelector('[data-error-for="' + name + '"]');
                    if (el) { el.textContent = ''; el.classList.add('hidden'); }
                    const input = form.querySelector('[name="' + name + '"]');
                    if (input && input.type !== 'radio') input.classList.remove('border-red-300');
                    if (input && input.type === 'radio') {
                        form.querySelectorAll('[name="' + name + '"]').forEach(r => r.closest('label')?.classList.remove('ring-2','ring-red-300'));
                    }
                } catch (e) {}
            }

            function updateState() {
                const valid = validateForm();
                // Keep the button clickable so users get validation feedback on click
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.toggle('opacity-50', !valid);
                    submitBtn.classList.toggle('cursor-not-allowed', !valid);
                }
                if (errorBox) {
                    if (valid) {
                        errorBox.classList.add('hidden');
                        errorBox.textContent = '';
                    }
                }
                // clear individual field errors when they become valid
                ['first_name','phone','street_address','city','payment_method'].forEach(name => {
                    const el = form.querySelector('[name="' + name + '"]');
                    if (!el) return;
                    if (el.type === 'radio') {
                        const any = form.querySelector('[name="' + name + '"]:checked');
                        if (any) clearFieldError(name);
                    } else {
                        if ((el.value || '').trim() !== '') clearFieldError(name);
                    }
                });
            }

            // initial state
            updateState();

            // re-check on input changes
            form.addEventListener('input', function(e) { if (e.target && e.target.name) clearFieldError(e.target.name); updateState(); });
            form.addEventListener('change', function(e) { if (e.target && e.target.name) clearFieldError(e.target.name); updateState(); });

            form.addEventListener('submit', function (e) {
                if (!validateForm()) {
                    e.preventDefault();
                    if (errorBox) {
                        errorBox.textContent = 'Please fill in all required fields before placing your order.';
                        errorBox.classList.remove('hidden');
                    }
                    // highlight missing fields and show inline messages
                    const requiredNames = ['first_name','phone','street_address','city','payment_method'];
                    requiredNames.forEach(name => {
                        const el = form.querySelector('[name="' + name + '"]');
                        if (!el) return;
                        if (el.type === 'radio') {
                            const any = form.querySelector('[name="' + name + '"]:checked');
                            if (!any) {
                                setFieldError(name, 'Please select an option.');
                            }
                        } else if ((el.value || '').trim() === '') {
                            setFieldError(name, 'This field is required.');
                        }
                    });
                }
            });
        });
    </script>

@endsection
