@extends('layouts.default')

@section('content')
<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-semibold">Order Details #{{ $order->id }}</h1>
            <a href="{{ route('orders.my') }}" class="text-sm text-gray-600 hover:underline">Back to orders</a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="rounded-lg bg-white p-6 shadow">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-sm text-gray-500">Order ID: <span class="font-medium">#{{ $order->id }}</span></div>
                            <div class="text-sm text-gray-500">Order date: <span class="font-medium">{{ $order->created_at->format('Y-m-d H:i') }}</span></div>
                        </div>
                        <div class="text-right">
                            <div class="text-base font-semibold">{{ number_format($order->grand_total ?? $order->total ?? 0, 0, ',', '.') }} USD</div>
                            <div class="mt-2">
                                @include('partials.order-status', ['status' => $order->status])
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 border-t pt-4">
                        <h3 class="text-lg font-medium mb-3">Products</h3>
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        @php
                                            $imgSrc = null;
                                            if ($item->product) {
                                                if (is_array($item->product->images) && count($item->product->images)) {
                                                    $imgSrc = asset($item->product->images[0]);
                                                } elseif (!empty($item->product->image)) {
                                                    $imgSrc = Str::startsWith($item->product->image, 'static/') ? asset($item->product->image) : asset('storage/' . ltrim($item->product->image, '/'));
                                                }
                                            }
                                        @endphp
                                        @if($imgSrc)
                                            <img src="{{ $imgSrc }}" alt="{{ $item->product?->name ?? '' }}" class="w-16 h-16 object-cover rounded"/>
                                        @else
                                            <img src="{{ asset('static/images/Manhwa-category.png') }}" alt="placeholder" class="w-16 h-16 object-cover rounded"/>
                                        @endif
                                        <div>
                                            <div class="font-medium">{{ $item->product?->name ?? 'Product removed' }}</div>
                                            <div class="text-sm text-gray-500">x{{ $item->quantity }} × {{ number_format($item->price ?? 0, 0, ',', '.') }} USD</div>
                                        </div>
                                    </div>
                                    <div class="text-sm font-medium">{{ number_format($item->total_amount ?? ($item->price * $item->quantity) ?? 0, 0, ',', '.') }} USD</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if($order->notes)
                        <div class="mt-6 border-t pt-4">
                            <h3 class="text-sm font-medium text-gray-700">Notes</h3>
                            <div class="text-sm text-gray-600 mt-2">{{ $order->notes }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <aside class="lg:col-span-1">
                <div class="rounded-lg bg-white p-6 shadow">
                    <h3 class="text-lg font-medium mb-3">Shipping information</h3>
                    @if($order->address)
                        <div class="text-sm text-gray-700">
                            <div class="font-medium">{{ $order->address->first_name }} {{ $order->address->last_name }}</div>
                            <div>{{ $order->address->street_address }}</div>
                            <div>{{ $order->address->city }}{{ $order->address->state ? ', ' . $order->address->state : '' }} {{ $order->address->zip_code }}</div>
                            <div class="mt-2 text-sm">Phone: {{ $order->address->phone }}</div>
                        </div>
                    @else
                        <div class="text-sm text-gray-600">No shipping address available.</div>
                    @endif

                    <div class="mt-6 border-t pt-4">
                        <div class="flex justify-between text-sm text-gray-700">
                            <div>Subtotal</div>
                            <div>{{ number_format($order->items->sum(fn($i)=> $i->total_amount ?? ($i->price * $i->quantity)), 0, ',', '.') }} USD</div>
                        </div>
                        <div class="flex justify-between text-sm text-gray-700 mt-2">
                            <div>Shipping fee</div>
                            <div>{{ number_format($order->shipping_amount ?? 0, 0, ',', '.') }} USD</div>
                        </div>
                        <div class="flex justify-between text-base font-semibold mt-4">
                            <div>Total</div>
                            <div>{{ number_format($order->grand_total ?? $order->total ?? 0, 0, ',', '.') }} USD</div>
                        </div>
                        @can('requestCancellation', $order)
                            <div class="mt-4">
                                <button id="openCancellationModal" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-3 rounded">Request cancellation</button>
                            </div>
                        @endcan
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection

@push('modals')
    <div id="cancellationModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
        <div class="w-full max-w-lg bg-white rounded shadow p-6">
            <h3 class="text-lg font-medium mb-3">Request order cancellation</h3>
            <p class="text-sm text-gray-600 mb-4">Please provide a reason for the cancellation. Our team will review and contact you.</p>
            <textarea id="cancellationReason" class="w-full border rounded p-2 mb-4" rows="4" placeholder="Reason for cancellation..."></textarea>
            <div class="flex justify-end gap-2">
                <button id="cancelClose" class="px-4 py-2 rounded border">Close</button>
                <button id="submitCancellation" class="px-4 py-2 rounded bg-red-600 text-white">Submit request</button>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        (() => {
            const openBtn = document.getElementById('openCancellationModal');
            const submitBtn = document.getElementById('submitCancellation');

            console.log('cancellation script init', { openBtn: !!openBtn, submitBtn: !!submitBtn, orderId: {{ $order->id }} });

            function getModal() { return document.getElementById('cancellationModal'); }
            function getCloseBtn() { return document.getElementById('cancelClose'); }

            // If the direct listener wasn't attached (e.g., DOM changes, turbolinks), use delegated handler as fallback
            document.addEventListener('click', (e) => {
                const target = e.target;
                if (target && target.closest && target.closest('#openCancellationModal')) {
                    console.log('openCancellationModal clicked via delegation for order', {{ $order->id }});
                    const modal = getModal();
                    if (modal) {
                        modal.classList.remove('hidden');
                        console.log('modal classList after remove hidden:', modal.className);
                        console.log('modal computed display:', window.getComputedStyle(modal).display);
                        if (window.getComputedStyle(modal).display === 'none') {
                            console.warn('modal still has display:none — forcing inline display:flex');
                            modal.style.display = 'flex';
                        }
                    }
                }
                if (target && target.closest && target.closest('#cancelClose')) {
                    console.log('cancellationModal closed via delegation for order', {{ $order->id }});
                    const modal = getModal();
                    if (modal) {
                        modal.classList.add('hidden');
                        // also clear inline style if present
                        modal.style.display = '';
                    }
                }
            });

            // Do not bail out early — the modal element may be injected later by Blade stacks.

            // Attach direct listeners if elements are present now
            const modalNow = getModal();
            if (openBtn && modalNow) {
                openBtn.addEventListener('click', () => {
                    console.log('openCancellationModal clicked - opening modal for order', {{ $order->id }});
                    const modal = getModal();
                    if (modal) {
                        modal.classList.remove('hidden');
                        console.log('modal classList after remove hidden:', modal.className);
                        console.log('modal computed display:', window.getComputedStyle(modal).display);
                        if (window.getComputedStyle(modal).display === 'none') {
                            console.warn('modal still has display:none — forcing inline display:flex');
                            modal.style.display = 'flex';
                        }
                    }
                });
            }

            const closeNow = getCloseBtn();
            if (closeNow) {
                closeNow.addEventListener('click', () => {
                    console.log('cancellationModal closed (cancel) for order', {{ $order->id }});
                    const modal = getModal();
                    if (modal) modal.classList.add('hidden');
                });
            }

            function attachSubmitHandler(element) {
                if (!element) return;
                element.addEventListener('click', async () => {
                    element.disabled = true;
                    const reasonEl = document.getElementById('cancellationReason');
                    const reason = reasonEl ? reasonEl.value.trim() : '';
                    if (!reason) {
                        alert('Please enter a reason for cancellation.');
                        element.disabled = false;
                        return;
                    }

                    try {
                        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                        const token = tokenMeta ? tokenMeta.getAttribute('content') : '';
                        const url = "{{ route('orders.cancellations.store', ['order' => $order->id]) }}";
                        const payload = { reason };
                        console.log('Submitting cancellation request', { orderId: {{ $order->id }}, url, tokenPresent: !!token, payload });

                        const resp = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });

                        console.log('Request finished, status:', resp.status);
                        const json = await resp.json().catch(() => ({}));
                        console.log('Response body:', json);

                        if (!resp.ok) {
                            console.error('Cancellation request failed', resp.status, json);
                            alert(json.message || 'Failed to submit request');
                            element.disabled = false;
                            return;
                        }

                        console.log('Cancellation request success', json);
                        alert('Cancellation request submitted. We will review it shortly.');
                        const modal = getModal();
                        if (modal) modal.classList.add('hidden');
                        // Optionally refresh page to update UI
                        location.reload();
                    } catch (err) {
                        console.error('Error while submitting cancellation request:', err);
                        alert('An error occurred. Please try again later.');
                        element.disabled = false;
                    }
                });
            }

            // Attach now if present, otherwise poll briefly for the modal-inserted button
            if (submitBtn) {
                attachSubmitHandler(submitBtn);
            } else {
                const poll = setInterval(() => {
                    const s = document.getElementById('submitCancellation');
                    if (s) {
                        clearInterval(poll);
                        attachSubmitHandler(s);
                    }
                }, 200);
                // stop polling after 5s
                setTimeout(() => clearInterval(poll), 5000);
            }

            // Quick-cancel removed: use the modal-trigger button only.
        })();
    </script>
@endpush

