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
                                <span class="inline-block px-2 py-1 rounded-full text-xs font-medium {{ $order->status === 'completed' ? 'bg-green-100 text-green-700' : ($order->status === 'processing' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">{{ ucfirst($order->status) }}</span>
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
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection

