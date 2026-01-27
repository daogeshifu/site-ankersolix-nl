@extends('layouts.stitch.master')

@section('title'){{ __('lang.login') }} - {{ config('app.name') }} @endsection

@push('styles')
<style>
    .mesh-gradient {
        background-color: #f6f6f8;
        background-image:
            radial-gradient(at 0% 0%, rgba(19, 91, 236, 0.05) 0px, transparent 50%),
            radial-gradient(at 100% 100%, rgba(19, 91, 236, 0.08) 0px, transparent 50%),
            radial-gradient(at 100% 0%, rgba(0, 212, 255, 0.05) 0px, transparent 50%);
    }
    .dark .mesh-gradient {
        background-color: #101622;
        background-image:
            radial-gradient(at 0% 0%, rgba(19, 91, 236, 0.15) 0px, transparent 50%),
            radial-gradient(at 100% 100%, rgba(19, 91, 236, 0.2) 0px, transparent 50%),
            radial-gradient(at 100% 0%, rgba(0, 212, 255, 0.1) 0px, transparent 50%);
    }
</style>
@endpush

@section('content')

<main class="flex-1 flex items-center justify-center p-6 mesh-gradient min-h-[calc(100vh-140px)]">
    <div class="w-full max-w-[460px]">
        <!-- Login Card -->
        <div class="bg-white dark:bg-[#1a212f] rounded-xl shadow-2xl shadow-black/5 border border-[#f0f2f4] dark:border-white/5 p-8 md:p-10">
            <!-- Section Header -->
            <div class="mb-2">
                <h4 class="text-primary text-xs font-bold uppercase tracking-[0.1em] text-center">{{ __('lang.welcome_back') }}</h4>
            </div>

            <!-- Title -->
            <div class="mb-8">
                <h2 class="text-[#111318] dark:text-white text-2xl font-bold text-center font-display">{{ __('lang.welcome_to') }} {{ config('app.name') }}</h2>
                <p class="text-[#616f89] dark:text-gray-400 text-sm text-center mt-2">{{ __('lang.login_subtitle') }}</p>
            </div>

            <!-- Google Sign In Button -->
            <div class="mb-6">
                <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-3 h-12 rounded-lg border border-[#e2e8f0] dark:border-white/10 bg-white dark:bg-white/5 hover:bg-gray-50 dark:hover:bg-white/10 transition-all text-[#111318] dark:text-white font-semibold">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"></path>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.66l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"></path>
                    </svg>
                    <span>{{ __('lang.sign_in_with_google') }}</span>
                </a>
            </div>

            <!-- Divider -->
            <div class="relative mb-8">
                <div class="absolute inset-0 flex items-center">
                    <span class="w-full border-t border-[#f0f2f4] dark:border-white/10"></span>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="bg-white dark:bg-[#1a212f] px-4 text-[#616f89] font-medium uppercase tracking-wider">{{ __('lang.or_sign_in_with_email') }}</span>
                </div>
            </div>

            <!-- Form -->
            <form id="login-form" action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-[#111318] dark:text-gray-200 mb-1.5 ml-1">{{ __('lang.email_address') }}</label>
                    <input type="email" name="email" class="w-full h-12 px-4 rounded-lg border border-[#e2e8f0] dark:border-white/10 dark:bg-white/5 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary transition-all text-sm outline-none" placeholder="{{ __('lang.form_email_placeholder') }}" required>
                </div>

                <!-- Password -->
                <div>
                    <div class="flex justify-between mb-1.5 ml-1">
                        <label class="block text-sm font-semibold text-[#111318] dark:text-gray-200">{{ __('lang.password') }}</label>
                        <a class="text-xs text-primary font-bold hover:underline" href="{{ route('auth.password.reset') }}">{{ __('lang.forgot_password') }}</a>
                    </div>
                    <div class="relative">
                        <input type="password" name="password" id="password-input" class="w-full h-12 px-4 rounded-lg border border-[#e2e8f0] dark:border-white/10 dark:bg-white/5 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary transition-all text-sm outline-none pr-12" placeholder="{{ __('lang.form_password_requirement') }}" required minlength="8">
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#616f89] hover:text-primary transition-colors">
                            <span id="password-icon" class="material-symbols-outlined text-[20px]">visibility</span>
                        </button>
                    </div>
                </div>

                <!-- Captcha -->
                <div>
                    <label class="block text-sm font-semibold text-[#111318] dark:text-gray-200 mb-1.5 ml-1">{{ __('lang.captcha') }}</label>
                    <div class="flex items-center gap-3">
                        <img src="{{ captcha_src('default') }}" alt="captcha" id="captcha-img" onclick="refreshCaptcha()" class="h-12 rounded-lg cursor-pointer hover:opacity-80 transition-opacity">
                        <input type="text" name="captcha" class="flex-1 h-12 px-4 rounded-lg border border-[#e2e8f0] dark:border-white/10 dark:bg-white/5 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary transition-all text-sm outline-none" placeholder="{{ __('lang.form_captcha_placeholder') }}" required>
                    </div>
                    <p class="text-xs text-[#616f89] mt-1 ml-1">{{ __('lang.click_image_refresh') }}</p>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="login-button" class="w-full h-12 bg-primary text-white font-bold rounded-lg shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all flex items-center justify-center gap-2 mt-2">
                    <span>{{ __('lang.login_btn') }}</span>
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </button>
            </form>

            <!-- Footer Text -->
            <p class="text-center text-sm text-[#616f89] dark:text-gray-400 mt-8">
                {{ __('lang.dont_have_account_prefix') }}
                <a class="text-primary font-bold hover:underline" href="{{ route('register') }}">{{ __('lang.sign_up_here_link') }}</a>
            </p>
        </div>

        <!-- Bottom Links -->
        <div class="mt-8 flex justify-center gap-6">
            <a class="text-xs text-[#616f89] dark:text-gray-400 hover:text-primary transition-colors" href="{{ route('policy') }}">{{ __('privacy-policy.privacy_policy_title') }}</a>
            <a class="text-xs text-[#616f89] dark:text-gray-400 hover:text-primary transition-colors" href="{{ route('terms') }}">{{ __('terms-of-service.terms_title') }}</a>
            <a class="text-xs text-[#616f89] dark:text-gray-400 hover:text-primary transition-colors" href="{{ route('help') }}">{{ __('help.title') }}</a>
        </div>
    </div>
</main>

@endsection

@push('scripts')
<script>
    // Toggle password visibility
    function togglePassword() {
        const input = document.getElementById('password-input');
        const icon = document.getElementById('password-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility';
        }
    }

    // Refresh captcha
    function refreshCaptcha() {
        document.getElementById('captcha-img').src = '{{ captcha_src('default') }}?t=' + Math.random();
    }

    // Form submission
    document.getElementById('login-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        const submitBtn = document.getElementById('login-button');

        // Disable button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="material-symbols-outlined text-[18px] animate-spin">progress_activity</span>';

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(({ status, body }) => {
            if (status === 200 && body.redirect) {
                window.location.href = body.redirect;
            } else if (status === 422) {
                // Validation errors
                const errors = body.errors;
                let errorMsg = '';
                if (errors.captcha) errorMsg = errors.captcha[0];
                else if (errors.email) errorMsg = errors.email[0];
                else if (errors.password) errorMsg = errors.password[0];
                else errorMsg = "{{ __('lang.validation_failed') }}";

                alert(errorMsg);
                refreshCaptcha();
            } else if (status === 401) {
                alert(body.message || "{{ __('lang.authentication_failed') }}");
                refreshCaptcha();
            } else {
                alert("{{ __('lang.request_failed') }}");
            }
        })
        .catch(() => {
            alert("{{ __('lang.request_failed') }}");
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span>{{ __("lang.login_btn") }}</span><span class="material-symbols-outlined text-[18px]">arrow_forward</span>';
        });
    });
</script>
@endpush
