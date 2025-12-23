@extends('layouts.default')
@section('content')
    <section class="products-section py-8">
        <div class="container mx-auto px-4">
            <div class="mb-8 flex flex-col gap-6">
                <div class="text-center">
                    <h1 class="mb-2 text-3xl font-bold text-gray-900">All Products</h1>
                    <p class="text-gray-600">Discover featured, newest and best-selling products.</p>
                </div>
                <form method="GET" class="flex flex-wrap items-center justify-center gap-3" x-data="{ category: '{{ request('category') }}', brand: '{{ request('brand') }}', sort: '{{ request('sort') }}' }">
                    <!-- Category Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <input type="hidden" name="category" :value="category">
                        <div @click="open = !open" tabindex="0" role="button" aria-haspopup="listbox" :aria-expanded="open ? 'true' : 'false'" class="flex min-w-[160px] cursor-pointer select-none items-center justify-between rounded-full border border-[#f3f4f6] bg-white px-4 py-2 text-sm shadow focus:outline-none hover:scale-105 active:scale-107 transition-transform duration-300 ease-out">
                            <span x-text="category ? ({{ json_encode($categories->pluck('name', 'id')) }}[category] ?? 'All categories') : 'All categories'"></span>
                            <svg class="ml-2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="hide-scrollbar absolute left-0 z-50 mt-2 max-h-60 w-full overflow-auto rounded-lg border border-[#f3f4f6] bg-white py-1 shadow-lg" x-cloak>
                            <a href="#" @click.prevent="category = ''; open = false" :class="['block rounded px-4 py-2 text-sm hover:bg-gray-100', !category ? 'bg-gray-100' : '']">All categories</a>
                            @foreach ($categories as $category)
                                <a href="#" @click.prevent="category = '{{ $category->id }}'; open = false" :class="['block rounded px-4 py-2 text-sm hover:bg-gray-100', category == '{{ $category->id }}' ? 'bg-gray-100' : '']">{{ $category->name }}</a>
                            @endforeach
                        </div>
                    </div>
                    <!-- Brand Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <input type="hidden" name="brand" :value="brand">
                        <div @click="open = !open" tabindex="0" role="button" aria-haspopup="listbox" :aria-expanded="open ? 'true' : 'false'" class="flex min-w-[160px] cursor-pointer select-none items-center justify-between rounded-full border border-[#f3f4f6] bg-white px-4 py-2 text-sm shadow focus:outline-none hover:scale-105 active:scale-107 transition-transform duration-300 ease-out">
                            <span x-text="brand ? ({{ json_encode($brands->pluck('name', 'id')) }}[brand] ?? 'All brands') : 'All brands'"></span>
                            <svg class="ml-2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="hide-scrollbar absolute left-0 z-50 mt-2 max-h-60 w-full overflow-auto rounded-lg border border-[#f3f4f6] bg-white py-1 shadow-lg" x-cloak>
                            <a href="#" @click.prevent="brand = ''; open = false" :class="['block rounded px-4 py-2 text-sm hover:bg-gray-100', !brand ? 'bg-gray-100' : '']">All brands</a>
                            @foreach ($brands as $brand)
                                <a href="#" @click.prevent="brand = '{{ $brand->id }}'; open = false" :class="['block rounded px-4 py-2 text-sm hover:bg-gray-100', brand == '{{ $brand->id }}' ? 'bg-gray-100' : '']">{{ $brand->name }}</a>
                            @endforeach
                        </div>
                    </div>
                    <!-- Sort Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <input type="hidden" name="sort" :value="sort">
                        <div @click="open = !open" tabindex="0" role="button" aria-haspopup="listbox" :aria-expanded="open ? 'true' : 'false'" class="flex min-w-[160px] cursor-pointer select-none items-center justify-between rounded-full border border-[#f3f4f6] bg-white px-4 py-2 text-sm shadow focus:outline-none hover:scale-105 active:scale-107 transition-transform duration-300 ease-out">
                            <span x-text="sort ? (sort === 'newest' ? 'Newest' : sort === 'price_asc' ? 'Price: Low to High' : sort === 'price_desc' ? 'Price: High to Low' : sort === 'best_seller' ? 'Best sellers' : 'Sort') : 'Sort'"></span>
                            <svg class="ml-2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="hide-scrollbar absolute left-0 z-50 mt-2 max-h-60 w-full overflow-auto rounded-lg border border-[#f3f4f6] bg-white py-1 shadow-lg" x-cloak>
                            <a href="#" @click.prevent="sort = ''; open = false" :class="['block rounded px-4 py-2 text-sm hover:bg-gray-100', !sort ? 'bg-gray-100' : '']">Sort</a>
                            <a href="#" @click.prevent="sort = 'newest'; open = false" :class="['block rounded px-4 py-2 text-sm hover:bg-gray-100', sort == 'newest' ? 'bg-gray-100' : '']">Newest</a>
                            <a href="#" @click.prevent="sort = 'price_asc'; open = false" :class="['block rounded px-4 py-2 text-sm hover:bg-gray-100', sort == 'price_asc' ? 'bg-gray-100' : '']">Price: Low to High</a>
                            <a href="#" @click.prevent="sort = 'price_desc'; open = false" :class="['block rounded px-4 py-2 text-sm hover:bg-gray-100', sort == 'price_desc' ? 'bg-gray-100' : '']">Price: High to Low</a>
                            <a href="#" @click.prevent="sort = 'best_seller'; open = false" :class="['block rounded px-4 py-2 text-sm hover:bg-gray-100', sort == 'best_seller' ? 'bg-gray-100' : '']">Best sellers</a>
                        </div>
                    </div>
                    <button type="submit" class="bg-primary hover:bg-primary-dark rounded-full px-6 py-2 text-sm shadow transition focus:outline-none focus:outline-none hover:scale-105 active:scale-107 transition-transform duration-300 ease-out">Filter</button>
                </form>
            </div>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($products as $product)
                    <x-product-card :product="$product" :categoryName="$product->category->name ?? ''" />
                @empty
                    <div class="col-span-3 py-12 text-center text-gray-500">
                        No products matched.
                    </div>
                @endforelse
            </div>
            @if (method_exists($products, 'links'))
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
