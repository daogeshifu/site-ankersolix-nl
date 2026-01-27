@extends('layouts.stitch.master')

@section('title', __('terms-of-service.terms_title') . ' - ' . config('app.name'))
@section('description', __('terms-of-service.terms_description'))
@section('keywords', __('terms-of-service.terms_keywords'))

@push('styles')
<style>
    .geometric-bg {
        background-image: radial-gradient(#135bec11 1px, transparent 1px);
        background-size: 24px 24px;
    }
</style>
@endpush

@section('content')
<main class="geometric-bg min-h-screen">
    <div class="max-w-[1280px] mx-auto px-4 lg:px-10 py-8">
        <!-- Breadcrumbs -->
        <div class="flex items-center gap-2 mb-6">
            <a class="text-[#616f89] dark:text-gray-400 text-sm font-medium hover:text-primary" href="{{ route('index') }}">{{ __('lang.home') }}</a>
            <span class="text-[#616f89] dark:text-gray-600">/</span>
            <span class="text-primary text-sm font-medium">{{ __('terms-of-service.terms_title') }}</span>
        </div>

        <!-- Page Heading -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12 border-b border-[#f0f2f4] dark:border-[#2a303c] pb-8">
            <div class="flex flex-col gap-3">
                <h1 class="text-[#111318] dark:text-white text-5xl font-black tracking-tight">{{ __('terms-of-service.terms_title') }}</h1>
                <p class="text-[#616f89] dark:text-gray-400 text-lg">{{ __('terms-of-service.last_updated') }}: {{ __('terms-of-service.last_updated_date') }}</p>
            </div>
            <div class="flex gap-4">
                <button class="flex items-center gap-2 px-6 py-3 bg-white dark:bg-[#1e2634] border border-[#f0f2f4] dark:border-[#2a303c] rounded-lg text-[#111318] dark:text-white font-bold text-sm hover:shadow-md transition-all">
                    <span class="material-symbols-outlined text-lg">download</span>
                    <span>{{ __('lang.download_pdf') }}</span>
                </button>
                <button onclick="window.print()" class="flex items-center gap-2 px-6 py-3 bg-white dark:bg-[#1e2634] border border-[#f0f2f4] dark:border-[#2a303c] rounded-lg text-[#111318] dark:text-white font-bold text-sm hover:shadow-md transition-all">
                    <span class="material-symbols-outlined text-lg">print</span>
                    <span>{{ __('lang.print') }}</span>
                </button>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Sticky Sidebar Navigation -->
            <aside class="w-full lg:w-72 flex-shrink-0">
                <div class="sticky top-24 bg-white dark:bg-[#1e2634] rounded-xl border border-[#f0f2f4] dark:border-[#2a303c] p-6">
                    <h3 class="text-[#111318] dark:text-white text-base font-bold mb-4 uppercase tracking-wider">{{ __('terms-of-service.navigation') }}</h3>
                    <nav class="flex flex-col gap-1">
                        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 text-primary font-bold transition-colors" href="#intro">
                            <span class="material-symbols-outlined text-xl">info</span>
                            <span class="text-sm">{{ __('terms-of-service.introduction') }}</span>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#616f89] dark:text-gray-400 hover:bg-[#f0f2f4] dark:hover:bg-[#2a303c] hover:text-[#111318] dark:hover:text-white transition-colors" href="#eligibility">
                            <span class="material-symbols-outlined text-xl">verified_user</span>
                            <span class="text-sm">{{ __('terms-of-service.eligibility') }}</span>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#616f89] dark:text-gray-400 hover:bg-[#f0f2f4] dark:hover:bg-[#2a303c] hover:text-[#111318] dark:hover:text-white transition-colors" href="#ip">
                            <span class="material-symbols-outlined text-xl">copyright</span>
                            <span class="text-sm">{{ __('terms-of-service.intellectual_property') }}</span>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#616f89] dark:text-gray-400 hover:bg-[#f0f2f4] dark:hover:bg-[#2a303c] hover:text-[#111318] dark:hover:text-white transition-colors" href="#conduct">
                            <span class="material-symbols-outlined text-xl">block</span>
                            <span class="text-sm">{{ __('terms-of-service.user_conduct') }}</span>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#616f89] dark:text-gray-400 hover:bg-[#f0f2f4] dark:hover:bg-[#2a303c] hover:text-[#111318] dark:hover:text-white transition-colors" href="#disclaimer">
                            <span class="material-symbols-outlined text-xl">warning</span>
                            <span class="text-sm">{{ __('terms-of-service.disclaimer_of_warranties') }}</span>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#616f89] dark:text-gray-400 hover:bg-[#f0f2f4] dark:hover:bg-[#2a303c] hover:text-[#111318] dark:hover:text-white transition-colors" href="#law">
                            <span class="material-symbols-outlined text-xl">gavel</span>
                            <span class="text-sm">{{ __('terms-of-service.governing_law') }}</span>
                        </a>
                    </nav>
                    <div class="mt-8 pt-6 border-t border-[#f0f2f4] dark:border-[#2a303c]">
                        <p class="text-xs text-[#616f89] dark:text-gray-500 mb-3 uppercase font-bold tracking-widest">{{ __('terms-of-service.questions') }}</p>
                        <a href="{{ route('contact') }}" class="w-full py-2 bg-primary/10 text-primary text-sm font-bold rounded-lg hover:bg-primary hover:text-white transition-all flex items-center justify-center">{{ __('terms-of-service.contact_legal') }}</a>
                    </div>
                </div>
            </aside>

            <!-- Content Area -->
            <article class="flex-1 bg-white dark:bg-[#1e2634] rounded-2xl border border-[#f0f2f4] dark:border-[#2a303c] shadow-sm p-8 lg:p-12">
                <section class="mb-12 scroll-mt-28" id="intro">
                    <h2 class="text-3xl font-bold mb-6 flex items-center gap-4">
                        <span class="bg-primary/10 text-primary w-10 h-10 rounded-lg flex items-center justify-center text-xl">1</span>
                        {{ __('terms-of-service.introduction') }}
                    </h2>
                    <div class="space-y-4 text-[#444] dark:text-gray-300 leading-relaxed text-lg">
                        <p>{{ __('terms-of-service.intro_note') }}</p>
                        <p>{{ __('terms-of-service.acceptance_content') }}</p>
                    </div>
                </section>

                <section class="mb-12 scroll-mt-28" id="eligibility">
                    <h2 class="text-3xl font-bold mb-6 flex items-center gap-4">
                        <span class="bg-primary/10 text-primary w-10 h-10 rounded-lg flex items-center justify-center text-xl">2</span>
                        {{ __('terms-of-service.eligibility') }}
                    </h2>
                    <div class="space-y-4 text-[#444] dark:text-gray-300 leading-relaxed text-lg">
                        <p>{{ __('terms-of-service.eligibility_content') }}</p>
                        <ul class="list-none space-y-3 pl-2">
                            <li class="flex items-start gap-3">
                                <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                                <span>{{ __('terms-of-service.eligibility_1') }}</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                                <span>{{ __('terms-of-service.eligibility_2') }}</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                                <span>{{ __('terms-of-service.eligibility_3') }}</span>
                            </li>
                        </ul>
                    </div>
                </section>

                <section class="mb-12 scroll-mt-28" id="ip">
                    <h2 class="text-3xl font-bold mb-6 flex items-center gap-4">
                        <span class="bg-primary/10 text-primary w-10 h-10 rounded-lg flex items-center justify-center text-xl">3</span>
                        {{ __('terms-of-service.intellectual_property') }}
                    </h2>
                    <div class="space-y-4 text-[#444] dark:text-gray-300 leading-relaxed text-lg">
                        <p>{{ __('terms-of-service.intellectual_property_content') }}</p>
                        <div class="bg-background-light dark:bg-[#101622] p-6 rounded-xl border-l-4 border-primary mt-6">
                            <p class="font-bold text-[#111318] dark:text-white mb-2 uppercase text-sm tracking-widest">{{ __('terms-of-service.usage_rights') }}</p>
                            <p>{{ __('terms-of-service.license_grant_content') }}</p>
                        </div>
                    </div>
                </section>

                <section class="mb-12 scroll-mt-28" id="conduct">
                    <h2 class="text-3xl font-bold mb-6 flex items-center gap-4">
                        <span class="bg-primary/10 text-primary w-10 h-10 rounded-lg flex items-center justify-center text-xl">4</span>
                        {{ __('terms-of-service.user_conduct') }}
                    </h2>
                    <div class="space-y-4 text-[#444] dark:text-gray-300 leading-relaxed text-lg">
                        <p>{{ __('terms-of-service.prohibited_content') }}</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 border border-[#f0f2f4] dark:border-[#2a303c] rounded-lg bg-gray-50 dark:bg-gray-800/30">
                                <span class="material-symbols-outlined text-red-500 mb-2">database_off</span>
                                <p class="font-bold mb-1">{{ __('terms-of-service.data_scraping') }}</p>
                                <p class="text-sm">{{ __('terms-of-service.data_scraping_desc') }}</p>
                            </div>
                            <div class="p-4 border border-[#f0f2f4] dark:border-[#2a303c] rounded-lg bg-gray-50 dark:bg-gray-800/30">
                                <span class="material-symbols-outlined text-red-500 mb-2">security_update_warning</span>
                                <p class="font-bold mb-1">{{ __('terms-of-service.reverse_engineering') }}</p>
                                <p class="text-sm">{{ __('terms-of-service.reverse_engineering_desc') }}</p>
                            </div>
                            <div class="p-4 border border-[#f0f2f4] dark:border-[#2a303c] rounded-lg bg-gray-50 dark:bg-gray-800/30">
                                <span class="material-symbols-outlined text-red-500 mb-2">person_cancel</span>
                                <p class="font-bold mb-1">{{ __('terms-of-service.account_sharing') }}</p>
                                <p class="text-sm">{{ __('terms-of-service.account_sharing_desc') }}</p>
                            </div>
                            <div class="p-4 border border-[#f0f2f4] dark:border-[#2a303c] rounded-lg bg-gray-50 dark:bg-gray-800/30">
                                <span class="material-symbols-outlined text-red-500 mb-2">bug_report</span>
                                <p class="font-bold mb-1">{{ __('terms-of-service.system_overload') }}</p>
                                <p class="text-sm">{{ __('terms-of-service.system_overload_desc') }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mb-12 scroll-mt-28" id="disclaimer">
                    <h2 class="text-3xl font-bold mb-6 flex items-center gap-4">
                        <span class="bg-primary/10 text-primary w-10 h-10 rounded-lg flex items-center justify-center text-xl">5</span>
                        {{ __('terms-of-service.disclaimer_of_warranties') }}
                    </h2>
                    <div class="space-y-4 text-[#444] dark:text-gray-300 leading-relaxed text-lg uppercase text-sm font-bold tracking-tight">
                        <p class="border-2 border-dashed border-[#f0f2f4] dark:border-[#2a303c] p-6 rounded-xl">{{ __('terms-of-service.warranties_content') }}</p>
                    </div>
                </section>

                <section class="mb-4 scroll-mt-28" id="law">
                    <h2 class="text-3xl font-bold mb-6 flex items-center gap-4">
                        <span class="bg-primary/10 text-primary w-10 h-10 rounded-lg flex items-center justify-center text-xl">6</span>
                        {{ __('terms-of-service.governing_law') }}
                    </h2>
                    <div class="space-y-4 text-[#444] dark:text-gray-300 leading-relaxed text-lg">
                        <p>{{ __('terms-of-service.governing_law_content') }}</p>
                    </div>
                </section>
            </article>
        </div>
    </div>
</main>
@endsection
