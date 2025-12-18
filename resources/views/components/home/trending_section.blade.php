<section class="trending">
    <div class="my_container">
        <h3 class="section_header">Popular Brands</h3>
        <div class="mt-9 relative">
            <div class="trending__grid">
                @foreach ($trending as $brand)
                <div class="trending__item">
                    <a href="#" class="">
                        <img alt="{{ $brand->name }}" loading="lazy"
                            decoding="async" data-nimg="fill"
                            class="absolute h-full w-full inset-0 object-center object-cover transition-all duration-300"
                            style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent"
                            src="{{ $brand->image ? (\Illuminate\Support\Str::startsWith($brand->image, 'static/') ? asset($brand->image) : asset('storage/' . ltrim($brand->image, '/'))) : asset('static/images/Manhwa-category.png') }}">
                        <div class="trending__tag">
                            <div class="">
                                <span class="">{{ $brand->name }}</span>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
