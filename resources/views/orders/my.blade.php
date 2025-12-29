@extends('layouts.default')

@section('content')
<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold">My Orders</h1>
            <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:underline">Back to home</a>
        </div>

        @if($orders->isEmpty())
            <div class="rounded-lg bg-white p-6 shadow text-center text-gray-600">You have no orders yet.</div>
        @else
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="rounded-lg bg-white p-4 shadow hover:shadow-md transition-shadow duration-150 flex items-center justify-between">
                        <div>
                            <div class="text-sm text-gray-500">Order #{{ $order->id }} &middot; <span class="text-xs text-gray-400">{{ $order->created_at->format('Y-m-d') }}</span></div>
                            <div class="text-base font-semibold mt-1">{{ formatUSD($order->grand_total ?? $order->total ?? 0) }}</div>
                        </div>

                        <div class="flex items-center gap-4">
                            @include('partials.order-status', ['status' => $order->status])
                            <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center gap-2 text-main-red hover:underline">View details
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L13.586 11H4a1 1 0 110-2h9.586l-3.293-3.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach

                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

