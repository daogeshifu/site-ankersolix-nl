@extends('layouts.around.master')

@section('title', __('terms-of-service.terms_title'))
@section('description', __('terms-of-service.terms_description'))
@section('keywords', __('terms-of-service.terms_keywords'))

@section('style')
<style>
    .terms-content h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
    }
    
    .terms-content h2 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .terms-content h3 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-top: 2rem;
        margin-bottom: 0.75rem;
    }
    
    .terms-content h4 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
    }
    
    .terms-content p {
        margin-bottom: 1rem;
        line-height: 1.6;
        color: #495057;
    }
    
    .terms-content ul,
    .terms-content ol {
        margin-bottom: 1rem;
        padding-left: 1.5rem;
    }
    
    .terms-content li {
        margin-bottom: 0.5rem;
        line-height: 1.6;
        color: #495057;
    }
    
    .terms-content strong {
        color: #212529;
    }
    
    .terms-content .table-container {
        overflow-x: auto;
        margin: 1.5rem 0;
    }
    
    .terms-content table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
    }
    
    .terms-content th,
    .terms-content td {
        padding: 0.75rem;
        border: 1px solid #dee2e6;
        text-align: left;
    }
    
    .terms-content th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    
    .last-updated {
        font-style: italic;
        color: #6c757d;
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 0.375rem;
        margin-bottom: 2rem;
    }
    
    .contact-info {
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-radius: 0.375rem;
        margin-top: 2rem;
    }
    
    .highlight-box {
        background-color: #f8f9fa;
        border-left: 4px solid #0d6efd;
        padding: 1rem 1.5rem;
        margin: 1.5rem 0;
        border-radius: 0.375rem;
    }
</style>
@endsection

@section('content')
<main class="page-wrapper">
    <!-- Page title -->
    <section class="container pt-5 pb-4 pb-lg-0 my-md-2 my-lg-5">
        <div class="row pt-5 pb-4 pb-lg-5 mb-2 mt-1 mt-sm-2 my-xl-3">
            <div class="col-md-12">
                <h1 class="display-3 fw-medium mb-0">{{ __('terms-of-service.terms_title') }}</h1>
            </div>
        </div>
        <hr>
    </section>

    <!-- Terms of Service Content -->
    <section class="container py-5 my-md-2 my-lg-3 my-xl-4 my-xxl-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9 terms-content">
                <div class="last-updated mb-5">
                    <p><strong>{{ __('terms-of-service.last_updated') }}:</strong> {{ __('terms-of-service.last_updated_date') }}</p>
                    <p class="mb-0">{{ __('terms-of-service.intro_note') }}</p>
                </div>
                
                <div class="highlight-box">
                    <p class="mb-0"><strong>{{ __('terms-of-service.important_notice') }}</strong> {{ __('terms-of-service.important_notice_content') }}</p>
                </div>
                
                <h2>{{ __('terms-of-service.acceptance_of_terms') }}</h2>
                <p>{{ __('terms-of-service.acceptance_content') }}</p>
                <p>{{ __('terms-of-service.agreement_notice') }}</p>
                
                <h2>{{ __('terms-of-service.eligibility') }}</h2>
                <p>{{ __('terms-of-service.eligibility_content') }}</p>
                <ul>
                    <li>{{ __('terms-of-service.eligibility_1') }}</li>
                    <li>{{ __('terms-of-service.eligibility_2') }}</li>
                    <li>{{ __('terms-of-service.eligibility_3') }}</li>
                </ul>
                
                <h2>{{ __('terms-of-service.account_registration') }}</h2>
                <p>{{ __('terms-of-service.registration_content') }}</p>
                <h3>{{ __('terms-of-service.account_security') }}</h3>
                <p>{{ __('terms-of-service.account_security_content') }}</p>
                <h3>{{ __('terms-of-service.account_termination') }}</h3>
                <p>{{ __('terms-of-service.termination_content') }}</p>
                
                <h2>{{ __('terms-of-service.user_conduct') }}</h2>
                <p>{{ __('terms-of-service.user_conduct_content') }}</p>
                <h3>{{ __('terms-of-service.prohibited_activities') }}</h3>
                <p>{{ __('terms-of-service.prohibited_content') }}</p>
                <ul>
                    <li>{{ __('terms-of-service.prohibited_1') }}</li>
                    <li>{{ __('terms-of-service.prohibited_2') }}</li>
                    <li>{{ __('terms-of-service.prohibited_3') }}</li>
                    <li>{{ __('terms-of-service.prohibited_4') }}</li>
                    <li>{{ __('terms-of-service.prohibited_5') }}</li>
                    <li>{{ __('terms-of-service.prohibited_6') }}</li>
                    <li>{{ __('terms-of-service.prohibited_7') }}</li>
                    <li>{{ __('terms-of-service.prohibited_8') }}</li>
                </ul>
                
                <h2>{{ __('terms-of-service.intellectual_property') }}</h2>
                <p>{{ __('terms-of-service.intellectual_property_content') }}</p>
                <h3>{{ __('terms-of-service.user_content') }}</h3>
                <p>{{ __('terms-of-service.user_content_content') }}</p>
                <h3>{{ __('terms-of-service.license_grant') }}</h3>
                <p>{{ __('terms-of-service.license_grant_content') }}</p>
                <h3>{{ __('terms-of-service.copyright_complaints') }}</h3>
                <p>{{ __('terms-of-service.copyright_content') }}</p>
                
                <h2>{{ __('terms-of-service.payments_and_subscriptions') }}</h2>
                <p>{{ __('terms-of-service.payments_content') }}</p>
                <h3>{{ __('terms-of-service.billing_cycles') }}</h3>
                <p>{{ __('terms-of-service.billing_content') }}</p>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('terms-of-service.plan') }}</th>
                                <th>{{ __('terms-of-service.billing_period') }}</th>
                                <th>{{ __('terms-of-service.price') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ __('terms-of-service.basic_plan') }}</td>
                                <td>{{ __('terms-of-service.monthly') }}</td>
                                <td>{{ __('terms-of-service.basic_price') }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('terms-of-service.pro_plan') }}</td>
                                <td>{{ __('terms-of-service.monthly') }}</td>
                                <td>{{ __('terms-of-service.pro_price') }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('terms-of-service.enterprise_plan') }}</td>
                                <td>{{ __('terms-of-service.annual') }}</td>
                                <td>{{ __('terms-of-service.enterprise_price') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <h3>{{ __('terms-of-service.refund_policy') }}</h3>
                <p>{{ __('terms-of-service.refund_content') }}</p>
                <h3>{{ __('terms-of-service.price_changes') }}</h3>
                <p>{{ __('terms-of-service.price_changes_content') }}</p>
                
                <h2>{{ __('terms-of-service.privacy_and_data') }}</h2>
                <p>{{ __('terms-of-service.privacy_content') }}</p>
                <ul>
                    <li>{{ __('terms-of-service.data_usage_1') }}</li>
                    <li>{{ __('terms-of-service.data_usage_2') }}</li>
                    <li>{{ __('terms-of-service.data_usage_3') }}</li>
                    <li>{{ __('terms-of-service.data_usage_4') }}</li>
                    <li>{{ __('terms-of-service.data_usage_5') }}</li>
                </ul>
                <h3>{{ __('terms-of-service.data_retention') }}</h3>
                <p>{{ __('terms-of-service.retention_content') }}</p>
                
                <h2>{{ __('terms-of-service.third_party_links') }}</h2>
                <p>{{ __('terms-of-service.third_party_content') }}</p>
                
                <h2>{{ __('terms-of-service.disclaimer_of_warranties') }}</h2>
                <p>{{ __('terms-of-service.warranties_content') }}</p>
                <h3>{{ __('terms-of-service.limitation_of_liability') }}</h3>
                <p>{{ __('terms-of-service.liability_content') }}</p>
                
                <h2>{{ __('terms-of-service.indemnification') }}</h2>
                <p>{{ __('terms-of-service.indemnification_content') }}</p>
                
                <h2>{{ __('terms-of-service.termination') }}</h2>
                <p>{{ __('terms-of-service.termination_clause_content') }}</p>
                <ul>
                    <li>{{ __('terms-of-service.termination_1') }}</li>
                    <li>{{ __('terms-of-service.termination_2') }}</li>
                    <li>{{ __('terms-of-service.termination_3') }}</li>
                    <li>{{ __('terms-of-service.termination_4') }}</li>
                </ul>
                
                <h2>{{ __('terms-of-service.governing_law') }}</h2>
                <p>{{ __('terms-of-service.governing_law_content') }}</p>
                <h3>{{ __('terms-of-service.dispute_resolution') }}</h3>
                <p>{{ __('terms-of-service.dispute_content') }}</p>
                
                <h2>{{ __('terms-of-service.changes_to_terms') }}</h2>
                <p>{{ __('terms-of-service.changes_content') }}</p>
                <ul>
                    <li>{{ __('terms-of-service.changes_notice_1') }}</li>
                    <li>{{ __('terms-of-service.changes_notice_2') }}</li>
                    <li>{{ __('terms-of-service.changes_notice_3') }}</li>
                </ul>
                
                <h2>{{ __('terms-of-service.miscellaneous') }}</h2>
                <p>{{ __('terms-of-service.miscellaneous_content') }}</p>
                <h3>{{ __('terms-of-service.entire_agreement') }}</h3>
                <p>{{ __('terms-of-service.entire_agreement_content') }}</p>
                <h3>{{ __('terms-of-service.severability') }}</h3>
                <p>{{ __('terms-of-service.severability_content') }}</p>
                <h3>{{ __('terms-of-service.assignment') }}</h3>
                <p>{{ __('terms-of-service.assignment_content') }}</p>
                <h3>{{ __('terms-of-service.force_majeure') }}</h3>
                <p>{{ __('terms-of-service.force_majeure_content') }}</p>
                
                <h2>{{ __('terms-of-service.contact_information') }}</h2>
                <div class="contact-info">
                    <p><strong>{{ __('terms-of-service.company_name') }}</strong><br>
                    {{ __('terms-of-service.company_address') }}</p>
                    
                    <p class="mb-1"><strong>{{ __('terms-of-service.email') }}:</strong> {{ __('terms-of-service.contact_email') }}</p>
                    <p class="mb-1"><strong>{{ __('terms-of-service.phone') }}:</strong> {{ __('terms-of-service.contact_phone') }}</p>
                    <p class="mb-0"><strong>{{ __('terms-of-service.website') }}:</strong> {{ __('terms-of-service.website_url') }}</p>
                </div>
                
                <div class="mt-5 pt-4 border-top text-center">
                    <p class="text-muted">{{ __('terms-of-service.closing_statement') }}</p>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('script')
<script>
    // 添加平滑滚动效果
    document.querySelectorAll('h2, h3, h4').forEach(heading => {
        heading.style.scrollMarginTop = '80px';
        
        // 添加锚点链接
        const id = heading.textContent.toLowerCase().replace(/[^\w]+/g, '-').replace(/^-+|-+$/g, '');
        heading.id = id;
        
        const link = document.createElement('a');
        link.href = `#${id}`;
        link.className = 'heading-link ms-2';
        link.innerHTML = '<i class="ai-link fs-sm"></i>';
        link.title = 'Link to this section';
        heading.appendChild(link);
    });
    
    // 添加返回顶部按钮
    const backToTopBtn = document.createElement('button');
    backToTopBtn.className = 'btn btn-outline-primary btn-sm position-fixed bottom-0 end-0 m-3 d-none';
    backToTopBtn.innerHTML = '<i class="ai-arrow-up me-1"></i> Back to Top';
    backToTopBtn.style.zIndex = '1000';
    document.body.appendChild(backToTopBtn);
    
    backToTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 500) {
            backToTopBtn.classList.remove('d-none');
        } else {
            backToTopBtn.classList.add('d-none');
        }
    });
</script>
@endsection