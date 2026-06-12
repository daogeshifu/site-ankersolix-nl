@extends('layouts.stitch.master')

@section('title', __('about-us.title') . ' | ' . (parse_url(url('/'), PHP_URL_HOST) ?: __('home.super')))
@section('description', __('about-us.subtitle'))
@section('keywords', __('lang.about_keywords'))

@push('styles')
<style>
    .glass-gradient {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }
    .dark .glass-gradient {
        background: rgba(16, 22, 34, 0.7);
    }
</style>
@endpush

@section('content')
<main class="max-w-[1200px] mx-auto px-4 py-12">
    <!-- Hero Section -->
    <section class="mb-20">
        <div class="relative overflow-hidden rounded-xl bg-primary/5 dark:bg-primary/10 border border-primary/10">
            <div class="flex min-h-[420px] flex-col gap-6 items-center justify-center p-8 text-center bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-primary/10 via-transparent to-transparent">
                <div class="flex flex-col gap-4 max-w-3xl">
                    <span class="text-primary font-bold tracking-widest text-xs uppercase">{{ __('about-us.since_year') }}</span>
                    <h1 class="text-[#111318] dark:text-white text-4xl md:text-6xl font-black leading-tight tracking-tight">
                        {{ __('about-us.hero_title') }}
                    </h1>
                    <p class="text-[#616f89] dark:text-gray-400 text-lg md:text-xl font-normal leading-relaxed">
                        {{ __('about-us.subtitle') }}
                    </p>
                </div>
                <div class="mt-4">
                    <a href="{{ route('articles') }}" class="bg-primary text-white text-base font-bold px-8 py-3 rounded-lg hover:shadow-lg hover:shadow-primary/30 transition-all inline-block">
                        {{ __('about-us.explore_mission') }}
                    </a>
                </div>
            </div>
            <!-- Abstract BG pattern decoration -->
            <div class="absolute -bottom-24 -left-24 size-64 bg-primary/10 rounded-full blur-3xl"></div>
            <div class="absolute -top-24 -right-24 size-64 bg-primary/10 rounded-full blur-3xl"></div>
        </div>
    </section>

    <!-- Feature Section (Mission & Vision) -->
    <section class="py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="flex flex-col gap-6">
                <h2 class="text-primary font-bold text-sm tracking-widest uppercase">{{ __('about-us.our_purpose') }}</h2>
                <h3 class="text-[#111318] dark:text-white text-3xl md:text-4xl font-bold leading-tight">{{ __('about-us.intro_title') }}</h3>
                <p class="text-[#616f89] dark:text-gray-400 text-lg leading-relaxed">
                    {{ __('about-us.intro_text') }}
                </p>
            </div>
            <div class="grid grid-cols-1 gap-4">
                <div class="group p-8 rounded-xl border border-[#dbdfe6] dark:border-gray-800 bg-white dark:bg-gray-900/50 hover:border-primary transition-all">
                    <div class="text-primary mb-4">
                        <span class="material-symbols-outlined !text-4xl">lightbulb</span>
                    </div>
                    <h4 class="text-[#111318] dark:text-white text-xl font-bold mb-2">{{ __('about-us.mission_title') }}</h4>
                    <p class="text-[#616f89] dark:text-gray-400 text-base leading-normal">
                        {{ __('about-us.mission_text') }}
                    </p>
                </div>
                <div class="group p-8 rounded-xl border border-[#dbdfe6] dark:border-gray-800 bg-white dark:bg-gray-900/50 hover:border-primary transition-all">
                    <div class="text-primary mb-4">
                        <span class="material-symbols-outlined !text-4xl">rocket_launch</span>
                    </div>
                    <h4 class="text-[#111318] dark:text-white text-xl font-bold mb-2">{{ __('about-us.vision_title') }}</h4>
                    <p class="text-[#616f89] dark:text-gray-400 text-base leading-normal">
                        {{ __('about-us.vision_text') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why We Exist Section -->
    <section class="py-20 border-y border-[#f0f2f4] dark:border-gray-800 my-16">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-[#111318] dark:text-white text-3xl font-bold mb-8">{{ __('about-us.values_title') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-left">
                <div class="flex flex-col gap-3">
                    <div class="text-2xl font-bold text-primary">01.</div>
                    <h5 class="font-bold dark:text-white">{{ __('about-us.value_1_title') }}</h5>
                    <p class="text-sm text-[#616f89] dark:text-gray-400">{{ __('about-us.value_1_text') }}</p>
                </div>
                <div class="flex flex-col gap-3">
                    <div class="text-2xl font-bold text-primary">02.</div>
                    <h5 class="font-bold dark:text-white">{{ __('about-us.value_2_title') }}</h5>
                    <p class="text-sm text-[#616f89] dark:text-gray-400">{{ __('about-us.value_2_text') }}</p>
                </div>
                <div class="flex flex-col gap-3">
                    <div class="text-2xl font-bold text-primary">03.</div>
                    <h5 class="font-bold dark:text-white">{{ __('about-us.value_3_title') }}</h5>
                    <p class="text-sm text-[#616f89] dark:text-gray-400">{{ __('about-us.value_3_text') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    @if(isset($team) && count($team) > 0)
    <section class="mb-12">
        <div class="flex items-end justify-between px-4 pb-6">
            <div>
                <h2 class="text-[#111318] dark:text-white text-3xl font-bold leading-tight">{{ __('about-us.team_title') }}</h2>
                <p class="text-[#616f89] dark:text-gray-400 mt-2">{{ __('about-us.team_subtitle') }}</p>
            </div>
        </div>
        <!-- Team Members Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 px-4">
            @foreach($team as $member)
            <div class="flex flex-col gap-4 group">
                <div class="w-full bg-center bg-no-repeat aspect-[4/5] bg-cover rounded-xl overflow-hidden shadow-sm group-hover:shadow-md transition-all bg-gradient-to-br from-primary/20 to-blue-400/20 flex items-center justify-center">
                    @if($member->avatar)
                        <img src="{{ Storage::url($member->avatar) }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-6xl font-bold text-primary/50">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                    @endif
                </div>
                <div class="p-2 border-l-2 border-transparent group-hover:border-primary transition-all">
                    <p class="text-[#111318] dark:text-white text-lg font-bold">{{ $member->name }}</p>
                    <p class="text-primary text-sm font-medium mb-2">{{ $member->role }}</p>
                    <p class="text-[#616f89] dark:text-gray-400 text-sm leading-relaxed">{{ $member->bio }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Timeline Section -->
    <section class="py-20 mt-16 bg-white dark:bg-gray-900 rounded-2xl p-8 md:p-12 border border-[#f0f2f4] dark:border-gray-800">
        <h2 class="text-[#111318] dark:text-white text-3xl font-bold mb-12 text-center">{{ __('about-us.timeline_title') }}</h2>
        <div class="relative space-y-12 before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-primary/30 before:to-transparent">
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                <div class="flex items-center justify-center w-10 h-10 rounded-full border border-primary bg-white dark:bg-gray-900 text-primary shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2">
                    <span class="material-symbols-outlined !text-sm">verified</span>
                </div>
                <div class="w-[calc(100%-4rem)] md:w-[45%] p-6 rounded-xl border border-[#dbdfe6] dark:border-gray-800 bg-background-light dark:bg-gray-800/50 shadow-sm">
                    <div class="flex items-center justify-between space-x-2 mb-1">
                        <div class="font-bold text-[#111318] dark:text-white text-lg">{{ __('about-us.timeline_1_title') }}</div>
                        <time class="font-display font-medium text-primary">{{ __('about-us.timeline_1_date') }}</time>
                    </div>
                    <div class="text-[#616f89] dark:text-gray-400">{{ __('about-us.timeline_1_desc') }}</div>
                </div>
            </div>
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                <div class="flex items-center justify-center w-10 h-10 rounded-full border border-primary bg-white dark:bg-gray-900 text-primary shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2">
                    <span class="material-symbols-outlined !text-sm">analytics</span>
                </div>
                <div class="w-[calc(100%-4rem)] md:w-[45%] p-6 rounded-xl border border-[#dbdfe6] dark:border-gray-800 bg-background-light dark:bg-gray-800/50 shadow-sm">
                    <div class="flex items-center justify-between space-x-2 mb-1">
                        <div class="font-bold text-[#111318] dark:text-white text-lg">{{ __('about-us.timeline_2_title') }}</div>
                        <time class="font-display font-medium text-primary">{{ __('about-us.timeline_2_date') }}</time>
                    </div>
                    <div class="text-[#616f89] dark:text-gray-400">{{ __('about-us.timeline_2_desc') }}</div>
                </div>
            </div>
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                <div class="flex items-center justify-center w-10 h-10 rounded-full border border-primary bg-white dark:bg-gray-900 text-primary shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2">
                    <span class="material-symbols-outlined !text-sm">groups</span>
                </div>
                <div class="w-[calc(100%-4rem)] md:w-[45%] p-6 rounded-xl border border-[#dbdfe6] dark:border-gray-800 bg-background-light dark:bg-gray-800/50 shadow-sm">
                    <div class="flex items-center justify-between space-x-2 mb-1">
                        <div class="font-bold text-[#111318] dark:text-white text-lg">{{ __('about-us.timeline_3_title') }}</div>
                        <time class="font-display font-medium text-primary">{{ __('about-us.timeline_3_date') }}</time>
                    </div>
                    <div class="text-[#616f89] dark:text-gray-400">{{ __('about-us.timeline_3_desc') }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA Section -->
    <section class="py-16 text-center">
        <div class="max-w-2xl mx-auto p-12 rounded-2xl bg-primary text-white">
            <h2 class="text-3xl font-bold mb-4">{{ __('about-us.cta_title') }}</h2>
            <p class="text-white/80 text-lg mb-8">{{ __('about-us.cta_text') }}</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('articles') }}" class="bg-white text-primary font-bold px-8 py-3 rounded-lg hover:bg-gray-100 transition-all">{{ __('about-us.cta_button') }}</a>
                <a href="{{ route('contact') }}" class="border border-white/30 text-white font-bold px-8 py-3 rounded-lg hover:bg-white/10 transition-all">{{ __('contact-us.title') }}</a>
            </div>
        </div>
    </section>
</main>
@endsection
