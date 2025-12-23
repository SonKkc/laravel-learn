@extends('layouts.default')

@section('content')
<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="col-span-1">
                <div class="rounded-lg bg-white p-6 shadow-lg transition-shadow duration-200">
                    <h3 class="text-lg font-semibold mb-4">Account information</h3>
                    <p class="text-sm text-gray-600 mb-2"><strong>Name:</strong> {{ $user->name }}</p>
                    <p class="text-sm text-gray-600 mb-2"><strong>Email:</strong> {{ $user->email }}</p>
                    @if(isset($user->email_verified_at))
                        <p class="text-sm text-gray-600 mb-2"><strong>Email verified:</strong> {{ $user->email_verified_at ? $user->email_verified_at->format('Y-m-d H:i') : 'No' }}</p>
                    @endif
                    <div class="mt-4">
                        <button id="btn-open-profile-modal" type="button" class="inline-flex items-center gap-2 rounded-full bg-main-red px-4 py-2 text-white transform transition-transform duration-200 ease-out active:scale-95 hover:scale-105">Edit profile</button>
                    </div>
                </div>

                <div class="mt-6 rounded-lg bg-white p-6 shadow-lg transition-shadow duration-200">
                        <h3 class="text-lg font-semibold mb-4">My addresses</h3>
                    <div class="mt-4">
                        @if($addresses->isEmpty())
                            <div class="text-sm text-gray-500">You don't have any addresses yet.</div>
                        @else
                            <div class="space-y-3">
                                @foreach($addresses as $addr)
                                    <div class="border rounded p-3 hover:bg-gray-50 transition-colors duration-150">
                                        <div class="font-medium">{{ $addr->first_name }} {{ $addr->last_name }}</div>
                                        <div class="text-sm text-gray-600">{{ $addr->street_address }}, {{ $addr->city }} {{ $addr->state }} {{ $addr->zip_code }} — {{ $addr->phone }}</div>
                                        <div class="mt-2">
                                            <div class="flex gap-2">
                                                <button type="button" class="btn btn-secondary btn-edit-address inline-flex items-center gap-2" data-id="{{ $addr->id }}" data-first_name="{{ e($addr->first_name) }}" data-last_name="{{ e($addr->last_name) }}" data-phone="{{ e($addr->phone) }}" data-street_address="{{ e($addr->street_address) }}" data-city="{{ e($addr->city) }}" data-state="{{ e($addr->state) }}" data-zip_code="{{ e($addr->zip_code) }}">Edit</button>
                                                <form method="POST" action="{{ route('addresses.destroy', $addr) }}" onsubmit="return confirm('Delete this address?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-danger text-sm">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                        <div class="mt-4">
                        <button id="btn-open-address-modal" type="button" class="inline-flex items-center gap-2 rounded-full bg-main-red px-4 py-2 text-white transform transition-transform duration-200 ease-out active:scale-95 hover:scale-105">Add new address</button>
                    </div>
                </div>
            </div>

            <div class="col-span-1 lg:col-span-2">
                <div class="rounded-lg bg-white p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">My Orders</h3>
                        <div class="text-sm text-gray-500">Total: {{ $orders->total() }} orders</div>
                    </div>

                    {{-- Tabs --}}
                    @php
                        $tabs = [
                            'all' => 'All',
                            'new' => 'New',
                            'processing' => 'Processing',
                            'completed' => 'Completed',
                            'cancelled' => 'Cancelled',
                        ];
                    @endphp

                    <div class="mb-4">
                        <nav class="flex gap-2 overflow-auto">
                            @foreach($tabs as $key => $label)
                                @php $count = ($statusCounts[$key] ?? ($key === 'all' ? array_sum($statusCounts ?? []) : 0)); @endphp
                                @php $activeStatus = $status ?? request('status','all'); @endphp
                                <a href="{{ route('profile.show', array_filter(['status' => $key])) }}" data-status="{{ $key }}" class="profile-tab px-3 py-1 rounded-full text-sm border {{ ($activeStatus === $key) ? 'bg-main-red text-white border-main-red' : 'bg-white text-gray-700' }}">
                                    {{ $label }} ({{ $count }})
                                </a>
                            @endforeach
                        </nav>
                    </div>

                    <div id="orders-list">
                        @if($orders->isEmpty())
                            <p class="text-sm text-gray-600">No orders found for this tab.</p>
                        @else
                            <div class="space-y-4">
                                @foreach($orders as $order)
                                    <div class="border rounded p-4 mb-3 hover:shadow transition-shadow duration-150">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                    <div class="font-medium">Order #{{ $order->id }}</div>
                                                    <div class="text-sm text-gray-600">{{ $order->created_at->format('Y-m-d H:i') }}</div>
                                            </div>
                                            <div class="text-sm text-gray-700">{{ strtoupper($order->status) }}</div>
                                        </div>
                                            <div class="mt-3 text-sm text-gray-600">
                                            <div>Total: {{ number_format($order->grand_total ?? $order->total ?? 0, 0, ',', '.') }} USD</div>
                                        </div>

                                        @if($order->items->isNotEmpty())
                                            <div class="mt-3">
                                                <h4 class="text-sm font-medium mb-2">Products</h4>
                                                <ul class="text-sm text-gray-700 list-disc list-inside">
                                                    @foreach($order->items as $item)
                                                        <li>{{ $item->product?->name ?? 'Product removed' }} — x{{ $item->quantity }} — {{ number_format($item->total_amount, 0, ',', '.') }}</li>
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
    const tabs = Array.from(document.querySelectorAll('.profile-tab'));
    const ordersContainerId = 'orders-list';
    const currentProfileStatus = '{{ $status ?? request('status','all') }}';

    // Class strategy: set full className instead of adding/removing multiple utility classes
    const tabBase = 'profile-tab px-3 py-1 rounded-full text-sm border';
    const tabActive = ' bg-main-red text-white border-main-red';
    const tabInactive = ' bg-white text-gray-700';

    // Initialize tab classes according to server-provided status
    tabs.forEach(t => {
        const s = t.dataset.status || '';
        t.className = tabBase + (s === currentProfileStatus ? tabActive : tabInactive);
    });

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

            // Re-attach pagination handlers for links inside the replaced content
            attachPaginationHandlers();
        } catch (err) {
            console.error(err);
        }
    }

    // Attach AJAX handlers to pagination links inside the orders container
    function attachPaginationHandlers() {
        const container = document.getElementById(ordersContainerId);
        if (!container) return;
        // Find Laravel pagination links inside container
        const pagelinks = container.querySelectorAll('.pagination a, nav[role="navigation"] a');
        pagelinks.forEach(a => {
            // avoid duplicate handlers
            if (a.dataset.ajaxHandled) return;
            a.addEventListener('click', function (ev) {
                ev.preventDefault();
                const url = this.href;
                fetchAndReplace(url, true);
            });
            a.dataset.ajaxHandled = '1';
        });
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function (e) {
            e.preventDefault();
            const url = this.getAttribute('href');

            // set all tabs to inactive then set clicked tab active
            tabs.forEach(t => t.className = tabBase + tabInactive);
            this.className = tabBase + tabActive;

            fetchAndReplace(url, true);
        });
    });

    // Handle browser navigation (back/forward)
    window.addEventListener('popstate', function (event) {
        const url = location.href;
        fetchAndReplace(url, false);
        // update active tab by setting className
        const params = new URLSearchParams(location.search);
        const status = params.get('status') || 'all';
        tabs.forEach(t => t.className = tabBase + (t.dataset.status === status ? tabActive : tabInactive));
    });
});
</script>
@endpush

@push('modals')
<!-- Profile edit modal -->
<div id="modal-profile" class="fixed inset-0 z-50 hidden items-center justify-center min-h-screen bg-black/40" role="dialog" aria-modal="true" aria-labelledby="profile-modal-title">
    <div class="bg-white rounded-lg shadow-xl max-w-lg w-full p-0 mx-4 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-lg font-semibold" id="profile-modal-title">Chỉnh sửa hồ sơ</h3>
            <button type="button" id="modal-profile-close" aria-label="Đóng" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 8.586l4.95-4.95a1 1 0 111.414 1.414L11.414 10l4.95 4.95a1 1 0 11-1.414 1.414L10 11.414l-4.95 4.95a1 1 0 11-1.414-1.414L8.586 10 3.636 5.05A1 1 0 115.05 3.636L10 8.586z" clip-rule="evenodd"/></svg>
            </button>
        </div>

        <form id="modal-profile-form" method="POST" action="{{ route('profile.update') }}" class="px-6 py-5">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="text-sm font-medium text-gray-700">Tên</label>
                    <input name="name" value="{{ old('name', $user->name) }}" class="input mt-1 w-full" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Email</label>
                    <input name="email" type="email" value="{{ old('email', $user->email) }}" class="input mt-1 w-full" required />
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 mt-6">
                <button type="button" id="modal-profile-cancel" class="px-4 py-2 rounded border text-sm">Huỷ</button>
                <button type="submit" id="modal-profile-submit" class="inline-flex items-center px-4 py-2 rounded bg-main-red text-white text-sm">
                    <svg id="modal-profile-spinner" class="animate-spin h-4 w-4 mr-2 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <span id="modal-profile-submit-text">Lưu</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Address modal (create / edit) -->
<div id="modal-address" class="fixed inset-0 z-50 hidden items-center justify-center min-h-screen bg-black/40" role="dialog" aria-modal="true" aria-labelledby="address-modal-title">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full p-0 mx-4 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-lg font-semibold" id="address-modal-title">Thêm địa chỉ</h3>
            <button type="button" id="modal-address-close" aria-label="Đóng" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 8.586l4.95-4.95a1 1 0 111.414 1.414L11.414 10l4.95 4.95a1 1 0 11-1.414 1.414L10 11.414l-4.95 4.95a1 1 0 11-1.414-1.414L8.586 10 3.636 5.05A1 1 0 115.05 3.636L10 8.586z" clip-rule="evenodd"/></svg>
            </button>
        </div>

        <form id="modal-address-form" method="POST" action="{{ route('addresses.store') }}" class="px-6 py-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="text-sm font-medium text-gray-700">Họ</label>
                    <input name="first_name" id="addr-first_name" placeholder="Nguyễn" class="input mt-1 w-full" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Tên</label>
                    <input name="last_name" id="addr-last_name" placeholder="Văn A" class="input mt-1 w-full" />
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Số điện thoại</label>
                    <input name="phone" id="addr-phone" placeholder="09xxxxxxxx" class="input mt-1 w-full" required />
                    <p class="text-xs text-gray-400 mt-1">Dùng để liên hệ khi giao hàng</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">ZIP / Mã bưu chính</label>
                    <input name="zip_code" id="addr-zip_code" placeholder="Ví dụ: 700000" class="input mt-1 w-full" />
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Địa chỉ (số nhà, tên đường)</label>
                    <input name="street_address" id="addr-street_address" placeholder="Số nhà, tên đường, phường/xã" class="input mt-1 w-full" required />
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Thành phố</label>
                    <input name="city" id="addr-city" placeholder="TP. Hồ Chí Minh" class="input mt-1 w-full" required />
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Quận / Tỉnh</label>
                    <input name="state" id="addr-state" placeholder="Quận 1" class="input mt-1 w-full" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6">
                <button type="button" id="modal-address-cancel" class="px-4 py-2 rounded border text-sm">Huỷ</button>
                <button type="submit" id="modal-address-submit" class="inline-flex items-center px-4 py-2 rounded bg-main-red text-white text-sm">
                    <svg id="modal-address-spinner" class="animate-spin h-4 w-4 mr-2 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <span id="modal-address-submit-text">Lưu địa chỉ</span>
                </button>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Profile modal
    const modalProfile = document.getElementById('modal-profile');
    const modalProfileClose = document.getElementById('modal-profile-close');
    const modalProfileCancel = document.getElementById('modal-profile-cancel');
    const modalProfileForm = document.getElementById('modal-profile-form');
    const modalProfileSubmit = document.getElementById('modal-profile-submit');
    const modalProfileSpinner = document.getElementById('modal-profile-spinner');

    function openProfileModal() {
        modalProfile.classList.remove('hidden');
        modalProfile.classList.add('flex');
        document.querySelector('#modal-profile input[name="name"]').focus();
    }

    function closeProfileModal() {
        modalProfile.classList.add('hidden');
        modalProfile.classList.remove('flex');
    }

    document.getElementById('btn-open-profile-modal').addEventListener('click', openProfileModal);
    modalProfileClose.addEventListener('click', closeProfileModal);
    modalProfileCancel.addEventListener('click', closeProfileModal);
    modalProfile.addEventListener('click', function (e) { if (e.target === modalProfile) closeProfileModal(); });

    // Show spinner on submit and disable submit to prevent double submits
    modalProfileForm.addEventListener('submit', function () {
        modalProfileSpinner.classList.remove('hidden');
        modalProfileSubmit.setAttribute('disabled', 'disabled');
    });

    // Address modal (create / edit)
    const modalAddress = document.getElementById('modal-address');
    const addrForm = document.getElementById('modal-address-form');
    const modalAddressClose = document.getElementById('modal-address-close');
    const modalAddressCancel = document.getElementById('modal-address-cancel');
    const modalAddressSubmit = document.getElementById('modal-address-submit');
    const modalAddressSpinner = document.getElementById('modal-address-spinner');

    function openAddressModal() {
        addrForm.action = '{{ route('addresses.store') }}';
        addrForm.querySelector('input[name="_method"]')?.remove();
        document.getElementById('address-modal-title').textContent = 'Thêm địa chỉ';
        ['first_name','last_name','phone','street_address','city','state','zip_code'].forEach(k => document.getElementById('addr-' + k).value = '');
        modalAddress.classList.remove('hidden');
        modalAddress.classList.add('flex');
        // focus first field
        document.getElementById('addr-first_name').focus();
    }

    function closeAddressModal() {
        modalAddress.classList.add('hidden');
        modalAddress.classList.remove('flex');
    }

    document.getElementById('btn-open-address-modal').addEventListener('click', openAddressModal);
    modalAddressClose.addEventListener('click', closeAddressModal);
    modalAddressCancel.addEventListener('click', closeAddressModal);
    modalAddress.addEventListener('click', function (e) { if (e.target === modalAddress) closeAddressModal(); });

    // Edit address buttons
    document.querySelectorAll('.btn-edit-address').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = btn.getAttribute('data-id');
            const payload = {
                first_name: btn.getAttribute('data-first_name') || '',
                last_name: btn.getAttribute('data-last_name') || '',
                phone: btn.getAttribute('data-phone') || '',
                street_address: btn.getAttribute('data-street_address') || '',
                city: btn.getAttribute('data-city') || '',
                state: btn.getAttribute('data-state') || '',
                zip_code: btn.getAttribute('data-zip_code') || '',
            };
            // set form to update
            addrForm.action = '/addresses/' + id;
            // ensure _method input exists for PUT
            if (!addrForm.querySelector('input[name="_method"]')) {
                const m = document.createElement('input'); m.type = 'hidden'; m.name = '_method'; m.value = 'PUT'; addrForm.appendChild(m);
            }
            document.getElementById('address-modal-title').textContent = 'Chỉnh sửa địa chỉ';
            Object.keys(payload).forEach(k => document.getElementById('addr-' + k).value = payload[k]);
            modalAddress.classList.remove('hidden');
            modalAddress.classList.add('flex');
            document.getElementById('addr-first_name').focus();
        });
    });

    // Show spinner on submit and disable submit to prevent double submits
    addrForm.addEventListener('submit', function () {
        modalAddressSpinner.classList.remove('hidden');
        modalAddressSubmit.setAttribute('disabled', 'disabled');
    });
});
</script>
@endpush
