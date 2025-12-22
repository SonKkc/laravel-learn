@extends('layouts.default')
@section('content')
    <h1 class="text-2xl font-semibold mb-4">Checkout</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('checkout.store') }}">
                @csrf
                <input type="hidden" name="address_id" id="selected-address-id" value="" />
                <div class="bg-white rounded-lg p-4 shadow">
                    <h2 class="font-semibold mb-3">Thông tin giao hàng</h2>

                    @if(isset($addresses) && $addresses->count())
                        <div class="mb-3">
                            <div class="text-sm font-medium mb-2">Địa chỉ đã lưu</div>
                            <div class="space-y-2">
                                @foreach($addresses as $addr)
                                    <label class="block border rounded p-2 cursor-pointer" data-addr='@json($addr)'>
                                        <input type="radio" name="use_address" value="{{ $addr->id }}" class="address-radio mr-2" />
                                        <span class="font-medium">{{ $addr->first_name }} {{ $addr->last_name }}</span>
                                        <div class="text-sm text-gray-600">{{ $addr->street_address }}, {{ $addr->city }} {{ $addr->state }} {{ $addr->zip_code }} — {{ $addr->phone }}</div>
                                    </label>
                                @endforeach
                                <label class="block p-2">
                                    <input type="radio" name="use_address" value="new" class="address-radio mr-2" checked />
                                    Sử dụng địa chỉ mới
                                </label>
                            </div>
                        </div>
                    @endif
                    <div class="grid grid-cols-2 gap-3">
                        <input name="first_name" placeholder="First name" value="{{ old('first_name') }}" class="input" />
                        <input name="last_name" placeholder="Last name" value="{{ old('last_name') }}" class="input" />
                        <input name="phone" placeholder="Phone" value="{{ old('phone') }}" class="input" />
                        <input name="street_address" placeholder="Street address" value="{{ old('street_address') }}" class="input" />
                        <input name="city" placeholder="City" value="{{ old('city') }}" class="input" />
                        <input name="state" placeholder="State" value="{{ old('state') }}" class="input" />
                        <input name="zip_code" placeholder="ZIP" value="{{ old('zip_code') }}" class="input" />
                    </div>

                    <script>
                        (function(){
                            function clearFields(){
                                document.querySelector('input[name="first_name"]').value = '';
                                document.querySelector('input[name="last_name"]').value = '';
                                document.querySelector('input[name="phone"]').value = '';
                                document.querySelector('input[name="street_address"]').value = '';
                                document.querySelector('input[name="city"]').value = '';
                                document.querySelector('input[name="state"]').value = '';
                                document.querySelector('input[name="zip_code"]').value = '';
                                document.getElementById('selected-address-id').value = '';
                            }
                            function fillFields(addr){
                                document.querySelector('input[name="first_name"]').value = addr.first_name || '';
                                document.querySelector('input[name="last_name"]').value = addr.last_name || '';
                                document.querySelector('input[name="phone"]').value = addr.phone || '';
                                document.querySelector('input[name="street_address"]').value = addr.street_address || '';
                                document.querySelector('input[name="city"]').value = addr.city || '';
                                document.querySelector('input[name="state"]').value = addr.state || '';
                                document.querySelector('input[name="zip_code"]').value = addr.zip_code || '';
                                document.getElementById('selected-address-id').value = addr.id || '';
                            }
                            document.querySelectorAll('.address-radio').forEach(function(el){
                                el.addEventListener('change', function(e){
                                    if (this.value === 'new') { clearFields(); return; }
                                    // find the parent label with data-addr
                                    var label = this.closest('label');
                                    if (!label) return;
                                    try { var addr = JSON.parse(label.getAttribute('data-addr')); fillFields(addr); } catch(err){ console.error(err); }
                                });
                            });
                        })();
                    </script>

                    <h2 class="font-semibold mt-4 mb-2">Phương thức thanh toán</h2>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="payment_method" value="cod" checked />
                            <span>Cash on Delivery (COD)</span>
                        </label>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-primary">Đặt hàng</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg p-4 shadow">
                <h3 class="font-semibold mb-3">Đơn hàng</h3>
                @include('cart._preview', ['cart' => $cart ?? []])
            </div>
        </div>
    </div>

@endsection

