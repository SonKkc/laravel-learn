@extends('layouts.default')

@section('content')
    <section class="category-detail-section py-8">
        <div class="container_md">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $category->name }}</h1>
                <p class="text-gray-600">{{ $category->description ?? 'Discover featured products in this category.' }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($products as $product)
                    <x-product-card :product="$product" :categoryName="$category->name" />
                @empty
                    <div class="col-span-3 text-center text-gray-500 py-12">
                        No products found in this category.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
