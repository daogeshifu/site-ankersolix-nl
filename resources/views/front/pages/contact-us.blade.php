@extends('layouts.stitch.master')

@section('title', __('contact-us.title') . ' - ' . config('app.name'))
@section('description', __('contact-us.contact_description'))
@section('keywords', __('contact-us.contact_keywords'))

@push('styles')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    .dark .glass-card {
        background: rgba(16, 22, 34, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
</style>
@endpush

@section('content')
<main class="max-w-7xl mx-auto px-6 py-12 md:py-20">
    <!-- Hero Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
        <!-- Left Side: Content & Contact Details -->
        <div class="space-y-12">
            <div class="space-y-6">
                <h1 class="text-5xl md:text-6xl font-black text-[#111318] dark:text-white leading-[1.1] tracking-tight">
                    {{ __('contact-us.title') }} <span class="text-primary">{{ __('contact-us.hero_highlight') }}</span>
                </h1>
                <p class="text-[#616f89] dark:text-gray-400 text-lg md:text-xl max-w-lg leading-relaxed">
                    {{ __('contact-us.description') }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                <div class="flex flex-col gap-2 border-t border-solid border-[#dbdfe6] dark:border-gray-800 pt-6">
                    <p class="text-primary text-sm font-bold uppercase tracking-widest">{{ __('contact-us.general_inquiries') }}</p>
                    <p class="text-[#111318] dark:text-white text-lg font-medium">
                        <a href="mailto:{{ config('mail.from.address') }}" class="hover:text-primary transition-colors">
                            {{ config('mail.from.address') }}
                        </a>
                    </p>
                </div>
                <div class="flex flex-col gap-2 border-t border-solid border-[#dbdfe6] dark:border-gray-800 pt-6">
                    <p class="text-primary text-sm font-bold uppercase tracking-widest">{{ __('contact-us.partnerships') }}</p>
                    <p class="text-[#111318] dark:text-white text-lg font-medium">
                        @php
                            $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'example.com';
                        @endphp
                        <a href="mailto:partners@{{ $host }}" class="hover:text-primary transition-colors">
                            partners@   {{ $host }}
                        </a>
                    </p>
                </div>
                <div class="flex flex-col gap-2 border-t border-solid border-[#dbdfe6] dark:border-gray-800 pt-6">
                    <p class="text-primary text-sm font-bold uppercase tracking-widest">{{ __('contact-us.response_time') }}</p>
                    <p class="text-[#111318] dark:text-white text-lg font-medium">{{ __('contact-us.response_desc') }}</p>
                </div>
                <div class="flex flex-col gap-2 border-t border-solid border-[#dbdfe6] dark:border-gray-800 pt-6">
                    <p class="text-primary text-sm font-bold uppercase tracking-widest">{{ __('contact-us.social_presence') }}</p>
                    <div class="flex gap-4 mt-1">
                        <a class="text-[#616f89] hover:text-primary transition-colors" href="#"><span class="material-symbols-outlined">share</span></a>
                        <a class="text-[#616f89] hover:text-primary transition-colors" href="#"><span class="material-symbols-outlined">forum</span></a>
                        <a class="text-[#616f89] hover:text-primary transition-colors" href="#"><span class="material-symbols-outlined">terminal</span></a>
                    </div>
                </div>
            </div>

            <!-- Support Section -->
            <div class="p-8 rounded-xl bg-primary/5 dark:bg-primary/10 border border-primary/10 space-y-4">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">contact_support</span>
                    <h3 class="font-bold text-lg text-[#111318] dark:text-white">{{ __('contact-us.quick_support_title') }}</h3>
                </div>
                <p class="text-sm text-[#616f89] dark:text-gray-400">{{ __('contact-us.quick_support_desc') }}</p>
                <a class="text-primary font-bold text-sm inline-flex items-center gap-1 hover:gap-2 transition-all" href="{{ route('articles') }}">
                    {{ __('contact-us.visit_help_center') }} <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>
        </div>

        <!-- Right Side: Form Card -->
        <div class="relative">
            <!-- Background Decorative Element -->
            <div class="absolute -top-10 -right-10 w-64 h-64 bg-primary/20 rounded-full blur-[80px] -z-10"></div>
            <div class="absolute -bottom-10 -left-10 w-64 h-64 bg-blue-400/10 rounded-full blur-[80px] -z-10"></div>

            <div class="glass-card p-8 md:p-10 rounded-2xl shadow-2xl shadow-primary/5">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold mb-2 text-[#111318] dark:text-white">{{ __('contact-us.form_title') }}</h2>
                    <p class="text-[#616f89] dark:text-gray-400">{{ __('contact-us.form_subtitle') }}</p>
                </div>

                @if (session('success'))
                    <div class="mb-6 p-4 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('save-contact') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 ml-1">{{ __('contact-us.name') }}</label>
                            <input type="text" name="name" class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-background-dark focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-gray-400 dark:text-white" placeholder="{{ __('contact-us.name_placeholder') }}" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 ml-1">{{ __('contact-us.email') }}</label>
                            <input type="email" name="email" class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-background-dark focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-gray-400 dark:text-white" placeholder="{{ __('contact-us.email_placeholder') }}" required>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 ml-1">{{ __('contact-us.subject') }}</label>
                        <select name="subject" class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-background-dark focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all dark:text-white">
                            <option value="general">{{ __('contact-us.subject_general') }}</option>
                            <option value="consultation">{{ __('contact-us.subject_consultation') }}</option>
                            <option value="media">{{ __('contact-us.subject_media') }}</option>
                            <option value="feedback">{{ __('contact-us.subject_feedback') }}</option>
                            <option value="other">{{ __('contact-us.subject_other') }}</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 ml-1">{{ __('contact-us.message') }}</label>
                        <textarea name="message" rows="5" class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-background-dark focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-gray-400 dark:text-white" placeholder="{{ __('contact-us.message_placeholder') }}" required></textarea>
                    </div>

                    <button type="submit" class="w-full group relative flex items-center justify-center gap-2 bg-primary text-white py-4 px-8 rounded-lg font-bold text-lg overflow-hidden shadow-lg shadow-primary/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
                        <span class="relative z-10">{{ __('contact-us.submit') }}</span>
                        <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform relative z-10">send</span>
                        <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <section class="mt-32">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold mb-4 text-[#111318] dark:text-white">{{ __('contact-us.faq_title') }}</h2>
            <p class="text-[#616f89] dark:text-gray-400">{{ __('contact-us.faq_subtitle') }}</p>
        </div>

        <div class="max-w-3xl mx-auto space-y-4">
            <!-- FAQ 1 -->
            <div class="bg-white dark:bg-background-dark rounded-xl border border-gray-200 dark:border-gray-800 hover:border-primary transition-colors overflow-hidden">
                <button class="w-full p-6 flex justify-between items-center cursor-pointer group text-left faq-toggle" data-target="faq-1">
                    <h4 class="font-bold text-lg text-[#111318] dark:text-white">{{ __('contact-us.faq_1_question') }}</h4>
                    <span class="material-symbols-outlined text-gray-400 group-hover:text-primary transition-colors faq-icon">add</span>
                </button>
                <div id="faq-1" class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                    <div class="px-6 pb-6">
                        <p class="text-[#616f89] dark:text-gray-400 leading-relaxed">{{ __('contact-us.faq_1_answer') }}</p>
                    </div>
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="bg-white dark:bg-background-dark rounded-xl border border-gray-200 dark:border-gray-800 hover:border-primary transition-colors overflow-hidden">
                <button class="w-full p-6 flex justify-between items-center cursor-pointer group text-left faq-toggle" data-target="faq-2">
                    <h4 class="font-bold text-lg text-[#111318] dark:text-white">{{ __('contact-us.faq_2_question') }}</h4>
                    <span class="material-symbols-outlined text-gray-400 group-hover:text-primary transition-colors faq-icon">add</span>
                </button>
                <div id="faq-2" class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                    <div class="px-6 pb-6">
                        <p class="text-[#616f89] dark:text-gray-400 leading-relaxed">{{ __('contact-us.faq_2_answer') }}</p>
                    </div>
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="bg-white dark:bg-background-dark rounded-xl border border-gray-200 dark:border-gray-800 hover:border-primary transition-colors overflow-hidden">
                <button class="w-full p-6 flex justify-between items-center cursor-pointer group text-left faq-toggle" data-target="faq-3">
                    <h4 class="font-bold text-lg text-[#111318] dark:text-white">{{ __('contact-us.faq_3_question') }}</h4>
                    <span class="material-symbols-outlined text-gray-400 group-hover:text-primary transition-colors faq-icon">add</span>
                </button>
                <div id="faq-3" class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                    <div class="px-6 pb-6">
                        <p class="text-[#616f89] dark:text-gray-400 leading-relaxed">{{ __('contact-us.faq_3_answer') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const faqToggles = document.querySelectorAll('.faq-toggle');

    faqToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const content = document.getElementById(targetId);
            const icon = this.querySelector('.faq-icon');
            const allContents = document.querySelectorAll('.faq-content');
            const allIcons = document.querySelectorAll('.faq-icon');

            // 关闭其他所有 FAQ
            allContents.forEach(otherContent => {
                if (otherContent.id !== targetId && otherContent.style.maxHeight && otherContent.style.maxHeight !== '0px') {
                    otherContent.style.maxHeight = '0px';
                }
            });

            // 重置其他所有图标
            allIcons.forEach(otherIcon => {
                if (otherIcon !== icon) {
                    otherIcon.textContent = 'add';
                }
            });

            // 切换当前 FAQ
            if (content.style.maxHeight && content.style.maxHeight !== '0px') {
                content.style.maxHeight = '0px';
                icon.textContent = 'add';
            } else {
                content.style.maxHeight = content.scrollHeight + 'px';
                icon.textContent = 'remove';
            }
        });
    });
});
</script>
@endpush
