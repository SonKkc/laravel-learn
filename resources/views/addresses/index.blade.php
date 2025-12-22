@extends('layouts.default')
@section('content')
    <h1 class="text-2xl font-semibold mb-4">Địa chỉ của tôi</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="font-semibold mb-3">Thêm địa chỉ mới</h2>
            <form method="POST" action="{{ route('addresses.store') }}">
                @csrf
                <div class="grid grid-cols-1 gap-2">
                    <input name="first_name" placeholder="First name" class="input" />
                    <input name="last_name" placeholder="Last name" class="input" />
                    <input name="phone" placeholder="Phone" class="input" />
                    <input name="street_address" placeholder="Street address" class="input" />
                    <input name="city" placeholder="City" class="input" />
                    <input name="state" placeholder="State" class="input" />
                    <input name="zip_code" placeholder="ZIP" class="input" />
                    <div class="mt-2">
                        <button class="btn btn-primary">Lưu địa chỉ</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="font-semibold mb-3">Địa chỉ đã lưu</h2>
            @forelse($addresses as $addr)
                <div class="border rounded p-3 mb-2">
                    <div class="font-medium">{{ $addr->first_name }} {{ $addr->last_name }}</div>
                    <div class="text-sm text-gray-600">{{ $addr->street_address }}, {{ $addr->city }} {{ $addr->state }} {{ $addr->zip_code }} — {{ $addr->phone }}</div>
                    <div class="mt-2 flex items-center gap-2">
                        <form method="POST" action="{{ route('addresses.destroy', $addr) }}" onsubmit="return confirm('Xoá địa chỉ này?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger text-sm">Xoá</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-sm text-gray-500">Bạn chưa có địa chỉ nào.</div>
            @endforelse
        </div>
    </div>

@endsection
