<section class="blog-section">
    <div class="blog-section__container">
        <div class="blog-section__main">
            @for ($i = 1; $i <= 6; $i++)
                <!-- Article -->
                <article class="blog-section__article">
                    <div class="blog-section__thumbnail">
                        <a class="blog-section__thumbnail-link" href="/articles/15-ways-to-grow-your-startup">
                            <img alt="15 Ways to Grow Your Startup" loading="lazy" decoding="async" data-nimg="fill" class="blog-section__thumbnail-image" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="(min-width: 1280px) 11rem, (min-width: 1024px) 16vw, (min-width: 768px) 9rem, (min-width: 640px) 30rem, calc(100vw - 2rem)" src="{{ asset('static/images/Manhwa-category.png') }}">
                        </a>
                    </div>
                    <div class="blog-section__content">
                        <div class="blog-section__content-wrapper">
                            <div>
                                <a class="blog-section__category" href="/categories/startup">Startup</a>
                                <h3 class="blog-section__title">
                                    <a href="/articles/15-ways-to-grow-your-startup" class="blog-section__title-link">
                                        <span aria-hidden="true"></span>15 Ways to Grow Your Startup
                                    </a>
                                </h3>
                                <p class="blog-section__excerpt">
                                    Lorem ipsum dolor sit amet tempus bendum labore laoreet.Hendrerit lobortis a leo
                                    curabitur faucibus sapien ullamcorper do labore odio.
                                </p>
                            </div>
                            <footer class="blog-section__meta">
                                <a class="blog-section__meta-avatar" href="/authors/matt-burgess">
                                    <img alt="Matt Burgess" loading="lazy" decoding="async" data-nimg="fill" class="blog-section__meta-avatar-image" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="2rem" src="{{ asset('static/images/Manhwa-category.png') }}">
                                </a>
                                <div class="blog-section__meta-info">
                                    <span class="blog-section__meta-byline">By&nbsp;</span>
                                    <a class="blog-section__meta-author" href="/authors/matt-burgess">Matt Burgess</a>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="blog-section__meta-icon">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5">
                                        </path>
                                    </svg>
                                    <time datetime="2022-04-05T10:56:47.000Z" class="blog-section__meta-date">Apr 5,
                                        2022</time>
                                    <span class="blog-section__meta-read-time">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="blog-section__meta-read-time-icon">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="blog-section__meta-read-time-text">12 min read</span>
                                    </span>
                                </div>
                            </footer>
                        </div>
                    </div>
                </article>
            @endfor
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
                <h3 class="blog-section__popular-title">Most read</h3>
                <div class="blog-section__popular-list">
                    @for ($i = 1; $i <= 6; $i++)
                        <!-- Popular Article -->
                        <article class="blog-section__popular-item">
                            <a class="blog-section__popular-thumbnail" href="/articles/pentagon-ufo-report">
                                <img alt="A Review of the Pentagon UFO Report" loading="lazy" decoding="async" data-nimg="fill" class="blog-section__popular-thumbnail-image" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="16rem" src="{{ asset('static/images/Manhwa-category.png') }}">
                            </a>
                            <div class="blog-section__popular-content">
                                <div class="blog-section__popular-content-wrapper">
                                    <div>
                                        <a class="blog-section__popular-content-title" href="/articles/pentagon-ufo-report">A Review of the Pentagon UFO Report</a>
                                    </div>
                                    <div class="blog-section__popular-content-meta">
                                        <div class="blog-section__popular-content-meta-author">
                                            <div class="blog-section__popular-content-meta-author-info">
                                                <span>By </span>
                                                <a href="/authors/mark-jack">Mark Jack</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</section>
