@extends('layouts.default')
@section('content')
<section class="categories-section py-8">
    <div class="container_md">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">All Categories</h1>
            <p class="text-gray-600">Discover featured product categories.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($categories as $category)
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-4 flex flex-col">
                    <a href="{{ route('categories.show', $category->id) }}" class="block mb-4">
                        <img src="{{ asset('static/images/Manhwa-category.png') }}" alt="{{ $category->name }}" class="w-full h-40 object-cover rounded">
                    </a>
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 mb-1">
                                <a href="{{ route('categories.show', $category->id) }}">{{ $category->name }}</a>
                            </h2>
                            <p class="text-gray-700 text-sm mb-2 line-clamp-2">{{ $category->description }}</p>
                        </div>
                        <div class="flex items-center justify-end mt-2">
                            <a href="{{ route('categories.show', $category->id) }}" class="inline-block px-3 py-1 bg-primary text-white rounded hover:bg-primary-dark transition text-sm">View products</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center text-gray-500 py-12">
                    No categories available.
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
