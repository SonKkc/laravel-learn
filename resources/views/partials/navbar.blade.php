<header class="navbar fixed left-0 top-0 z-50 w-full bg-white shadow">
    <div class="navbar__border">
        <nav class="navbar__nav">
            <div class="navbar__container">
                <!--Logo-->
                <div class="navbar__logo">
                    <a href="/" class="block h-10 w-10 lg:hidden">
                        <img alt="Logo" loading="lazy" width="40" height="40" decoding="async" class="h-full w-full object-cover" style="color:transparent" src="https://banter.tailawesome.com/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fbanter-icon-logo.67916a2e.png&w=256&q=75">
                    </a>
                    <a href="/" class="navbar__logo-link hidden lg:block">
                        <img src="https://banter.tailawesome.com/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fbanter-logo.7f46be36.png&w=360&q=75" alt="Logo" class="navbar__logo-image">
                    </a>
                </div>
                <!--Nav item-->
                <div class="navbar__menu">
                    <a href="{{ route('categories.index') }}" class="navbar__menu-item">Categories</a>
                    <a href="{{ route('products.index') }}" class="navbar__menu-item">Products</a>
                    <a href="{{ route('cart.index') }}" class="navbar__menu-item">Cart</a>
                    <a href="{{ route('orders.my') }}" class="navbar__menu-item">My Orders</a>
                </div>
                <!-- search-->
                <div class="navbar__search flex items-center gap-2">
                    <form action="/products" method="get" class="navbar__search-form hidden md:block">
                        <div class="navbar__search-icon-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="navbar__search-icon">
                                <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input class="navbar__search-input" name="search" placeholder="Search...">
                    </form>
                    @auth
                        <div class="relative flex items-center gap-2">
                            <!-- Cart dropdown -->
                            <div x-data="{ open: false, loading: false, html: '', async load() { if (this.html) return;
                                    this.loading = true; try { const res = await fetch('{{ route('cart.preview') }}', { credentials: 'same-origin' }); if (res.ok) { this.html = await res.text(); } } catch (e) { console.error(e); } finally { this.loading = false; } } }" class="relative" x-cloak>
                                @php
                                    // Use DB-backed cart for authenticated users to keep badge consistent
                                    $cartCount = 0;
                                    if (Auth::check()) {
                                        try {
                                            $cartModel = \App\Models\Cart::firstOrCreate(['user_id' => Auth::id()]);
                                            $cartArr = $cartModel->toArrayPayload();
                                            foreach ($cartArr as $it) {
                                                $cartCount += isset($it['qty']) ? (int) $it['qty'] : 1;
                                            }
                                        } catch (\Exception $e) {
                                            $cartCount = 0;
                                        }
                                    } else {
                                        $cart = session('cart', []);
                                        if (!empty($cart) && is_array($cart)) {
                                            foreach ($cart as $it) {
                                                $cartCount += isset($it['qty']) ? (int) $it['qty'] : 1;
                                            }
                                        }
                                    }
                                @endphp
                                <button @click="open = !open; if (open) load()" @keydown.escape="open = false" type="button" class="relative inline-flex items-center rounded-full bg-gray-50 p-2 text-gray-700 hover:bg-gray-100 focus:outline-none">
                                    @if ($cartCount > 0)
                                        <span data-cart-badge class="bg-main-red absolute -right-1 -top-1 inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full px-1.5 py-0.5 text-xs font-semibold leading-none text-white">{{ $cartCount }}</span>
                                    @else
                                        <span data-cart-badge class="bg-main-red absolute -right-1 -top-1 inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full px-1.5 py-0.5 text-xs font-semibold leading-none text-white">0</span>
                                    @endif
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-5 w-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 13l-1.2 6.4A1 1 0 0 0 6.8 21h10.4a1 1 0 0 0 .99-.86L19 13" />
                                    </svg>
                                    <span class="sr-only">Open cart</span>
                                </button>
                                <div x-show="open" x-transition class="absolute right-0 z-50 mt-2 w-80 max-w-xs origin-top-right rounded-xl bg-white py-2 shadow-xl ring-1 ring-black/10" @click.away="open = false">
                                    <div x-show="loading" class="p-4 text-sm text-gray-500">Đang tải...</div>
                                    <div x-html="html"></div>
                                    <div x-show="!loading && html === ''" class="p-4 text-sm text-gray-500">Giỏ hàng trống.</div>
                                </div>
                            </div>
                            <!-- User dropdown -->
                            <div x-data="{ open: false }" class="relative hidden md:block">
                                <button @click="open = !open" @keydown.escape="open = false" type="button" class="bg-main-red hover:bg-main-red-hover flex items-center gap-2 rounded-full px-4 py-2 font-semibold text-white shadow transition-all duration-200 focus:outline-none active:scale-95">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                    <span class="font-semibold line-clamp-1">{{ Auth::user()->name }}</span>
                                    <svg class="ml-1 h-4 w-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-2 scale-95" @click.away="open = false" class="absolute right-0 z-50 mt-2 w-64 max-w-xs origin-top-right rounded-xl bg-white py-2 shadow-xl ring-1 ring-black/10" x-cloak>
                                    <div class="flex items-center gap-3 border-b px-4 py-4">
                                        <span class="bg-main-red/10 inline-flex h-11 w-11 items-center justify-center rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="text-main-red h-7 w-7">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                            </svg>
                                        </span>
                                        <div class="flex min-w-0 flex-col">
                                            <span class="whitespace-normal break-all text-base font-semibold text-gray-900">{{ Auth::user()->name }}</span>
                                            <span class="whitespace-normal break-all text-xs text-gray-500">{{ Auth::user()->email }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('profile.show') }}" class="flex items-center gap-2 rounded-lg px-4 py-3 font-medium text-gray-700 transition hover:bg-gray-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="text-main-red h-5 w-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                        </svg>
                                        <span>Hồ sơ cá nhân</span>
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="mt-1">
                                        @csrf
                                        <button type="submit" class="text-main-red flex w-full items-center gap-2 rounded-lg px-4 py-3 text-left font-semibold transition hover:bg-gray-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Đăng xuất
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="/login" class="bg-main-red hover:bg-main-red-hover ml-2 rounded-full px-4 py-2 font-semibold text-white shadow transition-all duration-200">Login</a>
                        @endauth
                    </div>

                    <button id="navbar-mobile-toggle" class="ml-2 flex items-center justify-center rounded-full bg-gray-50 p-2 shadow-sm ring-1 ring-gray-900/5 transition duration-300 ease-in-out hover:bg-gray-100 focus:outline-none md:hidden" type="button" aria-label="Open menu">
                        <span class="relative block">
                            <svg id="navbar-mobile-icon" class="h-7 w-7 text-gray-700 transition-all duration-300 ease-in-out" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path class="navbar-bar top" d="M4 6h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                <path class="navbar-bar middle" d="M4 12h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                <path class="navbar-bar bottom" d="M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </span>
                    </button>
                </div>
        </nav>
    </div>
    <div id="navbar-mobile-menu" class="w-full md:hidden">
        <nav class="border-b border-gray-300/60 bg-white" aria-label="Global" id="mobile-menu">
            <div class="space-y-1 px-2 pb-3 pt-2">
                {{-- Mobile Search --}}
                <form action="/products" method="get" class="px-4 py-3">
                    <label for="mobile-search" class="sr-only">Search</label>
                    <div class="flex items-center gap-2">
                        <input id="mobile-search" name="search" placeholder="Tìm kiếm..." class="w-full rounded-md border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-900 outline-none" />
                        <button type="submit" class="inline-flex items-center justify-center rounded-md bg-main-red px-3 py-2 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35" />
                                <circle cx="11" cy="11" r="6" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                </form>

                {{-- Mobile user block --}}
                @auth
                    <div class="px-4 py-3 border-b">
                        <div class="flex items-center gap-3">
                            <div class="bg-main-red/10 inline-flex h-10 w-10 items-center justify-center rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="text-main-red h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <div class="truncate font-semibold text-gray-900 line-clamp-1">{{ Auth::user()->name }}</div>
                                <div class="truncate text-xs text-gray-500 line-clamp-1">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <a href="{{ route('profile.show') }}" class="block rounded-lg px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Hồ sơ cá nhân</a>
                            <a href="{{ route('orders.my') }}" class="block rounded-lg px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Đơn hàng của tôi</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left rounded-lg px-4 py-2 text-sm text-main-red hover:bg-gray-50">Đăng xuất</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="px-4 py-3 border-b">
                        <a href="/login" class="block rounded-lg px-4 py-2 text-sm font-semibold text-main-red hover:bg-gray-50">Đăng nhập</a>
                    </div>
                @endauth

                <a href="{{ route('categories.index') }}" class="block rounded-lg px-4 py-3 font-medium text-gray-800 transition duration-300 ease-in-out hover:bg-gray-50 hover:text-red-700">Categories</a>
                <a href="{{ route('products.index') }}" class="block rounded-lg px-4 py-3 font-medium text-gray-800 transition duration-300 ease-in-out hover:bg-gray-50 hover:text-red-700">Products</a>
                @php
                    $cartMobileCount = 0;
                    if (Auth::check()) {
                        try {
                            $cartModel = \App\Models\Cart::firstOrCreate(['user_id' => Auth::id()]);
                            $cartArr = $cartModel->toArrayPayload();
                            foreach ($cartArr as $it) {
                                $cartMobileCount += isset($it['qty']) ? (int) $it['qty'] : 1;
                            }
                        } catch (\Exception $e) {
                            $cartMobileCount = 0;
                        }
                    } else {
                        $cartMobile = session('cart', []);
                        if (!empty($cartMobile) && is_array($cartMobile)) {
                            foreach ($cartMobile as $it) {
                                $cartMobileCount += isset($it['qty']) ? (int) $it['qty'] : 1;
                            }
                        }
                    }
                @endphp
                <a href="{{ route('cart.index') }}" class="flex items-center justify-between rounded-lg px-4 py-3 font-medium text-gray-800 transition duration-300 ease-in-out hover:bg-gray-50 hover:text-red-700">
                    <span>Cart</span>
                    @if ($cartMobileCount > 0)
                        <span data-cart-badge class="bg-main-red inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full px-1.5 py-0.5 text-xs font-semibold leading-none text-white">{{ $cartMobileCount }}</span>
                    @endif
                </a>
                <a href="{{ route('orders.my') }}" class="block rounded-lg px-4 py-3 font-medium text-gray-800 transition duration-300 ease-in-out hover:bg-gray-50 hover:text-red-700">My Orders</a>
            </div>
        </nav>
    </div>
</header>
