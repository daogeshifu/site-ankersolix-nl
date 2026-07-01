<footer class="bg-white dark:bg-[#0c121d] border-t border-gray-200 dark:border-gray-800 px-6 py-12">
    <div class="max-w-[1280px] mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
        <!-- Brand -->
        <div class="md:col-span-1 space-y-4">
            <a href="{{ route('index') }}" class="flex items-center gap-2 text-primary">
                <div class="size-8">
                    <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" d="M24 18.4228L42 11.475V34.3663C42 34.7796 41.7457 35.1504 41.3601 35.2992L24 42V18.4228Z" fill="currentColor" fill-rule="evenodd"></path>
                        <path clip-rule="evenodd" d="M24 8.18819L33.4123 11.574L24 15.2071L14.5877 11.574L24 8.18819ZM9 15.8487L21 20.4805V37.6263L9 32.9945V15.8487ZM27 37.6263V20.4805L39 15.8487V32.9945L27 37.6263ZM25.354 2.29885C24.4788 1.98402 23.5212 1.98402 22.646 2.29885L4.98454 8.65208C3.7939 9.08038 3 10.2097 3 11.475V34.3663C3 36.0196 4.01719 37.5026 5.55962 38.098L22.9197 44.7987C23.6149 45.0671 24.3851 45.0671 25.0803 44.7987L42.4404 38.098C43.9828 37.5026 45 36.0196 45 34.3663V11.475C45 10.2097 44.2061 9.08038 43.0155 8.65208L25.354 2.29885Z" fill="currentColor" fill-rule="evenodd"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold dark:text-white">{{ __('home.super') }}</h2>
            </a>
            <p class="text-sm text-gray-500 leading-relaxed">{{ __('home.brand') }}</p>
        </div>

        <!-- Resources -->
        <div class="space-y-4">
            <h3 class="font-bold uppercase text-xs tracking-widest text-gray-400">{{ __('lang.resources') }}</h3>
            <ul class="space-y-2 text-sm">
{{--                <li><a class="hover:text-primary transition-colors" href="{{ route('articles') }}">{{ __('menu.insights') }}</a></li>--}}
                <li><a class="hover:text-primary transition-colors" href="{{ route('products.index') }}">{{ __('menu.products') }}</a></li>
                <li><a class="hover:text-primary transition-colors" href="{{ route('calculator') }}">{{ __('menu.calculator') }}</a></li>
                <li><a class="hover:text-primary transition-colors" href="{{ route('help') }}">{{ __('help.title') }}</a></li>
            </ul>
        </div>

        <!-- Company -->
        <div class="space-y-4">
            <h3 class="font-bold uppercase text-xs tracking-widest text-gray-400">{{ __('lang.company') }}</h3>
            <ul class="space-y-2 text-sm">
                <li><a class="hover:text-primary transition-colors" href="{{ route('about') }}">{{ __('about-us.title') }}</a></li>
                <li><a class="hover:text-primary transition-colors" href="{{ route('contact') }}">{{ __('contact-us.title') }}</a></li>
                <li><a class="hover:text-primary transition-colors" href="{{ route('policy') }}">{{ __('privacy-policy.privacy_policy_title') }}</a></li>
            </ul>
        </div>

        <!-- Subscribe -->
        <div class="space-y-4">
            <h3 class="font-bold uppercase text-xs tracking-widest text-gray-400">{{ __('lang.subscribe') }}</h3>
            <p class="text-xs text-gray-500">{{ __('lang.subscribe_desc') }}</p>
            <form action="{{ route('contact') }}" method="GET" class="flex h-10">
                <input class="flex-1 bg-gray-100 dark:bg-gray-800 border-none rounded-l-lg px-3 text-sm focus:ring-1 focus:ring-primary" placeholder="{{ __('contact.email_placeholder') }}" type="email"/>
                <button type="submit" class="bg-primary text-white px-4 rounded-r-lg">
                    <span class="material-symbols-outlined text-[20px] align-middle">send</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="max-w-[1280px] mx-auto mt-12 pt-8 border-t border-gray-100 dark:border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-400">
        <p>&copy; {{ date('Y') }} {{ __('home.super') }}. Alle rechten voorbehouden.</p>
        <div class="flex gap-6">
            <a class="hover:text-primary transition-colors" href="{{ route('policy') }}">{{ __('privacy-policy.privacy_policy_title') }}</a>
            <a class="hover:text-primary transition-colors" href="{{ route('terms') }}">{{ __('terms-of-service.terms_title') }}</a>
        </div>
    </div>
</footer>
