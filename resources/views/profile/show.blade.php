@extends('layouts.default')

@section('content')
<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="col-span-1">
                <div class="rounded-lg bg-white p-6 shadow">
                    <h3 class="text-lg font-semibold mb-4">Thông tin tài khoản</h3>
                    <p class="text-sm text-gray-600 mb-2"><strong>Tên:</strong> {{ $user->name }}</p>
                    <p class="text-sm text-gray-600 mb-2"><strong>Email:</strong> {{ $user->email }}</p>
                    @if(isset($user->email_verified_at))
                        <p class="text-sm text-gray-600 mb-2"><strong>Đã xác thực email:</strong> {{ $user->email_verified_at ? $user->email_verified_at->format('Y-m-d H:i') : 'Chưa' }}</p>
                    @endif
                    <div class="mt-4">
                        <a href="{{ route('profile.show') }}" class="inline-block rounded-md bg-main-red px-4 py-2 text-white">Chỉnh sửa hồ sơ</a>
                    </div>
                </div>
            </div>

            <div class="col-span-1 lg:col-span-2">
                <div class="rounded-lg bg-white p-6 shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Đơn hàng của tôi</h3>
                        <div class="text-sm text-gray-500">Tổng: {{ $orders->total() }} đơn</div>
                    </div>

                    {{-- Tabs --}}
                    @php
                        $tabs = [
                            'all' => 'Tất cả',
                            'pending' => 'Chờ xử lý',
                            'processing' => 'Đang xử lý',
                            'completed' => 'Hoàn thành',
                            'cancelled' => 'Đã hủy',
                        ];
                        $currentStatus = request()->query('status', 'all');
                    @endphp

                    <div class="mb-4">
                    <nav class="flex gap-2 overflow-auto">
                        @foreach($tabs as $key => $label)
                            @php $count = $statusCounts[$key] ?? ($key === 'all' ? array_sum($statusCounts) : 0); @endphp
                            <a href="{{ route('profile.show', array_filter(['status' => $key])) }}" data-status="{{ $key }}" class="profile-tab px-3 py-1 rounded-full text-sm border {{ $currentStatus === $key ? 'bg-main-red text-white border-main-red' : 'bg-white text-gray-700' }}">
                                {{ $label }} ({{ $count }})
                            </a>
                        @endforeach
                    </nav>
                </div>

                    <div id="orders-list">
                    @if($orders->isEmpty())
                        <p class="text-sm text-gray-600">Không tìm thấy đơn hàng cho tab này.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($orders as $order)
                                <div class="border rounded p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="text-sm text-gray-500">Đơn hàng #{{ $order->id }}</div>
                                            <div class="text-base font-semibold">Tổng: {{ number_format($order->grand_total, 0, ',', '.') }} {{ $order->currency ?? '' }}</div>
                                            <div class="text-sm text-gray-500">Trạng thái: {{ $order->status }}</div>
                                            <div class="text-sm text-gray-400">Ngày: {{ $order->created_at->format('Y-m-d H:i') }}</div>
                                        </div>
                                        <div class="text-right">
                                            <a href="{{ route('orders.show', $order) }}" class="text-main-red hover:underline">Xem chi tiết</a>
                                        </div>
                                    </div>

                                    @if($order->items->isNotEmpty())
                                        <div class="mt-3">
                                            <h4 class="text-sm font-medium mb-2">Sản phẩm</h4>
                                            <ul class="text-sm text-gray-700 list-disc list-inside">
                                                @foreach($order->items as $item)
                                                    <li>{{ $item->product?->name ?? 'Sản phẩm đã xóa' }} — x{{ $item->quantity }} — {{ number_format($item->total_amount, 0, ',', '.') }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $orders->links() }}
                        </div>
                    @endif
                    </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.profile-tab');
    const ordersContainerId = 'orders-list';

    async function fetchAndReplace(url, push = true) {
        try {
            const container = document.getElementById(ordersContainerId);
            if (!container) return;

            // show loading state
            const original = container.innerHTML;
            container.innerHTML = '<div class="p-6 text-center text-sm text-gray-500">Đang tải...</div>';

            const res = await fetch(url, { credentials: 'same-origin' });
            if (!res.ok) throw new Error('Network response was not ok');
            const text = await res.text();

            // Parse returned HTML and extract the orders-list
            const parser = new DOMParser();
            const doc = parser.parseFromString(text, 'text/html');
            const newContainer = doc.getElementById(ordersContainerId);
            if (newContainer) {
                container.innerHTML = newContainer.innerHTML;
            } else {
                // fallback: restore original
                container.innerHTML = original;
            }

            if (push) {
                history.pushState({ url: url }, '', url);
            }
        } catch (err) {
            console.error(err);
        }
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function (e) {
            e.preventDefault();
            const url = this.getAttribute('href');

            // update active classes immediately
            tabs.forEach(t => t.classList.remove('bg-main-red', 'text-white', 'border-main-red'));
            this.classList.add('bg-main-red', 'text-white', 'border-main-red');

            fetchAndReplace(url, true);
        });
    });

    // Handle browser navigation (back/forward)
    window.addEventListener('popstate', function (event) {
        const url = location.href;
        fetchAndReplace(url, false);
        // update active tab
        const params = new URLSearchParams(location.search);
        const status = params.get('status') || 'all';
        tabs.forEach(t => {
            if (t.dataset.status === status) {
                t.classList.add('bg-main-red', 'text-white', 'border-main-red');
            } else {
                t.classList.remove('bg-main-red', 'text-white', 'border-main-red');
            }
        });
    });
});
</script>
@endpush
