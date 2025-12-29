<article class="content__card">
    <div class="content__thumbnail">
        <a href="{{ route('products.show', $product->id) }}" class="">
            @php
                $imgSrc = null;
                if (is_array($product->images) && !empty($product->images) && $product->images[0]) {
                    $img = $product->images[0] ?? '';
                    $imgSrc = \Illuminate\Support\Str::startsWith($img, 'static/') ? asset($img) : asset('storage/' . ltrim($img, '/'));
                }
            @endphp
            <img alt="{{ $product->name }}" loading="lazy" decoding="async" data-nimg="fill"
                class="absolute inset-0 h-full w-full object-cover object-center transition-all duration-300"
                style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent"
                src="{{ $imgSrc ?? asset('static/images/Manhwa-category.png') }}">
        </a>
    </div>
    <div class="content__card-content">
        <div>
            <a href="#" class="content__card-tag">{{ $categoryName }}</a>
            <a href="{{ route('products.show', $product->id) }}" class="content__card-title">
                <h3>{{ $product->name }}</h3>
            </a>
            <p class="content__card-description line-clamp-4">{{ $product->description }}</p>
        </div>
        <div class="content__card-meta">
            <div class="ms-3">
                {{-- <a href="#" class="content__card-author">{{ $product->brand->name ?? '' }}</a> --}}
                <p class="content__card-time">
                    <a href="#" class="content__card-author">{{ $product->brand->name ?? '' }}</a>
                    {{-- <time datetime="{{ $product->created_at }}" class="">{{ $product->created_at->format('F d, Y') }}</time> --}}
                    <span class="mx-0.5">·</span>
                    <span class="text-main-red">{{ formatUSD($product->price) }}</span>
                </p>
            </div>
        </div>
    </div>
</article>
