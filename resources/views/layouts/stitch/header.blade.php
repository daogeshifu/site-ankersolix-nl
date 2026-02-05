@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
    $currentLocale = LaravelLocalization::getCurrentLocale();
@endphp

<header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-[#dbdfe6] dark:border-[#2a303c] bg-white dark:bg-background-dark px-6 lg:px-20 py-4 sticky top-0 z-50">
    <div class="flex items-center gap-10">
        <!-- Logo -->
        <a href="{{ route('index') }}" class="flex items-center gap-3 text-primary">
            <div class="size-8">
                <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd" d="M24 18.4228L42 11.475V34.3663C42 34.7796 41.7457 35.1504 41.3601 35.2992L24 42V18.4228Z" fill="currentColor" fill-rule="evenodd"></path>
                    <path clip-rule="evenodd" d="M24 8.18819L33.4123 11.574L24 15.2071L14.5877 11.574L24 8.18819ZM9 15.8487L21 20.4805V37.6263L9 32.9945V15.8487ZM27 37.6263V20.4805L39 15.8487V32.9945L27 37.6263ZM25.354 2.29885C24.4788 1.98402 23.5212 1.98402 22.646 2.29885L4.98454 8.65208C3.7939 9.08038 3 10.2097 3 11.475V34.3663C3 36.0196 4.01719 37.5026 5.55962 38.098L22.9197 44.7987C23.6149 45.0671 24.3851 45.0671 25.0803 44.7987L42.4404 38.098C43.9828 37.5026 45 36.0196 45 34.3663V11.475C45 10.2097 44.2061 9.08038 43.0155 8.65208L25.354 2.29885Z" fill="currentColor" fill-rule="evenodd"></path>
                </svg>
            </div>
            <h2 class="text-[#111318] dark:text-white text-xl font-bold leading-tight tracking-tight">{{ __('home.super') }}</h2>
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center gap-8">
            <a class="text-[#111318] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors {{ request()->routeIs('index') ? 'text-primary' : '' }}" href="{{ route('index') }}">{{ __('menu.home') }}</a>
            <a class="text-[#111318] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors {{ request()->routeIs('articles') ? 'text-primary' : '' }}" href="{{ route('news') }}">News</a>
            <a class="text-[#111318] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors {{ request()->routeIs('articles') ? 'text-primary' : '' }}" href="{{ route('guides') }}">Guides</a>
            <a class="text-[#111318] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors {{ request()->routeIs('articles') ? 'text-primary' : '' }}" href="{{ route('cases') }}">Cases</a>
{{--            <a class="text-[#111318] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors {{ request()->routeIs('articles') ? 'text-primary' : '' }}" href="{{ route('articles') }}">Free GEO Tools	</a>--}}
{{--            <a class="text-[#111318] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors {{ request()->routeIs('articles') ? 'text-primary' : '' }}" href="{{ route('articles') }}">Platforms</a>--}}
{{--            <a class="text-[#111318] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors {{ request()->routeIs('articles') ? 'text-primary' : '' }}" href="{{ route('articles') }}">Industry Index</a>--}}
            <a class="text-[#111318] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors {{ request()->routeIs('about') ? 'text-primary' : '' }}" href="{{ route('about') }}">{{ __('menu.about') }}</a>
            <a class="text-[#111318] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors {{ request()->routeIs('contact') ? 'text-primary' : '' }}" href="{{ route('contact') }}">{{ __('contact-us.title') }}</a>
        </nav>
    </div>

    <div class="flex flex-1 justify-end gap-4 items-center">
        <!-- Search Bar (Desktop) -->
        <div class="hidden lg:flex items-stretch bg-[#f0f2f4] dark:bg-[#1c2331] rounded-lg h-10 px-3 w-64 border border-transparent focus-within:border-primary transition-all">
            <span class="material-symbols-outlined self-center text-gray-500 text-[20px]">search</span>
            <form action="{{ route('articles') }}" method="GET" class="flex-1">
                <input class="bg-transparent border-none focus:ring-0 text-sm w-full placeholder:text-gray-500 h-full" name="search" placeholder="{{ __('article.search_placeholder') }}"/>
            </form>
        </div>

        <!-- Language Switcher -->
        <div class="relative group">
            <button class="flex items-center justify-center rounded-lg h-10 px-3 bg-[#f0f2f4] dark:bg-[#1c2331] text-[#111318] dark:text-white text-sm font-medium">
                <span class="material-symbols-outlined text-[18px] mr-1">language</span>
                {{ $currentLocale === 'en' ? 'EN' : '中' }}
            </button>
            <div class="absolute right-0 mt-2 w-32 bg-white dark:bg-[#1c2331] rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                <a href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 {{ $currentLocale === 'en' ? 'text-primary' : '' }}">English</a>
                <a href="{{ LaravelLocalization::getLocalizedURL('zh', null, [], true) }}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 {{ in_array($currentLocale, ['zh', 'cn']) ? 'text-primary' : '' }}">中文</a>
            </div>
        </div>

        <!-- Auth Buttons -->
        @auth
            <div class="relative group">
                <button class="flex items-center gap-2 rounded-lg h-10 px-3 bg-[#f0f2f4] dark:bg-[#1c2331]">
                    <img src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : asset('around/image/avatar/default.png') }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover">
                    <span class="text-sm font-medium hidden sm:inline">{{ Auth::user()->name }}</span>
                </button>
                <div class="absolute right-0 mt-2 w-40 bg-white dark:bg-[#1c2331] rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                    <a href="{{ route('login') }}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">{{ __('lang.login') }}</a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">{{ __('lang.logout') }}</a>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            </div>
        @else
            <a href="{{ route('register') }}" class="hidden sm:flex min-w-[100px] cursor-pointer items-center justify-center rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold hover:bg-primary/90 transition-all">
                {{ __('lang.register') }}
            </a>
            <a href="{{ route('login') }}" class="flex items-center justify-center rounded-lg h-10 w-10 bg-[#f0f2f4] dark:bg-[#1c2331] text-[#111318] dark:text-white">
                <span class="material-symbols-outlined">account_circle</span>
            </a>
        @endauth

        <!-- Mobile Menu Button -->
        <button class="md:hidden flex items-center justify-center rounded-lg h-10 w-10 bg-[#f0f2f4] dark:bg-[#1c2331] text-[#111318] dark:text-white" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
            <span class="material-symbols-outlined">menu</span>
        </button>
    </div>
</header>

<!-- Mobile Navigation -->
<div id="mobile-menu" class="hidden md:hidden bg-white dark:bg-background-dark border-b border-gray-200 dark:border-gray-800 px-6 py-4">
    <nav class="flex flex-col gap-4">
        <a class="text-[#111318] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors" href="{{ route('index') }}">{{ __('menu.home') }}</a>
        <a class="text-[#111318] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors" href="{{ route('articles') }}">{{ __('menu.insights') }}</a>
        <a class="text-[#111318] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors" href="{{ route('about') }}">{{ __('menu.about') }}</a>
        <a class="text-[#111318] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors" href="{{ route('contact') }}">{{ __('contact-us.title') }}</a>
        <!-- Mobile Search -->
        <form action="{{ route('articles') }}" method="GET" class="flex items-stretch bg-[#f0f2f4] dark:bg-[#1c2331] rounded-lg h-10 px-3">
            <span class="material-symbols-outlined self-center text-gray-500 text-[20px]">search</span>
            <input class="bg-transparent border-none focus:ring-0 text-sm w-full placeholder:text-gray-500" name="search" placeholder="{{ __('article.search_placeholder') }}"/>
        </form>
    </nav>
</div>
