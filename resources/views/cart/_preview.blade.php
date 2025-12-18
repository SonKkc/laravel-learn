@if(empty($cart) || count($cart) === 0)
    <div class="p-4 text-sm text-gray-600">Giỏ hàng trống.</div>
@else
    <div class="p-4">
        <ul class="divide-y">
            @php $subtotal = 0; @endphp
            @foreach($cart as $item)
                @php $subtotal += ($item['total'] ?? ($item['price'] * ($item['qty'] ?? 1))); @endphp
                <li class="flex items-center justify-between py-2">
                    <a href="{{ route('products.show', $item['id']) }}" class="flex min-w-0 items-center gap-3 text-sm text-gray-900 hover:underline">
                        @if(!empty($item['image']))
                            @php
                                $img = $item['image'] ?? '';
                                $imgSrc = \Illuminate\Support\Str::startsWith($img, 'static/') ? asset($img) : asset('storage/' . ltrim($img, '/'));
                            @endphp
                            <img src="{{ $imgSrc }}" class="h-10 w-10 rounded object-cover" alt="{{ $item['name'] }}">
                        @endif
                        <div class="min-w-0">
                            <div class="truncate font-medium">{{ $item['name'] ?? 'Sản phẩm' }}</div>
                            <div class="text-xs text-gray-500">x<span class="cart-item-qty">{{ $item['qty'] ?? 1 }}</span></div>
                        </div>
                    </a>
                    <div class="flex items-center gap-2">
                        <div class="text-sm text-gray-700 mr-2">{{ number_format($item['total'] ?? ($item['price'] * ($item['qty'] ?? 1)), 0, ',', '.') }}</div>
                        <button type="button" onclick="window.cartPerformUpdate({ url: '{{ route('cart.update') }}', product_id: {{ $item['id'] }}, quantity: {{ max(($item['qty'] ?? 1) - 1, 0) }} })" class="inline-flex h-6 w-6 items-center justify-center rounded bg-gray-100 text-xs">−</button>
                        <button type="button" onclick="window.cartPerformUpdate({ url: '{{ route('cart.update') }}', product_id: {{ $item['id'] }}, quantity: {{ ($item['qty'] ?? 1) + 1 }} })" class="inline-flex h-6 w-6 items-center justify-center rounded bg-gray-100 text-xs">+</button>
                        <button type="button" onclick="window.cartPerformRemove({ url: '{{ route('cart.remove') }}', product_id: {{ $item['id'] }} })" class="inline-flex h-6 w-6 items-center justify-center rounded bg-red-500 text-xs text-white">✕</button>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="mt-4 flex items-center justify-between border-t pt-3">
            <div class="text-sm text-gray-600">Tổng:</div>
            <div class="text-base font-semibold">{{ number_format($subtotal, 0, ',', '.') }}</div>
        </div>

        <div class="mt-3">
            <a href="{{ route('cart.index') }}" class="inline-block w-full rounded-md bg-main-red px-4 py-2 text-center text-white">Xem giỏ hàng</a>
        </div>
    </div>
@endif
