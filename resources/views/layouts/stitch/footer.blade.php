<footer class="bg-white dark:bg-[#0c121d] border-t border-gray-200 dark:border-gray-800 px-6 py-12">
    <div class="max-w-[1280px] mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
        <!-- Brand -->
        <div class="md:col-span-1 space-y-4">
            <a href="{{ route('index') }}" class="flex items-center gap-2 text-primary">
                <span class="inline-flex size-9 items-center justify-center rounded-[10px] bg-primary text-[12px] font-extrabold tracking-[-0.03em] text-white">BT</span>
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
