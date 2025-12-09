<section class="trending">
    <div class="my_container">
        <h3 class="section_header">Trending topics</h3>
        <div class="mt-9 relative">
            <div class="trending__grid">
                @for ($i = 1; $i <= 6; $i++)
                <div class="trending__item">
                    <a href="#" class="">
                        <img alt="Apple to Turn IPhones Into Payment Terminals in Fintech Push" loading="lazy"
                            decoding="async" data-nimg="fill"
                            class="absolute h-full w-full inset-0 object-center object-cover transition-all duration-300"
                            style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent"
                            src="{{ asset('static/images/Manhwa-category.png') }}">
                        <div class="trending__tag">
                            <div class="">
                                <span class="">Culture</span>
                            </div>
                        </div>
                    </a>
                </div>
                @endfor
            </div>
        </div>
    </div>
</section>
