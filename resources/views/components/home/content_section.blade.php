<section class="content">
    <div class="content__grid">
        <!--left-->
        <div class="col-span-2">
            <div class="content__main">

                <a href="#" class="content__advertisement">
                    <img alt="Apple to Turn IPhones Into Payment Terminals in Fintech Push" loading="lazy"
                        decoding="async" data-nimg="fill" class="" style="color:transparent; height: 155px;"
                        src="{{ asset('static/images/Manhwa-category.png') }}">
                </a>

                <div class="content__cards">
                    @for ($i = 1; $i <= 6; $i++)
                    <article class="content__card">
                        <div class="content__thumbnail">
                            <a href="#" class="">
                                <img alt="Apple to Turn IPhones Into Payment Terminals in Fintech Push" loading="lazy"
                                    decoding="async" data-nimg="fill"
                                    class="absolute h-full w-full inset-0 object-center object-cover transition-all duration-300"
                                    style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent"
                                    src="{{ asset('static/images/Manhwa-category.png') }}">
                            </a>
                        </div>
                        <div class="content__card-content">
                            <div>
                                <a href="#" class="content__card-tag">Science</a>
                                <a href="#" class="content__card-title">
                                    <h3>How AI is Making Nuclear Fusion Safer</h3>
                                </a>
                                <p class="content__card-description">Lorem ipsum dolor sit amet tempus bendum labore
                                    laoreet.Hendrerit lobortis a leo curabitur faucibus sapien ullamcorper do
                                    labore odio.</p>
                            </div>
                            <div class="content__card-meta">
                                <a href="#" class="content__card-avatar">
                                    <img alt="Apple to Turn IPhones Into Payment Terminals in Fintech Push"
                                        loading="lazy" decoding="async" data-nimg="fill" class="rounded-xl"
                                        style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent"
                                        src="{{ asset('static/images/Manhwa-category.png') }}">
                                </a>
                                <div class="ms-3">
                                    <a href="#" class="content__card-author">Mark Jack</a>
                                    <p class="content__card-time">
                                        <time datetime="2023-10-01T00:00:00Z" class="">October 1, 2023</time>
                                        <span class="mx-0.5">·</span>
                                        <span> <!-- -->6<!-- --> min read </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </article>
                    @endfor
                </div>
            </div>
        </div>
        <!--right-->
        <div class="content__sidebar">
            <!--feature-->
            <div class="content__feature">
                <h3 class="section_header">Featured</h3>

                <div class="pt-6">
                    @for ($i = 1; $i <= 4; $i++)
                    <article class="content__feature-card">
                        <a href="#" class="content__feature-thumbnail">
                            <img alt="Apple to Turn IPhones Into Payment Terminals in Fintech Push" loading="lazy"
                                decoding="async" data-nimg="fill"
                                class="absolute h-full w-full inset-0 object-center object-cover transition-all duration-300 hover:scale-105"
                                style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent"
                                src="{{ asset('static/images/Manhwa-category.png') }}">
                        </a>
                        <div class="content__feature-content">
                            <div class="content__feature-wrapper">
                                <div>
                                    <a href="#" class="content__feature-title">The 7 Best Monitors in the Market</a>
                                </div>
                                <div class="flex mt-2 items-center justify-between">
                                    <div class="content__feature-author">
                                        <div class="text-sm">
                                            <span>By </span>
                                            <a href="#">Mark Jack</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                    @endfor
                </div>
            </div>
            <!--popular tag-->
            <div class="content__tags">
                <h3 class="section_header">Popular tags</h3>
                <div class="pt-5">
                    <ul class="content__tags-list">
                        <li>
                            <a href="">
                                <span class="content__tag-item">Work</span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <span class="content__tag-item">Tips</span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <span class="content__tag-item">Business</span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <span class="content__tag-item">Reviews</span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <span class="content__tag-item">Growth</span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <span class="content__tag-item">Deeper Look</span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <span class="content__tag-item">Gaming</span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <span class="content__tag-item">Streaming</span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <span class="content__tag-item">Idea</span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <span class="content__tag-item">Environment</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
