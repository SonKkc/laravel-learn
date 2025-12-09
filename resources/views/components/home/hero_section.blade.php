<!--heading page-->
<section class="hero">
    <div class="hero__container">
        <article class="hero__main-content">
            <a href="#" class="hero__thumbnail">
                <img alt="Apple to Turn IPhones Into Payment Terminals in Fintech Push" loading="lazy" decoding="async"
                    data-nimg="fill"
                    class="absolute h-full w-full inset-0 object-center object-cover transition-all duration-300 hover:scale-105"
                    style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent"
                    src="{{ asset('static/images/Manhwa-category.png') }}">
            </a>
            <div class="hero__content">
                <a href="#" class="hero__tag">Technology</a>
                <a href="#" class="block mt-3 decoration-inherit">
                    <h2 class="hero__title">
                        Apple to Turn IPhones Into Payment Terminals in Fintech Push</h2>
                    <div>
                        <p class="hero__description">Apple Inc. announced that its merchants
                            will be able to accept credit card and digital payments with just a tap on their
                            iPhones, enabling them to bypass hardware systems such as those offered by Square
                            Inc.
                        </p>
                    </div>
                </a>
            </div>

            <div class="hero__meta">
                <a href="#" class="hero__avatar">
                    <img alt="Apple to Turn IPhones Into Payment Terminals in Fintech Push" loading="lazy"
                        decoding="async" data-nimg="fill" class="rounded-xl"
                        style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent"
                        src="{{ asset('static/images/Manhwa-category.png') }}">
                </a>
                <div class="ms-3">
                    <a href="#" class="hero__author-name">Mark Jack</a>
                    <p class="hero__publish-time">
                        <time datetime="2023-10-01T00:00:00Z" class="">October 1, 2023</time>
                        <span class="mx-0.5">·</span>
                        <span> <!-- -->6<!-- --> min read </span>
                    </p>
                </div>
            </div>
        </article>

        <div class="hero__sidebar">
            <h3 class="section_header">
                Recent Stories
            </h3>
            <div class="hero__stories-list">
                @for ($i = 1; $i <= 5; $i++)
                <article class="hero__story-item">
                    <a href="#" class="hero__story-thumbnail">
                        <div class="hero__story-thumbnail-container article_img-left">
                            <img alt="Apple to Turn IPhones Into Payment Terminals in Fintech Push" loading="lazy"
                                decoding="async" data-nimg="fill"
                                class="absolute h-full w-full inset-0 object-center object-cover transition-all duration-300 hover:scale-105"
                                style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent"
                                src="{{ asset('static/images/Manhwa-category.png') }}">
                        </div>
                    </a>
                    <div class="hero__story-content">
                        <a href="#" class="hero__story-tag">
                            Culture
                        </a>
                        <a href="#" class="hero__story-title">
                            <h3 class="">
                                What a Four Day Week Actually Looks Like</h3>
                        </a>
                        <div class="hero__story-meta">
                            <div class="hero__story-meta-container">
                                <a href="#" class="hero__story-avatar">
                                    <img alt="Apple to Turn IPhones Into Payment Terminals in Fintech Push"
                                        loading="lazy" decoding="async" data-nimg="fill" class=""
                                        style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent"
                                        src="{{ asset('static/images/Manhwa-category.png') }}">
                                </a>

                                <div class="hero__story-author-time">
                                    <span class="text-[#6b7280]">By </span>
                                    <a href="#" class="font-medium text-[#374151] hover:underline">Karina
                                        Bell</a>
                                    <span class="xl:inline lg:hidden text-[#6b7280]">
                                        <span class="mx-0.5">·</span>
                                        <time datetime="2023-10-01T00:00:00Z" class="text-[#6b7280]">October
                                            1, 2023</time>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
                @endfor
            </div>
        </div>
    </div>
</section>
