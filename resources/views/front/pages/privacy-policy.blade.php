@extends('layouts.stitch.master')

@section('title', __('privacy-policy.privacy_policy_title') . ' - ' . config('app.name'))
@section('description', __('privacy-policy.privacy_policy_description'))
@section('keywords', __('privacy-policy.privacy_policy_keywords'))

@push('styles')
<style>
    .prose p {
        margin-bottom: 1.5rem;
        line-height: 1.75;
        color: #4b5563;
    }
    .dark .prose p {
        color: #9ca3af;
    }
    .sticky-sidebar {
        position: sticky;
        top: 2rem;
        max-height: calc(100vh - 4rem);
    }
</style>
@endpush

@section('content')
<main class="max-w-[1280px] mx-auto px-4 lg:px-40 py-8">
    <!-- Breadcrumbs -->
    <div class="flex flex-wrap gap-2 mb-6">
        <a class="text-[#616f89] dark:text-slate-400 text-sm font-medium hover:text-primary transition-colors" href="{{ route('index') }}">{{ __('lang.home') }}</a>
        <span class="text-[#616f89] dark:text-slate-600 text-sm font-medium">/</span>
        <a class="text-[#616f89] dark:text-slate-400 text-sm font-medium hover:text-primary transition-colors" href="#">{{ __('lang.legal') }}</a>
        <span class="text-[#616f89] dark:text-slate-600 text-sm font-medium">/</span>
        <span class="text-[#111318] dark:text-white text-sm font-medium">{{ __('privacy-policy.privacy_policy_title') }}</span>
    </div>

    <!-- Page Heading -->
    <div class="flex flex-wrap justify-between items-end gap-3 mb-12">
        <div class="flex flex-col gap-3">
            <h1 class="text-[#111318] dark:text-white text-5xl font-black leading-tight tracking-[-0.033em]">{{ __('privacy-policy.privacy_policy_title') }}</h1>
            <p class="text-[#616f89] dark:text-slate-400 text-base font-normal">{{ __('privacy-policy.privacy_subtitle') }}</p>
        </div>
        <div class="flex flex-col items-end gap-3">
            <p class="text-[#616f89] dark:text-slate-400 text-sm font-medium">{{ __('privacy-policy.last_updated') }}: {{ __('privacy-policy.last_updated_date') }}</p>
            <button class="flex min-w-[140px] cursor-pointer items-center justify-center gap-2 rounded-lg h-10 px-4 bg-[#f0f2f4] dark:bg-slate-800 text-[#111318] dark:text-white text-sm font-bold hover:bg-[#e4e6e8] dark:hover:bg-slate-700 transition-colors">
                <span class="material-symbols-outlined text-[18px]">download</span>
                <span>{{ __('lang.download_pdf') }}</span>
            </button>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-12">
        <!-- Sticky Side Navigation -->
        <aside class="hidden lg:block w-64 flex-shrink-0">
            <div class="sticky-sidebar flex flex-col gap-4">
                <div class="flex flex-col">
                    <h3 class="text-[#111318] dark:text-white text-base font-bold leading-normal">{{ __('privacy-policy.sections_title') }}</h3>
                    <p class="text-[#616f89] dark:text-slate-400 text-xs font-normal uppercase tracking-wider">{{ __('privacy-policy.quick_nav') }}</p>
                </div>
                <nav class="flex flex-col gap-1">
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 text-primary group" href="#introduction">
                        <span class="material-symbols-outlined text-[20px]">info</span>
                        <span class="text-sm font-bold">{{ __('privacy-policy.introduction') }}</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#616f89] dark:text-slate-400 hover:bg-[#f0f2f4] dark:hover:bg-slate-800 transition-colors group" href="#data-collection">
                        <span class="material-symbols-outlined text-[20px] group-hover:text-primary">database</span>
                        <span class="text-sm font-medium group-hover:text-primary">{{ __('privacy-policy.information_we_collect') }}</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#616f89] dark:text-slate-400 hover:bg-[#f0f2f4] dark:hover:bg-slate-800 transition-colors group" href="#ai-processing">
                        <span class="material-symbols-outlined text-[20px] group-hover:text-primary">memory</span>
                        <span class="text-sm font-medium group-hover:text-primary">{{ __('privacy-policy.how_we_use_information') }}</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#616f89] dark:text-slate-400 hover:bg-[#f0f2f4] dark:hover:bg-slate-800 transition-colors group" href="#cookie-policy">
                        <span class="material-symbols-outlined text-[20px] group-hover:text-primary">cookie</span>
                        <span class="text-sm font-medium group-hover:text-primary">{{ __('privacy-policy.cookies_and_tracking') }}</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#616f89] dark:text-slate-400 hover:bg-[#f0f2f4] dark:hover:bg-slate-800 transition-colors group" href="#user-rights">
                        <span class="material-symbols-outlined text-[20px] group-hover:text-primary">verified_user</span>
                        <span class="text-sm font-medium group-hover:text-primary">{{ __('privacy-policy.your_rights') }}</span>
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Content Area -->
        <div class="flex-1 max-w-3xl prose prose-slate dark:prose-invert">
            <!-- Section: Introduction -->
            <section class="mb-16" id="introduction">
                <div class="mb-6 p-5 rounded-xl border border-primary/20 bg-primary/5 @container">
                    <div class="flex flex-1 flex-col items-start justify-between gap-4 @[480px]:flex-row @[480px]:items-center">
                        <div class="flex flex-col gap-1">
                            <p class="text-primary text-base font-bold leading-tight">{{ __('privacy-policy.key_summary') }}</p>
                            <p class="text-[#616f89] dark:text-slate-400 text-base font-normal leading-normal">
                                {{ __('privacy-policy.privacy_intro') }}
                            </p>
                        </div>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-[#111318] dark:text-white mb-4">1. {{ __('privacy-policy.introduction') }}</h2>
                <p>{{ __('privacy-policy.intro_content') }}</p>
            </section>

            <!-- Section: Data Collection -->
            <section class="mb-16" id="data-collection">
                <h2 class="text-2xl font-bold text-[#111318] dark:text-white mb-4">2. {{ __('privacy-policy.information_we_collect') }}</h2>
                <p>{{ __('privacy-policy.collect_description') }}</p>
                <ul class="list-disc pl-6 mb-4 space-y-2 text-[#616f89] dark:text-slate-400">
                    <li><strong>{{ __('privacy-policy.personal_information') }}:</strong> {{ __('privacy-policy.personal_info_description') }}</li>
                    <li><strong>{{ __('privacy-policy.usage_data') }}:</strong> {{ __('privacy-policy.usage_data_description') }}</li>
                    <li><strong>{{ __('privacy-policy.geo_data') }}:</strong> {{ __('privacy-policy.geo_data_description') }}</li>
                    <li><strong>{{ __('privacy-policy.ai_logs') }}:</strong> {{ __('privacy-policy.ai_logs_description') }}</li>
                </ul>
            </section>

            <!-- Section: AI Processing -->
            <section class="mb-16" id="ai-processing">
                <div class="mb-6 p-5 rounded-xl border border-[#dbdfe6] dark:border-slate-700 bg-white dark:bg-slate-900/50">
                    <div class="flex gap-4 items-start">
                        <div class="bg-primary/10 p-2 rounded-lg text-primary">
                            <span class="material-symbols-outlined">psychology</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-[#111318] dark:text-white mb-1">{{ __('privacy-policy.ai_ethics') }}</h4>
                            <p class="text-sm text-[#616f89] dark:text-slate-400">{{ __('privacy-policy.ai_ethics_desc') }}</p>
                        </div>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-[#111318] dark:text-white mb-4">3. {{ __('privacy-policy.how_we_use_information') }}</h2>
                <p>{{ __('privacy-policy.use_information_description') }}</p>
            </section>

            <!-- Section: Cookie Policy -->
            <section class="mb-16" id="cookie-policy">
                <h2 class="text-2xl font-bold text-[#111318] dark:text-white mb-4">4. {{ __('privacy-policy.cookies_and_tracking') }}</h2>
                <p>{{ __('privacy-policy.cookies_description') }}</p>
                <div class="overflow-x-auto rounded-lg border border-[#f0f2f4] dark:border-slate-800 mt-4">
                    <table class="min-w-full divide-y divide-[#f0f2f4] dark:divide-slate-800">
                        <thead class="bg-[#f8f9fb] dark:bg-slate-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-[#616f89] uppercase tracking-wider">{{ __('privacy-policy.cookie_type') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-[#616f89] uppercase tracking-wider">{{ __('privacy-policy.cookie_purpose') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#f0f2f4] dark:divide-slate-800 text-sm">
                            <tr>
                                <td class="px-6 py-4 font-medium text-[#111318] dark:text-white">{{ __('privacy-policy.essential_cookies') }}</td>
                                <td class="px-6 py-4 text-[#616f89] dark:text-slate-400">{{ __('privacy-policy.essential_cookies_description') }}</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 font-medium text-[#111318] dark:text-white">{{ __('privacy-policy.analytics_cookies') }}</td>
                                <td class="px-6 py-4 text-[#616f89] dark:text-slate-400">{{ __('privacy-policy.analytics_cookies_description') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Section: User Rights -->
            <section class="mb-16" id="user-rights">
                <h2 class="text-2xl font-bold text-[#111318] dark:text-white mb-4">5. {{ __('privacy-policy.your_rights') }}</h2>
                <p>{{ __('privacy-policy.your_rights_description') }}</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                    <div class="p-4 border border-[#f0f2f4] dark:border-slate-800 rounded-lg">
                        <h5 class="font-bold mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[20px]">visibility</span>
                            {{ __('privacy-policy.right_access') }}
                        </h5>
                        <p class="text-xs text-[#616f89] dark:text-slate-400">{{ __('privacy-policy.right_access_desc') }}</p>
                    </div>
                    <div class="p-4 border border-[#f0f2f4] dark:border-slate-800 rounded-lg">
                        <h5 class="font-bold mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[20px]">delete</span>
                            {{ __('privacy-policy.right_erasure') }}
                        </h5>
                        <p class="text-xs text-[#616f89] dark:text-slate-400">{{ __('privacy-policy.right_erasure_desc') }}</p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>
@endsection
