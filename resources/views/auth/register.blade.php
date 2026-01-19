@extends('layouts.admin.master')


@section('title')登录 - AIGC 智能查重，全网首发检测者 4.0 大模型-发现PPT @endsection

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

              <blockquote class="blockquote blockquote-light">“ It has many landing page variations to choose from, which one is always a big advantage. ”</blockquote>

              <figcaption class="blockquote-footer blockquote-light">
                <div class="mb-3">
                  <img class="avatar avatar-circle" src="/htmlstream/static/picture/img91.jpg" alt="Image Description">
                </div>

                Lida Reidy
                <span class="blockquote-footer-source">Project Manager | Mailchimp</span>
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
              <h1 class="h2">Welcome to {{ config('app.name') }}</h1>
              <p>Create an account to start using the platform.</p>
            </div>
            <!-- End Heading -->

            <!-- Form -->
            <form id="register-form" action="{{ route('register') }}" class="js-validate needs-validation mt-32" method="POST">

                <div class="row row-cols-1 row-cols-md-2 g-3 mb-3">
                    <div class="col">
                        <label class="form-label">First Name *</label>
                        <input type="text" name="first_name" class="form-control input-with-br -h-48" value="" placeholder="First Name" maxlength="50" required="">
                    </div>
                    <div class="col">
                        <label class="form-label">Last Name *</label>
                        <input id="lastname" type="text" name="last_name" class="form-control input-with-br -h-48" value="" placeholder="Last Name" maxlength="50" required="">
                    </div>
                </div>


              <!-- email -->
              <div class="mb-4">
                <label class="form-label" for="signupModalFormLoginEmail">Your email</label>
                <input type="email" class="form-control form-control-lg" name="email" id="signupModalFormLoginEmail" placeholder="请输入邮箱" aria-label="请输入邮箱" required="">
                <span class="invalid-feedback">Please enter a valid email address.</span>
              </div>
              <!-- End email -->

              <!-- Password -->
              <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <label class="form-label" for="signupModalFormLoginPassword">Password</label>

                  <a class="form-label-link" href="{{ route('auth.password.reset') }}">Forgot Password?</a>
                </div>

                <div class="input-group input-group-merge" data-hs-validation-validate-class="">
                  <input type="password" class="js-toggle-password form-control form-control-lg" name="password" id="signupModalFormLoginPassword" placeholder="8+ characters required" aria-label="8+ characters required" required="" minlength="8" data-hs-toggle-password-options="{
                         &quot;target&quot;: &quot;#changePassTarget&quot;,
                         &quot;defaultClass&quot;: &quot;bi-eye-slash&quot;,
                         &quot;showClass&quot;: &quot;bi-eye&quot;,
                         &quot;classChangeTarget&quot;: &quot;#changePassIcon&quot;
                       }">
                  <a id="changePassTarget" class="input-group-append input-group-text" href="{{ route('auth.password.reset') }}">
                    <i id="changePassIcon" class="bi-eye-slash"></i>
                  </a>
                </div>
              </div>
                <!-- End Password -->



              <!-- Confirm Password -->
              <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <label class="form-label" for="signupModalFormLoginPassword">Confirm Password</label>
                </div>

                <div class="input-group input-group-merge" data-hs-validation-validate-class="">
                  <input type="password" class="js-toggle-password form-control form-control-lg" name="password_confirmation" id="signupModalFormLoginPassword" 
                  placeholder=" The same as password" aria-label="The same as password" required="" minlength="8" data-hs-toggle-password-options="{
                         &quot;target&quot;: &quot;#changePassTarget&quot;,
                         &quot;defaultClass&quot;: &quot;bi-eye-slash&quot;,
                         &quot;showClass&quot;: &quot;bi-eye&quot;,
                         &quot;classChangeTarget&quot;: &quot;#changePassIcon&quot;
                       }" data-hs-validation-equal-field="#signupModalFormLoginPassword">
                  <a id="changePassTarget" class="input-group-append input-group-text" href="{{ route('auth.password.reset') }}">
                    <i id="changePassIcon" class="bi-eye-slash"></i>
                  </a>  
                </div>
                <!-- End Confirm Password -->


                <span class="invalid-feedback">Please enter a valid password.</span>
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
                        <input type="text" name="captcha"  class="form-control mt-2 ml-2 " style="margin-left: 10px;"   placeholder="Enter the text in the image" />
                    </div>
              </div>

              <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary btn-lg" id="register-button">Register</button>
              </div>

              <div class="text-center">
                <p>Already have an account? <a class="link" href="{{ route('login') }}">Login here</a></p>
              </div>
            </form>
            <!-- End Form -->
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
        $('#register-form').submit(function(e) {
            e.preventDefault(); // 阻止默认提交
    
            var form = $(this);
            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: form.serialize(),
                success: function(res) {
                    if(res.success) {
                        alert(res.message);
                        window.location.href = '{{ route('index') }}';
                    } else {
                        alert(res.message || '注册失败');
                        refreshCaptcha();
                    }
                },
                error: function(xhr) {
                    if(xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        // 提取第一个错误提示弹窗
                        var msg = '';
                        if(errors.first_name) msg = errors.first_name[0];
                        else if(errors.last_name) msg = errors.last_name[0];
                        else if(errors.email) msg = errors.email[0];
                        else if(errors.password) msg = errors.password[0];
                        else if(errors.captcha) msg = errors.captcha[0];
                        else msg = '表单验证失败';
                        alert(msg);
                    } else {
                        alert('请求失败，请稍后重试');
                    }
                    refreshCaptcha();
                }
            });
    
            function refreshCaptcha() {
                $('img[alt="captcha"]').attr('src', '{{ captcha_src('default') }}?t=' + Math.random());
            }
        });
    });
</script>
    
@endsection

