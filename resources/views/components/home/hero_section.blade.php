<!--heading page-->
<section class="hero">
    <div class="hero__container">
        @php
            $mainProduct = $products->first();
            $recentProducts = $products->slice(1, 5);
        @endphp
        @if($mainProduct)
        <article class="hero__main-content">
            <a href="{{ route('products.show', $mainProduct->id) }}" class="hero__thumbnail">
                @php
                    $mainImg = null;
                    if ($mainProduct->images && is_array($mainProduct->images) && !empty($mainProduct->images[0])) {
                        $img = $mainProduct->images[0] ?? '';
                        $mainImg = \Illuminate\Support\Str::startsWith($img, 'static/') ? asset($img) : asset('storage/' . ltrim($img, '/'));
                    }
                @endphp
                <img alt="{{ $mainProduct->name }}" loading="lazy" decoding="async"
                    data-nimg="fill"
                    class="absolute h-full w-full inset-0 object-center object-cover transition-all duration-300 hover:scale-105"
                    style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent"
                    src="{{ $mainImg ?? asset('static/images/Manhwa-category.png') }}">
            </a>
            <div class="hero__content">
                <a href="#" class="hero__tag">{{ $mainProduct->category->name ?? '' }}</a>
                <a href="{{ route('products.show', $mainProduct->id) }}" class="block mt-3 decoration-inherit">
                    <h2 class="hero__title">
                        {{ $mainProduct->name }}</h2>
                    <div>
                        <p class="hero__description">{{ $mainProduct->description ?? '' }}</p>
                    </div>
                </a>
            </div>

            <div class="hero__meta">
                <div class="flex items-center flex-wrap gap-2">
                    {{-- <a href="#" class="hero__author-name">{{ $mainProduct->brand->name ?? '' }}</a> --}}
                    <p class="hero__publish-time">
                         <a href="#" class="hero__author-name">{{ $mainProduct->brand->name ?? '' }}</a>
                        {{-- <time datetime="{{ $mainProduct->created_at }}" class="">{{ $mainProduct->created_at->format('F d, Y') }}</time> --}}
                        <span class="mx-0.5">·</span>
                        <span class="text-main-red">{{ formatUSD($mainProduct->price) }}</span>
                    </p>
                </div>
            </div>
        </article>
        @endif

        <div class="hero__sidebar">
            <h3 class="section_header">
                Recent Products
            </h3>
            <div class="hero__stories-list">
                @foreach($recentProducts as $product)
                <article class="hero__story-item">
                    <a href="{{ route('products.show', $product->id) }}" class="hero__story-thumbnail">
                        <div class="hero__story-thumbnail-container article_img-left">
                            @php
                                $recentImg = null;
                                if ($product->images && is_array($product->images) && !empty($product->images[0])) {
                                    $img = $product->images[0] ?? '';
                                    $recentImg = \Illuminate\Support\Str::startsWith($img, 'static/') ? asset($img) : asset('storage/' . ltrim($img, '/'));
                                }
                            @endphp
                            <img alt="{{ $product->name }}" loading="lazy"
                                decoding="async" data-nimg="fill"
                                class="absolute h-full w-full inset-0 object-center object-cover transition-all duration-300 hover:scale-105"
                                style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent"
                                src="{{ $recentImg ?? asset('static/images/Manhwa-category.png') }}">
                        </div>
                    </a>
                    <div class="hero__story-content">
                        <a href="#" class="hero__story-tag">
                            {{ $product->category->name ?? '' }}
                        </a>
                        <a href="{{ route('products.show', $product->id) }}" class="hero__story-title">
                            <h3 class="">
                                {{ $product->name }}</h3>
                        </a>
                        <div class="hero__story-meta">
                            <div class="hero__story-meta-container">
                                <div class="hero__story-author-time">
                                    <a href="#" class="font-medium text-[#374151] hover:underline">{{ $product->brand->name ?? '' }}</a>
                                    <span class="xl:inline lg:hidden text-[#6b7280]">
                                        <span class="mx-0.5">·</span>
                                        {{-- <time datetime="{{ $product->created_at }}" class="text-[#6b7280]">{{ $product->created_at->format('F d, Y') }}</time> --}}
                                         <span class="text-main-red">{{ formatUSD($product->price) }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
