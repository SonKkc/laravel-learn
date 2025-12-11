<header class="navbar fixed top-0 left-0 w-full z-50 bg-white shadow">
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
                    <form action="/products" method="get" class="navbar__search-form">
                        <div class="navbar__search-icon-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="navbar__search-icon">
                                <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input class="navbar__search-input" name="search" placeholder="Search...">
                    </form>
                    <a href="/login" class="ml-2 px-4 py-2 rounded-full bg-main-red text-white font-semibold shadow hover:bg-main-red-hover transition-color duration-200 ease-out">Login</a>
                </div>

                <button id="navbar-mobile-toggle" class="ml-2 flex items-center justify-center rounded-full bg-gray-50 p-2 shadow-sm ring-1 ring-gray-900/5 transition duration-300 ease-in-out hover:bg-gray-100 focus:outline-none md:hidden" type="button" aria-label="Open menu">
                    <span class="relative block">
                        <svg id="navbar-mobile-icon" class="h-7 w-7 text-gray-700 transition-all duration-300 ease-in-out" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path class="navbar-bar top" d="M4 6h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path class="navbar-bar middle" d="M4 12h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path class="navbar-bar bottom" d="M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </span>
                </button>
            </div>
        </nav>
    </div>
    <div id="navbar-mobile-menu" class="w-full md:hidden">
        <nav class="border-b border-gray-300/60 bg-white" aria-label="Global" id="mobile-menu">
            <div class="space-y-1 px-2 pb-3 pt-2">
                <a href="{{ route('categories.index') }}" class="block rounded-lg font-medium px-4 py-3 text-gray-800 transition duration-300 ease-in-out hover:bg-gray-50 hover:text-red-700">Categories</a>
                <a href="{{ route('products.index') }}" class="block rounded-lg font-medium px-4 py-3 text-gray-800 transition duration-300 ease-in-out hover:bg-gray-50 hover:text-red-700">Products</a>
                <a href="{{ route('cart.index') }}" class="block rounded-lg font-medium px-4 py-3 text-gray-800 transition duration-300 ease-in-out hover:bg-gray-50 hover:text-red-700">Cart</a>
                <a href="{{ route('orders.my') }}" class="block rounded-lg font-medium px-4 py-3 text-gray-800 transition duration-300 ease-in-out hover:bg-gray-50 hover:text-red-700">My Orders</a>
            </div>
            {{-- <div class="border-t border-gray-300/70 pb-4 pt-4 px-3">
                <div class="mt-2 px-6 text-xs font-medium uppercase tracking-widest text-gray-500">Pages</div>
                <div class="mt-3 space-y-1 px-2 text-[15px]">
                        <a href="{{ url('/') }}" class="block rounded-lg font-medium px-4 py-2 bg-gray-50 text-red-700">Home</a>
                        <a href="{{ route('categories.index') }}" class="block rounded-lg font-medium px-4 py-2 text-gray-800 transition duration-300 ease-in-out hover:bg-gray-50 hover:text-red-700">Categories</a>
                        <a href="{{ route('products.index') }}" class="block rounded-lg font-medium px-4 py-2 text-gray-800 transition duration-300 ease-in-out hover:bg-gray-50 hover:text-red-700">Products</a>
                        <a href="{{ route('cart.index') }}" class="block rounded-lg font-medium px-4 py-2 text-gray-800 transition duration-300 ease-in-out hover:bg-gray-50 hover:text-red-700">Cart</a>
                        <a href="{{ route('orders.my') }}" class="block rounded-lg font-medium px-4 py-2 text-gray-800 transition duration-300 ease-in-out hover:bg-gray-50 hover:text-red-700">My Orders</a>
                </div>
            </div> --}}
        </nav>
    </div>
</header>
