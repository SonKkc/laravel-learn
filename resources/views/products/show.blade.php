@extends('layouts.default')

@section('content')
    <section class="bg-gray-50 py-10">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 items-start gap-10 md:grid-cols-2">
                <!-- Product Images -->
                <div class="flex flex-col items-center rounded-lg bg-white p-6 shadow w-full">
                    @if(is_array($product->images) && count($product->images) > 1)
                        <!-- Swiper main image gallery -->
                        <div class="w-full h-96 overflow-hidden">
                            <div class="swiper productSwiper">
                                <div class="swiper-wrapper !bg-transparent">
                                    @foreach($product->images as $img)
                                        <div class="swiper-slide">
                                            <img src="{{ asset('storage/'.$img) }}" alt="{{ $product->name }}" class="h-auto w-full object-cover rounded-lg !bg-transparent select-none" />
                                        </div>
                                    @endforeach
                                </div>
                                <div class="swiper-pagination !text-main-red"></div>
                                <div class="swiper-button-next !size-6 !text-main-red"></div>
                                <div class="swiper-button-prev !size-6 !text-main-red"></div>
                            </div>
                        </div>
                        <!-- Swiper thumbs -->
                        <div class="mt-4 flex gap-2 justify-center" id="swiper-thumbs">
                            @foreach($product->images as $img)
                                <img src="{{ asset('storage/'.$img) }}" alt="{{ $product->name }}" class="h-16 w-16 object-cover rounded shadow border border-[#f3f4f6] bg-white cursor-pointer transition hover:scale-105 active:scale-107 swiper-thumb select-none">
                            @endforeach
                        </div>
                        <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            if (window.Swiper) {
                                let swiperInstance = null;
                                swiperInstance = new Swiper('.productSwiper', {
                                    spaceBetween: 15,
                                    loop: true,
                                    pagination: { el: '.swiper-pagination', clickable: true },
                                    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                                    on: {
                                        init: function(swiper) {
                                            const thumbs = document.querySelectorAll('#swiper-thumbs .swiper-thumb');
                                            thumbs.forEach(t => t.classList.remove('scale-110', 'ring-2', 'ring-main-red'));
                                            if (thumbs[swiper.realIndex]) {
                                                thumbs[swiper.realIndex].classList.add('scale-110', 'ring-2', 'ring-main-red');
                                            }
                                        },
                                        slideChange: function(swiper) {
                                            const thumbs = document.querySelectorAll('#swiper-thumbs .swiper-thumb');
                                            thumbs.forEach(t => t.classList.remove('scale-110', 'ring-2', 'ring-main-red'));
                                            if (thumbs[swiper.realIndex]) {
                                                thumbs[swiper.realIndex].classList.add('scale-110', 'ring-2', 'ring-main-red');
                                            }
                                        }
                                    }
                                });
                                // Thumb click event
                                document.querySelectorAll('#swiper-thumbs .swiper-thumb').forEach((thumb, idx) => {
                                    thumb.addEventListener('click', function() {
                                        if (swiperInstance) swiperInstance.slideToLoop(idx);
                                    });
                                });
                            }
                        });
                        </script>
                    @else
                        <img src="{{ is_array($product->images) && count($product->images) ? asset($product->images[0]) : asset('static/images/Manhwa-category.png') }}" alt="{{ $product->name }}" class="mb-4 h-80 w-full max-w-md rounded-lg object-cover">
                    @endif
                </div>
                <!-- Product Info -->
                <div class="rounded-lg bg-white p-8 shadow">
                    <h1 class="mb-2 text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
                    <div class="mb-4 flex items-center gap-3">
                        <span class="bg-primary/10 text-primary inline-block rounded-full px-3 py-1 text-xs font-semibold">{{ $product->category->name ?? 'Danh mục?' }}</span>
                        <span class="inline-block rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">{{ $product->brand->name ?? 'Thương hiệu?' }}</span>
                    </div>
                    <div class="mb-4 flex items-center gap-4">
                        <span class="text-main-red text-2xl font-bold">{{ number_format($product->price, 0, ',', '.') }} USD</span>
                        @if ($product->on_sale)
                            <span class="rounded bg-green-500 px-2 py-1 text-xs text-white">Đang giảm giá</span>
                        @endif
                    </div>
                    <div class="mb-4 leading-relaxed text-gray-700">
                        {{ $product->description }}
                    </div>
                    <div class="mb-6 flex items-center gap-6">
                        <span class="text-sm text-gray-500">Lượt xem: <span class="font-semibold text-gray-700">{{ $product->views }}</span></span>
                        <span class="text-sm text-gray-500">Kho: <span class="font-semibold text-gray-700">{{ $product->in_stock ? 'Còn hàng' : 'Hết hàng' }}</span></span>
                        <span class="text-sm text-gray-500">Số lượng: <span class="font-semibold text-gray-700">{{ $product->quantity ?? 0 }}</span></span>
                    </div>
                    <form class="flex items-center gap-4" x-data="{ qty: 1, min: 1, max: {{ $product->quantity ?? 99 }} }">
                        <template x-if="max > 0">
                            <div class="flex flex-col gap-3 w-full">
                                <div class="flex items-center justify-between w-full">
                                    <div class="flex items-center gap-3 bg-gray-100 rounded-full px-3 py-2 shadow-inner">
                                        <button type="button" @click="if(qty > min) { qty = qty - 1 }" :disabled="qty <= min" class="h-9 w-9 rounded-full bg-white hover:bg-gray-200 text-2xl font-bold flex items-center justify-center shadow disabled:opacity-40 disabled:cursor-not-allowed transition-all duration-200 hover:scale-110 active:scale-95">&#8722;</button>
                                        <span class="inline-block w-14 text-center text-xl font-bold text-gray-900 select-none" x-text="qty"></span>
                                        <input type="hidden" :min="min" :max="max" x-model.number="qty" name="quantity" />
                                        <button type="button" @click="qty = Math.min(max, qty + 1)" :disabled="qty >= max" class="h-9 w-9 rounded-full bg-white hover:bg-gray-200 text-2xl font-bold flex items-center justify-center shadow disabled:opacity-40 disabled:cursor-not-allowed transition-all duration-200 hover:scale-110 active:scale-95">&#43;</button>
                                    </div>
                                    <span class="ml-4 text-sm text-gray-500">Kho: <span class="font-semibold text-main-red">{{ $product->quantity }}</span></span>
                                </div>
                                <button type="button"
                                    class="w-full bg-main-red hover:bg-main-red-hover rounded-full px-6 py-3 font-bold text-white shadow-lg text-lg tracking-wide
                                    transition-all duration-300 ease-out hover:scale-103 hover:shadow-xl
                                    focus-visible:outline-none active:scale-95">
                                    Thêm vào giỏ hàng
                                </button>
                            </div>
                        </template>
                        <template x-if="max === 0">
                            <div class="flex flex-col gap-3 w-full opacity-60 pointer-events-none select-none">
                                <button type="button" disabled class="w-full bg-gray-300 rounded-full px-6 py-3 font-bold text-white shadow-lg text-lg tracking-wide">Hết hàng</button>
                            </div>
                        </template>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
