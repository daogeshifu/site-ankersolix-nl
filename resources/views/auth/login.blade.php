@extends('layouts.htmlstream.master_blank')


@section('title'){{ __('lang.login') }} - {{ config('app.name') }} @endsection

@section('style')
 
@endsection


@section('content')


<main id="content" role="main" class="flex-grow-1">
    <!-- Form -->
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-5 col-xl-4 d-none d-lg-flex justify-content-center align-items-center min-vh-lg-100 position-relative bg-dark" style="background-image: url(static/picture/wave-pattern-light.svg);">
          <div class="flex-grow-1 p-5">
            <!-- Blockquote -->
            <figure class="text-center">
              <div class="mb-4">
                <img class="avatar avatar-xl avatar-4x3" src="/htmlstream/static/picture/mailchimp-white.svg" alt="Logo">
              </div>

              <blockquote class="blockquote blockquote-light">{{ __('lang.login_testimonial_quote') }}</blockquote>

              <figcaption class="blockquote-footer blockquote-light">
                <div class="mb-3">
                  <img class="avatar avatar-circle" src="/htmlstream/static/picture/img91.jpg" alt="Image Description">
                </div>

                {{ __('lang.login_testimonial_author') }}
                <span class="blockquote-footer-source">{{ __('lang.login_testimonial_position') }}</span>
              </figcaption>
            </figure>
            <!-- End Blockquote -->

            <!-- Clients -->
            <div class="position-absolute start-0 end-0 bottom-0 text-center p-5">
              <div class="row justify-content-center">
                <div class="col text-center py-3">
                  <img class="avatar avatar-lg avatar-4x3" src="/htmlstream/static/picture/fitbit-white.svg" alt="Logo">
                </div>
                <!-- End Col -->

                <div class="col text-center py-3">
                  <img class="avatar avatar-lg avatar-4x3" src="/htmlstream/static/picture/business-insider-white.svg" alt="Logo">
                </div>
                <!-- End Col -->

                <div class="col text-center py-3">
                  <img class="avatar avatar-lg avatar-4x3" src="/htmlstream/static/picture/capsule-white.svg" alt="Logo">
                </div>
                <!-- End Col -->
              </div>
              <!-- End Row -->
            </div>
            <!-- End Clients -->
          </div>
        </div>
        <!-- End Col -->

        <div class="col-lg-7 col-xl-8 d-flex justify-content-center align-items-center min-vh-lg-100">
          <div class="flex-grow-1 mx-auto" style="max-width: 28rem;">
            <!-- Heading -->
            <div class="text-center mb-5 mb-md-7">
              <h1 class="h2">{{ __('lang.welcome_to') }} {{ config('app.name') }}</h1>
              <p>{{ __('lang.login_subtitle') }}</p>
            </div>
            <!-- End Heading -->

            <!-- Form -->
            <form id="login-form" action="{{ route('login') }}" class="js-validate needs-validation mt-32" method="POST">
              <!-- email -->
              <div class="mb-4">
                <label class="form-label" for="signupModalFormLoginEmail">{{ __('lang.email_address') }}</label>
                <input type="email" class="form-control form-control-lg" name="email" id="signupModalFormLoginEmail" placeholder="{{ __('lang.form_email_placeholder') }}" aria-label="{{ __('lang.email_address') }}" required="">
                <span class="invalid-feedback">{{ __('lang.error_email_required') }}</span>
              </div>
              <!-- End email -->

              <!-- Password -->
              <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <label class="form-label" for="signupModalFormLoginPassword">{{ __('lang.password') }}</label>

                  <a class="form-label-link" href="{{ route('auth.password.reset') }}">{{ __('lang.forgot_password') }}</a>
                </div>

                <div class="input-group input-group-merge" data-hs-validation-validate-class="">
                  <input type="password" class="js-toggle-password form-control form-control-lg" name="password" id="signupModalFormLoginPassword" placeholder="{{ __('lang.form_password_requirement') }}" aria-label="{{ __('lang.form_password_requirement') }}" required="" minlength="8" data-hs-toggle-password-options="{
                         &quot;target&quot;: &quot;#changePassTarget&quot;,
                         &quot;defaultClass&quot;: &quot;bi-eye-slash&quot;,
                         &quot;showClass&quot;: &quot;bi-eye&quot;,
                         &quot;classChangeTarget&quot;: &quot;#changePassIcon&quot;
                       }">
                  <a id="changePassTarget" class="input-group-append input-group-text" href="{{ route('auth.password.reset') }}">
                    <i id="changePassIcon" class="bi-eye-slash"></i>
                  </a>
                </div>
                <!-- End Password -->




                <span class="invalid-feedback">{{ __('lang.error_password_length') }}</span>
              </div>
              <!-- End Form -->

              <div class="mb-4">
                    <!-- Captcha -->
                    <div class="input-group mt-16 mb-16">
                        <div class="d-flex align-items-center">
                            <img src="{{ captcha_src('default') }}" alt="captcha"
                                onclick="this.src='{{ captcha_src('default') }}?t='+Math.random()"
                                style="cursor: pointer;">
                        </div>
                        <input type="text" name="captcha"  class="form-control mt-2 ml-2 " style="margin-left: 10px;"   placeholder="{{ __('lang.form_captcha_placeholder') }}" />
                    </div>
              </div>

              <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary btn-lg" id="login-button">{{ __('lang.login_btn') }}</button>
              </div>

              <div class="text-center">
                <p>{{ __('lang.dont_have_account_prefix') }} <a class="link" href="{{ route('register') }}">{{ __('lang.sign_up_here_link') }}</a></p>
              </div>
            </form>
            <!-- End Form -->

            <div class="modal-footer d-block text-center py-sm-5 border-top">
              <a class="btn btn-white btn-lg" href="{{ route('auth.google') }}">
                <span class="d-flex justify-content-center align-items-center">
                  <img class="avatar avatar-xss me-2" src="/htmlstream/static/picture/google-icon.svg" alt="Image Description">
                  {{ __('lang.sign_in_with_google') }}
                </span>
              </a>
            </div>
          </div>
        </div>

        <!-- End Col -->
      </div>
      <!-- End Row -->
    </div>
    <!-- End Form -->
  </main>
@endsection


@section('script')


<script>
    $(function() {
        $('#login-form').submit(function(e) {
            e.preventDefault(); // 阻止表单默认提交

            var form = $(this);
            var url = form.attr('action');
            var data = form.serialize();

            $.post(url, data)
                .done(function(response) {
                    // 假设登录成功返回重定向URL或成功标志
                    if(response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        alert("{{ __('lang.login_success') }}");
                    }
                })
                .fail(function(xhr) {
                    if(xhr.status === 422) { // Laravel 默认验证错误状态码
                        var errors = xhr.responseJSON.errors;
                        if(errors.captcha) {
                            alert(errors.captcha[0]); // 弹出验证码错误提示
                        } else if(errors.email) {
                            alert(errors.email[0]); // 邮箱错误
                        } else if(errors.password) {
                            alert(errors.password[0]); // 密码错误
                        } else {
                            alert("{{ __('lang.validation_failed') }}");
                        }
                        // 可刷新验证码图片
                        $('img[alt="captcha"]').attr('src', '{{ captcha_src('default') }}?t=' + Math.random());
                    } else if(xhr.status === 401) {
                        alert(xhr.responseJSON.message || "{{ __('lang.authentication_failed') }}");
                    } else {
                        alert("{{ __('lang.request_failed') }}");
                    }
                });
        });
    });
    </script>
    
@endsection

