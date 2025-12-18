<section class="content">
    <div class="content__grid">
        <!--left-->
        <div class="col-span-2">
            <div class="content__main">

                {{-- <a href="#" class="content__advertisement">
                    <img alt="Apple to Turn IPhones Into Payment Terminals in Fintech Push" loading="lazy"
                        decoding="async" data-nimg="fill" class="" style="color:transparent; height: 155px;"
                        src="{{ asset('static/images/Manhwa-category.png') }}">
                </a> --}}

                <div class="content__cards !mt-0">
                    @foreach ($productsByCategory as $categoryName => $product)
                    <x-product-card :product="$product" :categoryName="$categoryName" />
                    @endforeach
                </div>
            </div>
        </div>
        <!--right-->
        <div class="content__sidebar">
            <!--feature-->
            @if($featuredProducts->count())
            <div class="content__feature">
                <h3 class="section_header">Featured</h3>
                <div class="pt-6">
                    @foreach($featuredProducts as $product)
                    <article class="content__feature-card">
                        <a href="{{ route('products.show', $product->id) }}" class="content__feature-thumbnail">
                            @php
                                $imgSrc = null;
                                if (is_array($product->images) && !empty($product->images[0])) {
                                    $img = $product->images[0] ?? '';
                                    $imgSrc = \Illuminate\Support\Str::startsWith($img, 'static/') ? asset($img) : asset('storage/' . ltrim($img, '/'));
                                }
                            @endphp
                            <img alt="{{ $product->name }}" loading="lazy"
                                decoding="async" data-nimg="fill"
                                class="absolute h-full w-full inset-0 object-center object-cover transition-all duration-300 hover:scale-105"
                                style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent"
                                src="{{ $imgSrc ?? asset('static/images/Manhwa-category.png') }}">
                        </a>
                        <div class="content__feature-content">
                            <div class="content__feature-wrapper">
                                <div>
                                    <a href="{{ route('products.show', $product->id) }}" class="content__feature-title">{{ $product->name }}</a>
                                </div>
                                <div class="flex mt-2 items-center justify-between">
                                    <div class="content__feature-author">
                                        <div class="text-sm">
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
            @endif
            <!--category list-->
            <div class="content__tags">
                <h3 class="section_header">Categories</h3>
                <div class="pt-5">
                    <ul class="content__tags-list">
                        @foreach (App\Models\Category::all() as $category)
                        <li>
                            <a href="{{ route('categories.index') }}">
                                <span class="content__tag-item">{{ $category->name }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
