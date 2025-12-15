@extends('layouts.default')

@section('content')
<section class="py-10 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">
            <!-- Product Images -->
            <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
                <img src="{{ is_array($product->images) && count($product->images) ? asset($product->images[0]) : asset('static/images/Manhwa-category.png') }}" alt="{{ $product->name }}" class="w-full max-w-md h-80 object-cover rounded-lg mb-4">
                @if(is_array($product->images) && count($product->images) > 1)
                <div class="flex gap-2 mt-2">
                    @foreach($product->images as $img)
                        <img src="{{ asset($img) }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded border hover:ring-2 hover:ring-primary cursor-pointer transition">
                    @endforeach
                </div>
                @endif
            </div>
            <!-- Product Info -->
            <div class="bg-white rounded-lg shadow p-8">
                <h1 class="text-3xl font-bold mb-2 text-gray-900">{{ $product->name }}</h1>
                <div class="flex items-center gap-3 mb-4">
                    <span class="inline-block bg-primary/10 text-primary px-3 py-1 rounded-full text-xs font-semibold">{{ $product->category->name ?? 'Danh mục?' }}</span>
                    <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">{{ $product->brand->name ?? 'Thương hiệu?' }}</span>
                </div>
                <div class="flex items-center gap-4 mb-4">
                    <span class="text-2xl font-bold text-main-red">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                    @if($product->on_sale)
                        <span class="text-xs text-white bg-green-500 px-2 py-1 rounded">Đang giảm giá</span>
                    @endif
                </div>
                <div class="mb-4 text-gray-700 leading-relaxed">
                    {{ $product->description }}
                </div>
                <div class="flex items-center gap-6 mb-6">
                    <span class="text-sm text-gray-500">Lượt xem: <span class="font-semibold text-gray-700">{{ $product->views }}</span></span>
                    <span class="text-sm text-gray-500">Kho: <span class="font-semibold text-gray-700">{{ $product->in_stock ? 'Còn hàng' : 'Hết hàng' }}</span></span>
                </div>
                <form class="flex items-center gap-4">
                    <input type="number" min="1" max="{{ $product->quantity ?? 99 }}" value="1" class="w-20 rounded border px-3 py-2 text-center focus:outline-none focus:ring-2 focus:ring-primary">
                    <button type="button" class="rounded bg-primary px-6 py-2 text-white font-semibold shadow hover:bg-primary-dark transition">Thêm vào giỏ hàng</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
