<div class="page-header">
  <div class="header-wrapper row m-0">
    <form class="form-inline search-full col" action="javascript:;" method="get">
      <div class="form-group w-100">
        <div class="Typeahead Typeahead--twitterUsers">
          <div class="u-posRelative">
            <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text"
                   placeholder="Search Anything Here..." name="q" title="" autofocus="">
            <div class="spinner-border Typeahead-spinner" role="status"><span class="sr-only">Loading...</span></div>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="feather feather-x close-search">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </div>
          <div class="Typeahead-menu"></div>
        </div>
      </div>
    </form>
    <div class="header-logo-wrapper col-auto p-0">
      <div class="logo-wrapper"><a href="{{ route('index')}}"><img class="img-fluid"
                                                                   src="{{ asset('assets/images/logo/logo.png') }}"
                                                                   alt=""></a></div>
      <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="align-center"></i></div>
    </div>
    <div class="nav-right col-xxl-7 col-xl-6 col-md-7 col-8 pull-right right-header p-0 ms-auto">
      <ul class="nav-menus">

        {{-- 语言切换下拉菜单 --}}
        <li class="language-nav onhover-dropdown">
          <div class="translate_wrapper">
            <div class="current_lang">
              @php
                $currentLocale = app()->getLocale();
                $locales = config('laravellocalization.supportedLocales');
                $current = $locales[$currentLocale] ?? $locales['en'];

                // 国旗图标映射
                $flagIcons = [
                    'en' => 'flag-icon-us',
                    'zh' => 'flag-icon-cn',
                    'fr' => 'flag-icon-fr',
                ];
              @endphp
              <div class="lang">
                <i class="flag-icon {{ $flagIcons[$currentLocale] ?? 'flag-icon-us' }}"></i>
                <span class="lang-txt">{{ strtoupper($currentLocale) }}</span>
                <i class="fa-solid fa-angle-down ms-1"></i>
              </div>
            </div>
          </div>
          <ul class="language-dropdown onhover-show-div">
            @foreach($locales as $code => $locale)
              <li class="{{ $currentLocale === $code ? 'active' : '' }}">
                <a href="{{ route('lang.switch', $code) }}">
                  <i class="flag-icon {{ $flagIcons[$code] ?? 'flag-icon-us' }}"></i>
                  <span>{{ $locale['native'] }}</span>
                </a>
              </li>
            @endforeach
          </ul>
        </li>

        <li class="onhover-dropdown">

          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
               class="bi bi-piggy-bank-fill" viewBox="0 0 16 16">
            <path d="M7.964 1.527c-2.977 0-5.571 1.704-6.32 4.125h-.55A1 1 0 0 0 .11 6.824l.254 1.46a1.5 1.5 0 0 0 1.478 1.243h.263c.3.513.688.978 1.145 1.382l-.729 2.477a.5.5 0 0 0 .48.641h2a.5.5 0 0 0 .471-.332l.482-1.351c.635.173 1.31.267 2.011.267.707 0 1.388-.095 2.028-.272l.543 1.372a.5.5 0 0 0 .465.316h2a.5.5 0 0 0 .478-.645l-.761-2.506C13.81 9.895 14.5 8.559 14.5 7.069q0-.218-.02-.431c.261-.11.508-.266.705-.444.315.306.815.306.815-.417 0 .223-.5.223-.461-.026a1 1 0 0 0 .09-.255.7.7 0 0 0-.202-.645.58.58 0 0 0-.707-.098.74.74 0 0 0-.375.562c-.024.243.082.48.32.654a2 2 0 0 1-.259.153c-.534-2.664-3.284-4.595-6.442-4.595m7.173 3.876a.6.6 0 0 1-.098.21l-.044-.025c-.146-.09-.157-.175-.152-.223a.24.24 0 0 1 .117-.173c.049-.027.08-.021.113.012a.2.2 0 0 1 .064.199m-8.999-.65a.5.5 0 1 1-.276-.96A7.6 7.6 0 0 1 7.964 3.5c.763 0 1.497.11 2.18.315a.5.5 0 1 1-.287.958A6.6 6.6 0 0 0 7.964 4.5c-.64 0-1.255.09-1.826.254ZM5 6.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0"/>
          </svg>

          <div class="onhover-show-div bookmark-flip">
            <div class="flip-card">
              <div class="flip-card-inner">
                <div class="front">
                  <h6 class="f-18 mb-0 dropdown-title">My Wallet</h6>
                  @php
                    //查询 当前用户余额、消耗、充值，如果未登录，则显示 0
                    if (Auth::check()){
                        $amount = Auth::user()->amount;
                        $amount_consumed = \App\Models\User\UserAmountLog::getAmountConsumed();
                        $amount_recharged = \App\Models\User\UserAmountLog::getAmountRecharged();
                    }else{
                        $amount = 1;
                        $amount_consumed = 0;
                        $amount_recharged = 0;
                    }
                  @endphp
                  <ul class="bookmark-dropdown">
                    <li>
                      <div class="row">
                        <div class="col-4 text-center">
                          <a href="{{route('user-record-amounts',['type'=>'1'])}}" target="_blank">
                            <div class="bookmark-content">
                              <div class="bookmark-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-file-text">
                                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                  <polyline points="14 2 14 8 20 8"></polyline>
                                  <line x1="16" y1="13" x2="8" y2="13"></line>
                                  <line x1="16" y1="17" x2="8" y2="17"></line>
                                  <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                              </div>
                              <span>
                                  <p>Balance:</p><span class="text-danger">{{ $amount }}</span> USD
                                </span>
                            </div>
                          </a>
                        </div>
                        <div class="col-4 text-center">
                          <a href="{{route('user-record-amounts',['type'=>'2'])}}" target="_blank">
                            <div class="bookmark-content">
                              <div class="bookmark-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-user">
                                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                  <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                              </div>
                              <span>
                                <p>Consumed:</p><span class="text-danger">{{ $amount_consumed }}</span> USD
                              </span>
                            </div>
                          </a>
                        </div>
                        <div class="col-4 text-center">
                          <a href="{{route('user-record-amounts',['type'=>'3'])}}" target="_blank">
                            <div class="bookmark-content">
                              <div class="bookmark-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-server">
                                  <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                                  <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                                  <line x1="6" y1="6" x2="6.01" y2="6"></line>
                                  <line x1="6" y1="18" x2="6.01" y2="18"></line>
                                </svg>
                              </div>
                              <span>
                                <p>Recharged:</p><span class="text-danger">{{ $amount_recharged }}</span> USD
                              </span>
                            </div>
                          </a>
                        </div>
                      </div>
                    </li>
                    <li class="text-center mt-0">
                      <a class="flip-btn f-w-700 btn btn-primary w-100" id="flip-btn" target="_blank"
                         href="#">
                        Contact Us
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </li>

        @if(\Illuminate\Support\Facades\Auth::check())
          <li class="profile-nav onhover-dropdown pe-0 py-0">
            <div class="d-flex profile-media">
              <img class="b-r-10"
                   src="{{ \Illuminate\Support\Facades\Auth::user()->avatar}}" width="35px" alt="">
              <div class="flex-grow-1"><span>{{ \Illuminate\Support\Facades\Auth::user()->name }}</span>
                <p class="mb-0"> {{ \Illuminate\Support\Facades\Auth::user()->getLevel() }} <i
                          class="middle fa-solid fa-angle-down"></i></p>
              </div>
            </div>
            <ul class="profile-dropdown onhover-show-div">
              <li><a href="{{ route('logout') }}"><i
                          data-feather="log-in"> </i><span>{{trans('front.logout')}}</span></a></li>
            </ul>
          </li>
        @else
          <li class="profile-nav pe-0 py-0">
            <a href="{{ route('login',['redirectUrl' => request()->fullUrl()]) }}">
              <button class="btn btn-outline-primary-2x" type="button" data-bs-original-title=""
                      title="">{{trans('front.login')}}</button>
            </a>
          </li>
        @endif


      </ul>
    </div>
    <script class="result-template" type="text/x-handlebars-template">
      <div class="ProfileCard u-cf">
        <div class="ProfileCard-avatar">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
               stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
               class="feather feather-airplay m-0">
            <path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path>
            <polygon points="12 15 17 21 7 21 12 15"></polygon>
          </svg>
        </div>
        <div class="ProfileCard-details">
          <div class="ProfileCard-realName"></div>
        </div>
      </div>
    </script>
    <script class="empty-template" type="text/x-handlebars-template">
      <div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!
      </div></script>
  </div>
</div>

