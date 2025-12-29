<section class="blog-section">
    <div class="blog-section__container">
        <div class="blog-section__main">
            @foreach ($mostBuy as $product)
                <!-- Article -->
                <article class="blog-section__article">
                    <div class="blog-section__thumbnail">
                        <a class="blog-section__thumbnail-link" href="{{ route('products.show', $product->id) }}">
                            @php
                                $imgSrc = null;
                                if (is_array($product->images) && !empty($product->images[0])) {
                                    $img = $product->images[0] ?? '';
                                    $imgSrc = \Illuminate\Support\Str::startsWith($img, 'static/') ? asset($img) : asset('storage/' . ltrim($img, '/'));
                                }
                            @endphp
                            <img alt="{{ $product->name }}" loading="lazy" decoding="async" data-nimg="fill" class="blog-section__thumbnail-image" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="(min-width: 1280px) 11rem, (min-width: 1024px) 16vw, (min-width: 768px) 9rem, (min-width: 640px) 30rem, calc(100vw - 2rem)" src="{{ $imgSrc ?? asset('static/images/Manhwa-category.png') }}">
                        </a>
                    </div>
                    <div class="blog-section__content">
                        <div class="blog-section__content-wrapper">
                            <div>
                                <a class="blog-section__category" href="#">{{ $product->category->name ?? '' }}</a>
                                <h3 class="blog-section__title">
                                    <a href="{{ route('products.show', $product->id) }}" class="blog-section__title-link">
                                        <span aria-hidden="true"></span>{{ $product->name }}
                                    </a>
                                </h3>
                                <p class="blog-section__excerpt line-clamp-3">
                                    {{ $product->description }}
                                </p>
                            </div>
                            <footer class="blog-section__meta">
                                {{-- <a class="blog-section__meta-avatar" href="#">
                                    <img alt="{{ $product->brand->name ?? '' }}" loading="lazy" decoding="async" data-nimg="fill" class="blog-section__meta-avatar-image" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="2rem" src="{{ asset('static/images/Manhwa-category.png') }}">
                                </a> --}}
                                <div class="blog-section__meta-info">
                                    <a class="blog-section__meta-author" href="#">{{ $product->brand->name ?? '' }}</a>
                                    {{-- <time datetime="{{ $product->created_at }}" class="blog-section__meta-date">{{ $product->created_at->format('M d, Y') }}</time> --}}
                                    <span class="mx-2">·</span>
                                    <span class="text-main-red">{{ formatUSD($product->price) }}</span>
                                </div>
                            </footer>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <!-- Sidebar -->
        <div class="blog-section__sidebar">
            <!-- Advertisement -->
            <div>
                <a class="blog-section__ad-portrait" href="#">
                    <img alt="Fashion ad portrait" loading="lazy" decoding="async" data-nimg="fill" class="blog-section__ad-image blog-section__ad-image--portrait" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="(min-width: 1024px) 33vw, 0" src="https://banter.tailawesome.com/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Ffashion-ad-portrait.e299ad8d.png&w=640&q=75">
                </a>
                <a class="blog-section__ad-landscape" href="#">
                    <img alt="Fashion landscape ad" loading="lazy" width="960" height="540" decoding="async" data-nimg="1" class="blog-section__ad-image blog-section__ad-image--landscape" style="color:transparent" sizes="(min-width: 1024px) 0, (min-width: 768px) 42rem, (min-width: 640px) 30rem, calc(100vw - 2rem)" src="https://banter.tailawesome.com/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Ffashion-ad-portrait.e299ad8d.png&w=640&q=75">
                </a>
            </div>

            <!-- Most Read Section -->
            <div class="blog-section__popular">
                <h3 class="blog-section__popular-title">Most view</h3>
                <div class="blog-section__popular-list">
                    @foreach ($mostView as $product)
                        <article class="blog-section__popular-item">
                            <a class="blog-section__popular-thumbnail" href="{{ route('products.show', $product->id) }}">
                                @php
                                    $imgSrc = null;
                                    if (is_array($product->images) && !empty($product->images[0])) {
                                        $img = $product->images[0] ?? '';
                                        $imgSrc = \Illuminate\Support\Str::startsWith($img, 'static/') ? asset($img) : asset('storage/' . ltrim($img, '/'));
                                    }
                                @endphp
                                <img alt="{{ $product->name }}" loading="lazy" decoding="async" data-nimg="fill" class="blog-section__popular-thumbnail-image" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="16rem" src="{{ $imgSrc ?? asset('static/images/Manhwa-category.png') }}">
                            </a>
                            <div class="blog-section__popular-content">
                                <div class="blog-section__popular-content-wrapper">
                                    <div>
                                        <a class="blog-section__popular-content-title" href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a>
                                    </div>
                                    <div class="blog-section__popular-content-meta">
                                        <div class="blog-section__popular-content-meta-author">
                                            <div class="blog-section__popular-content-meta-author-info">
                                                {{-- <span>By </span> --}}
                                                <a href="#">{{ $product->brand->name ?? '' }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
