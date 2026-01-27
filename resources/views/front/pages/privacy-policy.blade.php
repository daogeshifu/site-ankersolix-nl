@extends('layouts.around.master')

@section('title', __('privacy-policy.privacy_policy_title'))
@section('description', __('privacy-policy.privacy_policy_description'))
@section('keywords', __('privacy-policy.privacy_policy_keywords'))

@section('style')
<style>
    .privacy-content h2 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
    }
    
    .privacy-content h3 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-top: 2rem;
        margin-bottom: 0.75rem;
    }
    
    .privacy-content p {
        margin-bottom: 1rem;
        line-height: 1.6;
    }
    
    .privacy-content ul {
        margin-bottom: 1rem;
        padding-left: 1.5rem;
    }
    
    .privacy-content li {
        margin-bottom: 0.5rem;
        line-height: 1.6;
    }
    
    .last-updated {
        font-style: italic;
        color: #6c757d;
    }
</style>
@endsection

@section('content')
<main class="page-wrapper">
    <!-- Page title -->
    <section class="container pt-5 pb-4 pb-lg-0 my-md-2 my-lg-5">
        <div class="row pt-5 pb-4 pb-lg-5 mb-2 mt-1 mt-sm-2 my-xl-3">
            <div class="col-md-12">
                <h1 class="display-3 fw-medium mb-0">{{ __('privacy-policy.privacy_policy_title') }}</h1>
            </div>
        </div>
        <hr>
    </section>

    <!-- Privacy Policy Content -->
    <section class="container py-5 my-md-2 my-lg-3 my-xl-4 my-xxl-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9 privacy-content">
                <p class="last-updated mb-4">{{ __('privacy-policy.last_updated') }}: {{ __('privacy-policy.last_updated_date') }}</p>
                
                <p>{{ __('privacy-policy.privacy_intro') }}</p>
                
                <h2>{{ __('privacy-policy.information_we_collect') }}</h2>
                <p>{{ __('privacy-policy.collect_description') }}</p>
                
                <h3>{{ __('privacy-policy.personal_information') }}</h3>
                <p>{{ __('privacy-policy.personal_info_description') }}</p>
                <ul>
                    <li>{{ __('privacy-policy.personal_info_1') }}</li>
                    <li>{{ __('privacy-policy.personal_info_2') }}</li>
                    <li>{{ __('privacy-policy.personal_info_3') }}</li>
                    <li>{{ __('privacy-policy.personal_info_4') }}</li>
                    <li>{{ __('privacy-policy.personal_info_5') }}</li>
                    <li>{{ __('privacy-policy.personal_info_6') }}</li>
                </ul>
                
                <h3>{{ __('privacy-policy.usage_data') }}</h3>
                <p>{{ __('privacy-policy.usage_data_description') }}</p>
                <ul>
                    <li>{{ __('privacy-policy.usage_data_1') }}</li>
                    <li>{{ __('privacy-policy.usage_data_2') }}</li>
                    <li>{{ __('privacy-policy.usage_data_3') }}</li>
                    <li>{{ __('privacy-policy.usage_data_4') }}</li>
                    <li>{{ __('privacy-policy.usage_data_5') }}</li>
                </ul>
                
                <h2>{{ __('privacy-policy.how_we_use_information') }}</h2>
                <p>{{ __('privacy-policy.use_information_description') }}</p>
                <ul>
                    <li>{{ __('privacy-policy.use_info_1') }}</li>
                    <li>{{ __('privacy-policy.use_info_2') }}</li>
                    <li>{{ __('privacy-policy.use_info_3') }}</li>
                    <li>{{ __('privacy-policy.use_info_4') }}</li>
                    <li>{{ __('privacy-policy.use_info_5') }}</li>
                    <li>{{ __('privacy-policy.use_info_6') }}</li>
                    <li>{{ __('privacy-policy.use_info_7') }}</li>
                </ul>
                
                <h2>{{ __('privacy-policy.cookies_and_tracking') }}</h2>
                <p>{{ __('privacy-policy.cookies_description') }}</p>
                <p>{{ __('privacy-policy.cookies_types_description') }}</p>
                <ul>
                    <li><strong>{{ __('privacy-policy.essential_cookies') }}:</strong> {{ __('privacy-policy.essential_cookies_description') }}</li>
                    <li><strong>{{ __('privacy-policy.preference_cookies') }}:</strong> {{ __('privacy-policy.preference_cookies_description') }}</li>
                    <li><strong>{{ __('privacy-policy.analytics_cookies') }}:</strong> {{ __('privacy-policy.analytics_cookies_description') }}</li>
                    <li><strong>{{ __('privacy-policy.marketing_cookies') }}:</strong> {{ __('privacy-policy.marketing_cookies_description') }}</li>
                </ul>
                
                <h2>{{ __('privacy-policy.data_sharing_disclosure') }}</h2>
                <p>{{ __('privacy-policy.sharing_description') }}</p>
                <ul>
                    <li>{{ __('privacy-policy.sharing_1') }}</li>
                    <li>{{ __('privacy-policy.sharing_2') }}</li>
                    <li>{{ __('privacy-policy.sharing_3') }}</li>
                    <li>{{ __('privacy-policy.sharing_4') }}</li>
                    <li>{{ __('privacy-policy.sharing_5') }}</li>
                </ul>
                
                <h2>{{ __('privacy-policy.data_security') }}</h2>
                <p>{{ __('privacy-policy.security_description') }}</p>
                <p>{{ __('privacy-policy.security_measures') }}</p>
                
                <h2>{{ __('privacy-policy.your_rights') }}</h2>
                <p>{{ __('privacy-policy.your_rights_description') }}</p>
                <ul>
                    <li>{{ __('privacy-policy.right_1') }}</li>
                    <li>{{ __('privacy-policy.right_2') }}</li>
                    <li>{{ __('privacy-policy.right_3') }}</li>
                    <li>{{ __('privacy-policy.right_4') }}</li>
                    <li>{{ __('privacy-policy.right_5') }}</li>
                    <li>{{ __('privacy-policy.right_6') }}</li>
                    <li>{{ __('privacy-policy.right_7') }}</li>
                </ul>
                
                <h2>{{ __('privacy-policy.children_privacy') }}</h2>
                <p>{{ __('privacy-policy.children_description') }}</p>
                
                <h2>{{ __('privacy-policy.changes_to_policy') }}</h2>
                <p>{{ __('privacy-policy.changes_description') }}</p>
                
                <h2>{{ __('privacy-policy.contact_us') }}</h2>
                <p>{{ __('privacy-policy.contact_description') }}</p>
                <p>
                    <strong>{{ __('privacy-policy.email') }}:</strong> {{ __('privacy-policy.contact_email') }}<br>
                    <strong>{{ __('privacy-policy.phone') }}:</strong> {{ __('privacy-policy.contact_phone') }}<br>
                    <strong>{{ __('privacy-policy.address') }}:</strong> {{ __('privacy-policy.contact_address') }}
                </p>
                
                <div class="mt-5 pt-4 border-top">
                    <p class="text-muted">{{ __('privacy-policy.privacy_closing') }}</p>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('script')
<script>
    // 添加平滑滚动效果
    document.querySelectorAll('h2, h3').forEach(heading => {
        heading.style.scrollMarginTop = '80px';
    });
</script>
@endsection