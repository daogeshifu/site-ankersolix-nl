@extends('layouts.htmlstream.master')

@section('title', __('policy.page_title'))
@section('description', __('policy.page_title'). "-". __('policy.meta_description'))
@section('keywords', __('policy.meta_keywords'))

@section('opengraph')
<!-- Open Graph Meta Tags -->
<meta property="og:url" content="{{ URL::full() }}">
<meta property="og:type" content="website">
<meta property="og:title" content="{{ __('policy.page_title') }}">
<meta property="og:description" content="{{ __('policy.page_title') }} - {{ __('policy.meta_description') }}">
<meta property="og:image" content="https://www.aigcchecker.com/storage/og.jpg">
<meta property="og:image:width" content="1864">
<meta property="og:image:height" content="829">

<!-- Twitter Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta property="twitter:domain" content="aigcchecker.com">
<meta property="twitter:url" content="{{ URL::full() }}">
<meta name="twitter:title" content="{{ __('policy.page_title') }}">
<meta name="twitter:description" content="{{ __('policy.page_title') }} - {{ __('policy.meta_description') }}">
<meta name="twitter:image" content="https://www.aigcchecker.com/storage/og.jpg">
@endsection

@section('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "{{ __('policy.page_title') }}",
    "url": "{{ URL::full() }}",
    "logo": "https://www.aigcchecker.com/aigc/static/image/logo.png",
    "description": "{{ __('policy.meta_description') }}"
}
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "{{ __('policy.page_title') }}",
    "url": "{{ URL::full() }}",
    "inLanguage": "{{ app()->getLocale() }}"
}
</script>
@endsection

@section('content')

<main id="content" role="main">
  <!-- Content -->
  <div class="container content-space-3 content-space-lg-4">
    <!-- Card -->
    <div class="card card-lg">
      <!-- Header -->
      <div class="card-header bg-dark py-sm-10">
        <h1 class="card-title h2 text-white">{{ __('policy.privacy_policy_new') }}</h1>
        <p class="card-text text-white">{{ __('policy.last_modified') }} {{ date('F d, Y') }}</p>
      </div>
      <!-- End Header -->

      <!-- Card Body -->
      <div class="card-body">
        <div class="mb-7">
          <h4>{{ __('policy.section_new_1.title') }}</h4>

          <p>{{ __('policy.section_new_1.content.1') }}</p>

          <p>{{ __('policy.section_new_1.content.2') }}</p>

          <p>{{ __('policy.section_new_1.content.3') }}</p>
        </div>

        <div class="mb-7">
          <h4>{{ __('policy.section_new_2.title') }}</h4>

          <p>{{ __('policy.section_new_2.content.1.text') }}</p>

          <p><strong>{{ __('policy.section_new_2.content.2.label') }}</strong> {{ __('policy.section_new_2.content.2.text') }}</p>

          <p><strong>{{ __('policy.section_new_2.content.3.label') }}</strong> {{ __('policy.section_new_2.content.3.text') }}</p>

          <p><strong>{{ __('policy.section_new_2.content.4.label') }}</strong> {{ __('policy.section_new_2.content.4.text') }}</p>

          <p><strong>{{ __('policy.section_new_2.content.5.label') }}</strong> {{ __('policy.section_new_2.content.5.text') }}</p>
        </div>

        <div class="mb-7">
          <h4>{{ __('policy.section_new_3.title') }}</h4>

          <p>{{ __('policy.section_new_3.content.1.text') }}</p>

          <p><strong>{{ __('policy.section_new_3.content.2.label') }}</strong> {{ __('policy.section_new_3.content.2.text') }}</p>

          <p><strong>{{ __('policy.section_new_3.content.3.label') }}</strong> {{ __('policy.section_new_3.content.3.text') }}</p>

          <p><strong>{{ __('policy.section_new_3.content.4.label') }}</strong> {{ __('policy.section_new_3.content.4.text') }}</p>

          <p><strong>{{ __('policy.section_new_3.content.5.label') }}</strong> {{ __('policy.section_new_3.content.5.text') }}</p>

          <p>{{ __('policy.section_new_3.content.6.text') }}</p>
        </div>


        <div class="mb-7">
          <h4>{{ __('policy.section_new_4.title') }}</h4>

          <p>{{ __('policy.section_new_4.content.1.text') }}</p>

          <p><strong>{{ __('policy.section_new_4.content.2.label') }}</strong> {{ __('policy.section_new_4.content.2.text') }}</p>

          <p><strong>{{ __('policy.section_new_4.content.3.label') }}</strong> {{ __('policy.section_new_4.content.3.text') }}</p>

          <p><strong>{{ __('policy.section_new_4.content.4.label') }}</strong> {{ __('policy.section_new_4.content.4.text') }}</p>
        </div>

        <div class="mb-7">
          <h4>{{ __('policy.section_new_5.title') }}</h4>

          <p>{{ __('policy.section_new_5.content.1.text') }}</p>

          <p><strong>{{ __('policy.section_new_5.content.2.label') }}</strong> {{ __('policy.section_new_5.content.2.text') }}</p>

          <p><strong>{{ __('policy.section_new_5.content.3.label') }}</strong> {{ __('policy.section_new_5.content.3.text') }}</p>

          <p><strong>{{ __('policy.section_new_5.content.4.label') }}</strong> {{ __('policy.section_new_5.content.4.text') }}</p>
        </div>
        <div class="mb-7">
          <h4>{{ __('policy.section_new_6.title') }}</h4>

          <p>{{ __('policy.section_new_6.content.1.text') }}</p>

          <p><strong>{{ __('policy.section_new_6.content.2.label') }}</strong> {{ __('policy.section_new_6.content.2.text') }}</p>

          <p><strong>{{ __('policy.section_new_6.content.3.label') }}</strong> {{ __('policy.section_new_6.content.3.text') }}</p>

          <p><strong>{{ __('policy.section_new_6.content.4.label') }}</strong> {{ __('policy.section_new_6.content.4.text') }}</p>

          <p><strong>{{ __('policy.section_new_6.content.5.label') }}</strong> {{ __('policy.section_new_6.content.5.text') }}</p>
        </div>

        <div class="mb-7">
          <h4>{{ __('policy.section_new_7.title') }}</h4>

          <p>{{ __('policy.section_new_7.content.1.text') }}</p>
        </div>

        <div class="mb-7">
          <h4>{{ __('policy.section_new_8.title') }}</h4>

          <p>{{ __('policy.section_new_8.content.1.text') }}</p>

          <p>{{ __('policy.section_new_8.content.2.text') }}</p>
        </div>

        <div class="mb-7">
          <h4>{{ __('policy.section_new_9.title') }}</h4>

          <p>{{ __('policy.section_new_9.content.1.text_before_link') }}<a href="#">{{ __('policy.section_new_9.content.1.link_text') }}</a>{{ __('policy.section_new_9.content.1.text_after_link') }}</p>

          <p>{{ __('policy.section_new_9.content.2.text') }}</p>
        </div>
      </div>
      <!-- End Card Body -->
    </div>
    <!-- End Card -->
  </div>
  <!-- End Content -->
</main>

<div class="flex flex-col min-h-screen " style="display:none;">
  <section class="lg:mx-0 !pt-0 lg:!pt-[128px]">
    <div class="mx-6 pt-12 lg:pt-32">
      <div class="flex flex-col items-center mx-16 text-lg">
        <div class="w-full lg:w-2/3 m-5 lg:m-10 text-center">
          <div align="center" style="line-height: 1.5;">
            <div align="center" class="MsoNormal" data-custom-class="title" style="line-height: 1.5;">
              <strong>
                <span style="line-height: 150%; font-family: Arial; font-size: 26px;">
                  <bdt class="block-component"></bdt>
                  <bdt class="question">{{ __('policy.privacy_policy') }}</bdt>
                  <bdt class="statement-end-if-in-editor"></bdt>
                </span>
              </strong>
            </div>
            <div align="center" class="MsoNormal" data-custom-class="subtitle" style="line-height: 1.5;">
              <br>
            </div>
            <div align="center" class="MsoNormal" data-custom-class="subtitle" style="line-height: 1.5;">
              <span style="font-size:11.0pt;line-height:150%;font-family:Arial;color:#A6A6A6; mso-themecolor:background1;mso-themeshade:166;">
                <span style="color: rgb(127, 127, 127); font-size: 15px; text-align: justify;">
                  <strong>{{ __('terms.last_updated') }}</strong>
                </span>
                <strong>
                  <span style="color: rgb(127,127,127); font-size: 14.6667px; text-align: justify;">
                  </span>
                </strong>
                <span style="color: rgb(127, 127, 127); font-size: 15px; text-align: justify;">
                  <bdt class="block-container question question-in-editor" data-id="0d5ae8ae-7749-9afb-1fed-6556cb563dc0" data-type="question">
                    <strong>{{ __('terms.last_updated_at') }}</strong>
                  </bdt>
                </span>
              </span>
            </div>
          </div>
          <div align="center" style="text-align: left; line-height: 1;">
            <br>
          </div>
          <div align="center" style="text-align: left; line-height: 1;">
            <br>
          </div>
          <div align="center" style="text-align: left; line-height: 1;">
            <br>
          </div>
          <div style="text-align: left; line-height: 1.5;">
            <span style="color: rgb(127, 127, 127);">
              <span style="color: rgb(89, 89, 89); font-size: 15px;">
                <span data-custom-class="body_text">
                  {{ __('policy.introduction_1') }}
                </span>
              </span>
            </span>
            <span style="font-size: 15px;">
              <span style="color: rgb(127, 127, 127);">
                <span data-custom-class="body_text">
                  <span style="color: rgb(89, 89, 89);">
                    <span data-custom-class="body_text">
                      <bdt class="block-component"></bdt>
                    </span>
                  </span>
                </span>
              </span>
            </span>
          </div>
          <ul>
            <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px; color: rgb(89, 89, 89);">
                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                  <span data-custom-class="body_text">
                    {{ __('policy.introduction_2') }}
                  </span>
                </span>
              </span>
            </li>
          </ul>
          <div>
            <ul>
              <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                    <span data-custom-class="body_text">
                      {{ __('policy.introduction_3') }}
                    </span>
                  </span>
                </span>
              </li>
            </ul>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                <span style="color: rgb(127, 127, 127);">
                  <span data-custom-class="body_text">
                    {{ __('policy.introduction_4') }}
                  </span>
                </span>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <strong>
                <span style="font-size: 15px;">
                  <span data-custom-class="heading_1">
                    {{ __('policy.summary_title') }}
                  </span>
                </span>
              </strong>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                {{ __('policy.summary_1') }}
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                <span data-custom-class="body_text">
                  <strong>{{ __('policy.summary_2.question') }}</strong>{{ __('policy.summary_2.answer') }}
                </span>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                <span data-custom-class="body_text">
                  <strong>{{ __('policy.summary_3.question') }}</strong>{{ __('policy.summary_3.answer') }}
                </span>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                <span data-custom-class="body_text">
                  <strong>{{ __('policy.summary_4.question') }}</strong>{{ __('policy.summary_4.answer') }}
                </span>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                <span data-custom-class="body_text">
                  <strong>{{ __('policy.summary_5.question') }}</strong>{{ __('policy.summary_5.answer') }}
                </span>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                <span data-custom-class="body_text">
                  <strong>{{ __('policy.summary_6.question') }}</strong>{{ __('policy.summary_6.answer') }}
              <span style="font-size: 15px;">
                <span data-custom-class="body_text">
                  .
                  <bdt class="block-component"></bdt>
                </span>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                <span data-custom-class="body_text">
                  <strong>{{ __('policy.summary_7.question') }}</strong>{{ __('policy.summary_7.answer') }}
                </span>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                <span data-custom-class="body_text">
                  <strong>{{ __('policy.summary_8.question') }}</strong>{{ __('policy.summary_8.answer') }}
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                <span data-custom-class="body_text">
                  <strong>{{ __('policy.summary_9.question') }}</strong>{{ __('policy.summary_9.answer') }}
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                <span data-custom-class="body_text">{{ __('policy.summary_10.question') }}</span>
              </span>
              <a data-custom-class="link" href="#toc">
                <span style="color: rgb(0, 58, 250); font-size: 15px;">
                  <span data-custom-class="body_text">{{ __('policy.summary_10.answer') }}</span>
                </span>
              </a>
              <span style="font-size: 15px;">
                <span data-custom-class="body_text">.</span>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div id="toc" style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                <span style="color: rgb(127, 127, 127);">
                  <span style="color: rgb(0, 0, 0);">
                    <strong>
                      <span data-custom-class="heading_1">{{ __('policy.table_of_contents.title') }}</span>
                    </strong>
                  </span>
                </span>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                <a data-custom-class="link" href="#infocollect">
                  <span style="color: rgb(0, 58, 250);">{{ __('policy.section_1.title') }}</span>
                </a>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                <a data-custom-class="link" href="#infouse">
                  <span style="color: rgb(0, 58, 250);">
                    {{ __('policy.section_2.title') }}
                  </span>
                </a>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                <a data-custom-class="link" href="#legalbases">
                  <span style="color: rgb(0, 58, 250);">
                    <span style="font-size: 15px;">
                      <span style="color: rgb(0, 58, 250);">{{ __('policy.section_3.title') }}</span>
                    </span>
                  </span>
                </a>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                <span style="color: rgb(0, 58, 250);">
                  <a data-custom-class="link" href="#whoshare">{{ __('policy.section_4.title') }}</a>
                </span>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                <a data-custom-class="link" href="#cookies">
                  <span style="color: rgb(0, 58, 250);">
                    {{ __('policy.section_5.title') }}
                  </span>
                </a>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                <a data-custom-class="link" href="#sociallogins">
                  <span style="color: rgb(0, 58, 250);">
                    <span style="color: rgb(0, 58, 250);">
                      <span style="color: rgb(0, 58, 250);">{{ __('policy.section_6.title') }}</span>
                    </span>
                  </span>
                </a>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                <a data-custom-class="link" href="#inforetain">
                  <span style="color: rgb(0, 58, 250);">{{ __('policy.section_7.title') }}</span>
                </a>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                <a data-custom-class="link" href="#infosafe">
                  <span style="color: rgb(0, 58, 250);">{{ __('policy.section_8.title') }}</span>
                </a>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                <a data-custom-class="link" href="#infominors">
                  <span style="color: rgb(0, 58, 250);">{{ __('policy.section_9.title') }}</span>
                </a>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                <span style="color: rgb(0, 58, 250);">
                  <a data-custom-class="link" href="#privacyrights">{{ __('policy.section_10.title') }}</a>
                </span>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                <a data-custom-class="link" href="#DNT">
                  <span style="color: rgb(0, 58, 250);">{{ __('policy.section_11.title') }}<bdt class="block-component"></bdt>
                  </span>
                </a>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                <a data-custom-class="link" href="#uslaws">
                  <span style="color: rgb(0, 58, 250);">{{ __('policy.section_12.title') }}</span>
                </a>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="color: rgb(0, 58, 250); font-size: 15px;">
                <a data-custom-class="link" href="#clausea">{{ __('policy.section_13.title') }}</a>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                <a data-custom-class="link" href="#policyupdates">
                  <span style="color: rgb(0, 58, 250);">{{ __('policy.section_14.title') }}</span>
                </a>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <a data-custom-class="link" href="#contact">
                <span style="color: rgb(0, 58, 250); font-size: 15px;">{{ __('policy.section_15.title') }}</span>
              </a>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <a data-custom-class="link" href="#request">
                <span style="color: rgb(0, 58, 250); font-size: 15px;">{{ __('policy.section_16.title') }}</span>
              </a>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div id="infocollect" style="text-align: left; line-height: 1.5;">
              <span style="color: rgb(0, 0, 0);">
                <span style="color: rgb(0, 0, 0); font-size: 15px;">
                  <span style="font-size: 15px; color: rgb(0, 0, 0);">
                    <span style="font-size: 15px; color: rgb(0, 0, 0);">
                      <span id="control" style="color: rgb(0, 0, 0);">
                        <strong>
                          <span data-custom-class="heading_1">{{ __('policy.section_1.title') }}</span>
                        </strong>
                      </span>
                    </span>
                  </span>
                </span>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div id="personalinfo" style="text-align: left; line-height: 1.5;">
              <span data-custom-class="heading_2" style="color: rgb(0, 0, 0);">
                <span style="font-size: 15px;">
                  <strong>{{ __('policy.section_1.content.1') }}</strong>
                </span>
              </span>
            </div>
            <div>
              <div>
                <br>
              </div>
              <div style="text-align: left; line-height: 1.5;">
                <span style="color: rgb(127, 127, 127);">
                  <span style="color: rgb(89, 89, 89); font-size: 15px;">
                    <span data-custom-class="body_text">
                      <span style="font-size: 15px; color: rgb(89, 89, 89);">
                        <span style="font-size: 15px; color: rgb(89, 89, 89);">
                          <span data-custom-class="body_text">
                            <em>{{ __('policy.section_1.content.2') }}</em>
                          </span>
                        </span>
                      </span>
                    </span>
                  </span>
                </span>
              </div>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px; color: rgb(89, 89, 89);">
                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                  <span data-custom-class="body_text">{{ __('policy.section_1.content.3') }}</span>
                  </span>
                </span>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px; color: rgb(89, 89, 89);">
                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                  <span data-custom-class="body_text">
                    {{ __('policy.section_1.content.4') }}
                  </span>
                </span>
              </span>
            </div>
            @for ($i = 1; $i <= 12; $i++)
              <ul>
                <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                    <span style="font-size: 15px; color: rgb(89, 89, 89);">
                      <span data-custom-class="body_text">
                        <span style="font-size: 15px;">
                          <span data-custom-class="body_text">
                            <bdt class="question">{{ __('policy.section_1.content.follow.'. $i) }}</bdt>
                          </span>
                        </span>
                      </span>
                    </span>
                  </span>
                </li>
              </ul>
            @endfor
            <div id="sensitiveinfo" style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px;">
                <span data-custom-class="body_text">
                  {{ __('policy.section_1.content.5') }}
                </span>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px; color: rgb(89, 89, 89);">
                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                  <span data-custom-class="body_text">
                    {{ __('policy.section_1.content.6') }}
                  </span>
                </span>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px; color: rgb(89, 89, 89);">
                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                  <span data-custom-class="body_text">
                    {{ __('policy.section_1.content.7') }}
                  </span>
                </span>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px; color: rgb(89, 89, 89);">
                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                  <span data-custom-class="body_text">{{ __('policy.section_1.content.8') }}</span>
                </span>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span data-custom-class="heading_2" style="color: rgb(0, 0, 0);">
                <span style="font-size: 15px;">
                  <strong>{{ __('policy.section_1.content.9') }}</strong>
                </span>
              </span>
            </div>
            <div>
              <div>
                <br>
              </div>
              <div style="text-align: left; line-height: 1.5;">
                <span style="color: rgb(127, 127, 127);">
                  <span style="color: rgb(89, 89, 89); font-size: 15px;">
                    <span data-custom-class="body_text">
                      <span style="font-size: 15px; color: rgb(89, 89, 89);">
                        <span style="font-size: 15px; color: rgb(89, 89, 89);">
                          <span data-custom-class="body_text">
                            <em>{{ __('policy.section_1.content.10') }}</em>
                          </span>
                        </span>
                      </span>
                    </span>
                  </span>
                </span>
              </div>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px; color: rgb(89, 89, 89);">
                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                  <span data-custom-class="body_text">{{ __('policy.section_1.content.11') }}</span>
                </span>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px; color: rgb(89, 89, 89);">
                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                  <span data-custom-class="body_text">
                    {{ __('policy.section_1.content.12') }}
                  </span>
                </span>
              </span>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px; color: rgb(89, 89, 89);">
                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                  <span data-custom-class="body_text">
                    {{ __('policy.section_1.content.13') }}
                  </span>
                </span>
              </span>
            </div>
            <ul>
              <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                    <span data-custom-class="body_text">
                      {{ __('policy.section_1.content.14') }}
                    </span>
                  </span>
                </span>
              </li>
            </ul>
            <ul>
              <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                    <span data-custom-class="body_text">
                      {{ __('policy.section_1.content.15') }}
                    </span>
                  </span>
                </span>
              </li>
            </ul>
            <ul>
              <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                    <span data-custom-class="body_text">
                      {{ __('policy.section_1.content.16') }}
                    </span>
                  </span>
                </span>
              </li>
            </ul>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div id="infouse" style="text-align: left; line-height: 1.5;">
              <span style="color: rgb(127, 127, 127);">
                <span style="color: rgb(89, 89, 89); font-size: 15px;">
                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                    <span style="font-size: 15px; color: rgb(89, 89, 89);">
                      <span id="control" style="color: rgb(0, 0, 0);">
                        <strong>
                          <span data-custom-class="heading_1">{{ __('policy.section_2.title') }}</span>
                        </strong>
                      </span>
                    </span>
                  </span>
                </span>
              </span>
            </div>
            <div>
              <div style="text-align: left; line-height: 1.5;">
                <br>
              </div>
              <div style="text-align: left; line-height: 1.5;">
                <span style="color: rgb(127, 127, 127);">
                  <span style="color: rgb(89, 89, 89); font-size: 15px;">
                    <span data-custom-class="body_text">
                      <span style="font-size: 15px; color: rgb(89, 89, 89);">
                        <span style="font-size: 15px; color: rgb(89, 89, 89);">
                          <span data-custom-class="body_text">
                            <em>{{ __('policy.section_2.content.1') }}</em>
                          </span>
                        </span>
                      </span>
                    </span>
                  </span>
                </span>
              </div>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <br>
            </div>
            <div style="text-align: left; line-height: 1.5;">
              <span style="font-size: 15px; color: rgb(89, 89, 89);">
                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                  <span data-custom-class="body_text">
                    <strong>{{ __('policy.section_2.content.2') }}</strong>
                  </span>
                </span>
              </span>
            </div>
            <ul>
              <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                    <span data-custom-class="body_text">
                      {{ __('policy.section_2.content.1') }}
                    </span>
                  </span>
                </span>
              </li>
            </ul>
            <div style="text-align: left; line-height: 1.5;">
              <div style="text-align: left; line-height: 1.5;">
                <div style="text-align: left; line-height: 1.5;">
                  <div style="text-align: left; line-height: 1.5;">
                    <div style="text-align: left; line-height: 1.5;">
                      <div style="text-align: left; line-height: 1.5;">
                        <div style="text-align: left; line-height: 1.5;">
                          <div style="text-align: left; line-height: 1.5;">
                            <ul>
                              <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                                <span style="font-size: 15px;">
                                  <span style="color: rgb(89, 89, 89);">
                                    <span style="color: rgb(89, 89, 89);">
                                      <span data-custom-class="body_text">
                                        {{ __('policy.section_2.content.4') }}
                                      </span>
                                    </span>
                                  </span>
                                </span>
                              </li>
                            </ul>
                            <div style="text-align: left; line-height: 1.5;">
                              <ul>
                                <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                    <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                      <span data-custom-class="body_text">
                                        {{ __('policy.section_2.content.5') }}
                                      </span>
                                    </span>
                                  </span>
                                </li>
                              </ul>
                              <div style="text-align: left; line-height: 1.5;">
                                <div style="text-align: left; line-height: 1.5;">
                                  <div style="text-align: left; line-height: 1.5;">
                                    <div style="text-align: left; line-height: 1.5;">
                                      <ul>
                                        <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                                          <span data-custom-class="body_text">
                                            <span style="font-size: 15px;">
                                              {{ __('policy.section_2.content.6') }}</span>
                                          </span>
                                        </li>
                                      </ul>
                                      <div style="text-align: left; line-height: 1.5;">
                                        <div style="text-align: left; line-height: 1.5;">
                                          <div style="text-align: left; line-height: 1.5;">
                                            <ul>
                                              <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                                                <span data-custom-class="body_text">
                                                  <span style="font-size: 15px;">
                                                    {{ __('policy.section_2.content.7') }}</span>
                                                </span>
                                              </li>
                                            </ul>
                                            <div style="text-align: left; line-height: 1.5;">
                                              <ul>
                                                <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                                                  <span style="font-size: 15px;">
                                                    <span data-custom-class="body_text">
                                                      {{ __('policy.section_2.content.8') }}</span>
                                                  </span>
                                                </li>
                                              </ul>
                                              <div style="text-align: left; line-height: 1.5;">
                                                <ul>
                                                  <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                                                    <span data-custom-class="body_text">
                                                      <span style="font-size: 15px;">
                                                        {{ __('policy.section_2.content.9') }}</span>
                                                    </span>
                                                  </li>
                                                </ul>
                                                <div style="text-align: left; line-height: 1.5;">
                                                  <br>
                                                </div>
                                                <div id="legalbases" style="text-align: left; line-height: 1.5;">
                                                  <strong>
                                                    <span style="font-size: 15px;">
                                                      <span data-custom-class="heading_1">{{ __('policy.section_3.title') }}</span>
                                                    </span>
                                                  </strong>
                                                </div>
                                                <div style="text-align: left; line-height: 1.5;">
                                                  <br>
                                                </div>
                                                <div style="text-align: left; line-height: 1.5;">
                                                  <em>
                                                    <span style="font-size: 15px;">
                                                      <span data-custom-class="body_text">
                                                        {{ __('policy.section_3.content.1') }}
                                                      </span>
                                                    </span>
                                                  </em>
                                                </div>
                                                <div style="text-align: left; line-height: 1.5;">
                                                  <br>
                                                </div>
                                                <div style="text-align: left; line-height: 1.5;">
                                                  <em>
                                                    <span style="font-size: 15px;">
                                                      <span data-custom-class="body_text">
                                                        <strong>
                                                          <u>{{ __('policy.section_3.content.2') }}</u>
                                                        </strong>
                                                      </span>
                                                    </span>
                                                  </em>
                                                </div>
                                                <div style="text-align: left; line-height: 1.5;">
                                                  <br>
                                                </div>
                                                <div style="text-align: left; line-height: 1.5;">
                                                  <span style="font-size: 15px;">
                                                    <span data-custom-class="body_text">
                                                      {{ __('policy.section_3.content.3') }}
                                                    </span>
                                                  </span>
                                                </div>
                                                <ul>
                                                  <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                                                    <span style="font-size: 15px;">
                                                      {{ __('policy.section_3.content.4') }}
                                                    </span>
                                                  </li>
                                                </ul>
                                                <div style="text-align: left; line-height: 1.5;">
                                                  <ul>
                                                    <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                                                      <span data-custom-class="body_text">
                                                        <span style="font-size: 15px;">
                                                          {{ __('policy.section_3.content.5') }}
                                                        </span>
                                                      </span>
                                                    </li>
                                                  </ul>
                                                  <ul style="margin-left: 40px;">
                                                    <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                                                      <span data-custom-class="body_text">
                                                        <span style="font-size: 15px;">
                                                          {{ __('policy.section_3.content.6') }}
                                                        </span>
                                                      </span>
                                                    </li>
                                                  </ul>
                                                  <div style="text-align: left; line-height: 1.5;">
                                                    <ul style="margin-left: 40px;">
                                                      <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                                                        <span data-custom-class="body_text">
                                                          <span style="font-size: 15px;">
                                                            {{ __('policy.section_3.content.7') }}
                                                          </span>
                                                        </span>
                                                      </li>
                                                    </ul>
                                                    <ul style="margin-left: 40px;">
                                                      <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                                                        <span data-custom-class="body_text">
                                                          <span style="font-size: 15px;">
                                                            {{ __('policy.section_3.content.8') }}
                                                          </span>
                                                        </span>
                                                      </li>
                                                    </ul>
                                                    <ul style="margin-left: 40px;">
                                                      <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                                                        <span data-custom-class="body_text">
                                                          <span style="font-size: 15px;">
                                                            {{ __('policy.section_3.content.9') }}
                                                          </span>
                                                        </span>
                                                      </li>
                                                    </ul>
                                                    <ul style="margin-left: 40px;">
                                                      <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                                                        <span data-custom-class="body_text">
                                                          <span style="font-size: 15px;">
                                                            {{ __('policy.section_3.content.10') }}
                                                          </span>
                                                        </span>
                                                      </li>
                                                    </ul>
                                                    <ul>
                                                      <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                                                        <span data-custom-class="body_text">
                                                          <span style="font-size: 15px;">
                                                            {{ __('policy.section_3.content.11') }}
                                                            <br>
                                                          </span>
                                                        </span>
                                                      </li>
                                                    </ul>
                                                    <ul>
                                                      <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                                                        <span data-custom-class="body_text">
                                                          <span style="font-size: 15px;">
                                                            {{ __('policy.section_3.content.12') }}
                                                          </span>
                                                        </span>
                                                      </li>
                                                    </ul>
                                                    <div style="text-align: left; line-height: 1.5;">
                                                      <br>
                                                    </div>
                                                    <div id="whoshare" style="text-align: left; line-height: 1.5;">
                                                      <span style="color: rgb(127, 127, 127);">
                                                        <span style="color: rgb(89, 89, 89); font-size: 15px;">
                                                          <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                            <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                              <span id="control" style="color: rgb(0, 0, 0);">
                                                                <strong>
                                                                  <span data-custom-class="heading_1">
                                                                    {{ __('policy.section_4.title') }}
                                                                  </span>
                                                                </strong>
                                                              </span>
                                                            </span>
                                                          </span>
                                                        </span>
                                                      </span>
                                                    </div>
                                                    <div style="text-align: left; line-height: 1.5;">
                                                      <br>
                                                    </div>
                                                    <div style="text-align: left; line-height: 1.5;">
                                                      <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                        <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                          <span data-custom-class="body_text">
                                                            <em>
                                                              {{ __('policy.section_4.content.1') }}
                                                            </em>
                                                          </span>
                                                        </span>
                                                      </span>
                                                    </div>
                                                    <div style="text-align: left; line-height: 1.5;">
                                                      <br>
                                                    </div>
                                                    <div style="text-align: left; line-height: 1.5;">
                                                      <span style="font-size: 15px;">
                                                        <span data-custom-class="body_text">
                                                          {{ __('policy.section_4.content.2') }}
                                                        </span>
                                                      </span>
                                                    </div>
                                                    <ul>
                                                      <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                                                        <span style="font-size: 15px;">
                                                          <span data-custom-class="body_text">
                                                            {{ __('policy.section_4.content.3') }}
                                                          </span>
                                                        </span>
                                                      </li>
                                                    </ul>
                                                    <div style="text-align: left; line-height: 1.5;">
                                                      <div style="text-align: left; line-height: 1.5;">
                                                        <div style="text-align: left; line-height: 1.5;">
                                                          <div style="text-align: left; line-height: 1.5;">
                                                            <div style="text-align: left; line-height: 1.5;">
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div id="cookies" style="text-align: left; line-height: 1.5;">
                                                                <span style="color: rgb(127, 127, 127);">
                                                                  <span style="color: rgb(89, 89, 89); font-size: 15px;">
                                                                    <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                      <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                        <span id="control" style="color: rgb(0, 0, 0);">
                                                                          <strong>
                                                                            <span data-custom-class="heading_1">{{ __('policy.section_5.title') }}</span>
                                                                          </strong>
                                                                        </span>
                                                                      </span>
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                    <span data-custom-class="body_text">
                                                                      <em>{{ __('policy.section_5.content.1') }}</em>
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                    <span data-custom-class="body_text">
                                                                      {{ __('policy.section_5.content.2') }}
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                    <span data-custom-class="body_text">
                                                                      {{ __('policy.section_5.content.3') }}
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px;">
                                                                  <br>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px;">
                                                                  <span data-custom-class="body_text">
                                                                    {{ __('policy.section_5.content.4') }}
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                    <span data-custom-class="body_text">
                                                                      {{ __('policy.section_5.content.5') }}
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div id="sociallogins" style="text-align: left; line-height: 1.5;">
                                                                <span style="color: rgb(127, 127, 127);">
                                                                  <span style="color: rgb(89, 89, 89); font-size: 15px;">
                                                                    <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                      <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                        <span id="control" style="color: rgb(0, 0, 0);">
                                                                          <strong>
                                                                            <span data-custom-class="heading_1">
                                                                              {{ __('policy.section_6.title') }}
                                                                            </span>
                                                                          </strong>
                                                                        </span>
                                                                      </span>
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                    <span data-custom-class="body_text">
                                                                      <em>{{ __('policy.section_6.content.1') }}</em>
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                    <span data-custom-class="body_text">
                                                                      {{ __('policy.section_6.content.2') }}
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                    <span data-custom-class="body_text">
                                                                      {{ __('policy.section_6.content.3') }}
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div id="inforetain" style="text-align: left; line-height: 1.5;">
                                                                <span style="color: rgb(127, 127, 127);">
                                                                  <span style="color: rgb(89, 89, 89); font-size: 15px;">
                                                                    <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                      <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                        <span id="control" style="color: rgb(0, 0, 0);">
                                                                          <strong>
                                                                            <span data-custom-class="heading_1">{{ __('policy.section_7.title') }}</span>
                                                                          </strong>
                                                                        </span>
                                                                      </span>
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                    <span data-custom-class="body_text">
                                                                      <em>
                                                                        {{ __('policy.section_7.content.1') }}
                                                                      </em>
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                    <span data-custom-class="body_text">
                                                                      {{ __('policy.section_7.content.2') }}
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                    <span data-custom-class="body_text">
                                                                      {{ __('policy.section_7.content.3') }}
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div id="infosafe" style="text-align: left; line-height: 1.5;">
                                                                <span style="color: rgb(127, 127, 127);">
                                                                  <span style="color: rgb(89, 89, 89); font-size: 15px;">
                                                                    <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                      <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                        <span id="control" style="color: rgb(0, 0, 0);">
                                                                          <strong>
                                                                            <span data-custom-class="heading_1">{{ __('policy.section_8.title') }}</span>
                                                                          </strong>
                                                                        </span>
                                                                      </span>
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                    <span data-custom-class="body_text">
                                                                      <em>
                                                                        {{ __('policy.section_8.content.1') }}
                                                                      </em>
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                    <span data-custom-class="body_text">
                                                                      {{ __('policy.section_8.content.2') }}
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div id="infominors" style="text-align: left; line-height: 1.5;">
                                                                <span style="color: rgb(127, 127, 127);">
                                                                  <span style="color: rgb(89, 89, 89); font-size: 15px;">
                                                                    <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                      <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                        <span id="control" style="color: rgb(0, 0, 0);">
                                                                          <strong>
                                                                            <span data-custom-class="heading_1">{{ __('policy.section_9.title') }}</span>
                                                                          </strong>
                                                                        </span>
                                                                      </span>
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                    <span data-custom-class="body_text">
                                                                      <em>
                                                                        {{ __('policy.section_9.content.1') }}
                                                                      </em>
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                    <span data-custom-class="body_text">
                                                                      {{ __('policy.section_9.content.2') }}
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div id="privacyrights" style="text-align: left; line-height: 1.5;">
                                                                <span style="color: rgb(127, 127, 127);">
                                                                  <span style="color: rgb(89, 89, 89); font-size: 15px;">
                                                                    <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                      <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                        <span id="control" style="color: rgb(0, 0, 0);">
                                                                          <strong>
                                                                            <span data-custom-class="heading_1">{{ __('policy.section_10.title') }}</span>
                                                                          </strong>
                                                                        </span>
                                                                      </span>
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                    <span data-custom-class="body_text">
                                                                      <em>
                                                                        {{ __('policy.section_10.content.1') }}
                                                                      </em>
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                    <span data-custom-class="body_text">
                                                                      {{ __('policy.section_10.content.2') }}
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                    <span data-custom-class="body_text">
                                                                      {{ __('policy.section_10.content.3') }}
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                    <span data-custom-class="body_text">
                                                                      {{ __('policy.section_10.content.4') }}
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div id="withdrawconsent" style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                    <span data-custom-class="body_text">
                                                                      {{ __('policy.section_10.content.5') }}
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px;">
                                                                  <span data-custom-class="body_text">
                                                                    {{ __('policy.section_10.content.6') }}
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px;">
                                                                  <span data-custom-class="body_text">
                                                                    {{ __('policy.section_10.content.7') }}
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px;">
                                                                  <span data-custom-class="heading_2">
                                                                    <strong>{{ __('policy.section_10.content.8') }}</strong>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span data-custom-class="body_text">
                                                                  <span style="font-size: 15px;">
                                                                    {{ __('policy.section_10.content.9') }}
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <ul>
                                                                <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                                                                  <span data-custom-class="body_text">
                                                                    <span style="font-size: 15px;">
                                                                      {{ __('policy.section_10.content.10') }}
                                                                    </span>
                                                                  </span>
                                                                </li>
                                                              </ul>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px;">
                                                                  <span data-custom-class="body_text">
                                                                    {{ __('policy.section_10.content.11') }}
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                    <span data-custom-class="body_text">
                                                                      {{ __('policy.section_10.content.12') }}
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span data-custom-class="body_text">
                                                                  <span style="font-size: 15px;">
                                                                    {{ __('policy.section_10.content.13') }}
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div id="DNT" style="text-align: left; line-height: 1.5;">
                                                                <span style="color: rgb(127, 127, 127);">
                                                                  <span style="color: rgb(89, 89, 89); font-size: 15px;">
                                                                    <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                      <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                        <span id="control" style="color: rgb(0, 0, 0);">
                                                                          <strong>
                                                                            <span data-custom-class="heading_1">{{ __('policy.section_11.title') }}</span>
                                                                          </strong>
                                                                        </span>
                                                                      </span>
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                    <span data-custom-class="body_text">
                                                                      {{ __('policy.section_11.content.1') }}
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                                <bdt class="block-component">
                                                                  <span style="font-size: 15px;"></span>
                                                                </bdt>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px;">
                                                                  <br>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px;">
                                                                  <span data-custom-class="body_text">
                                                                    {{ __('policy.section_11.content.2') }}
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div id="uslaws" style="text-align: left; line-height: 1.5;">
                                                                <span style="color: rgb(127, 127, 127);">
                                                                  <span style="color: rgb(89, 89, 89); font-size: 15px;">
                                                                    <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                      <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                        <span id="control" style="color: rgb(0, 0, 0);">
                                                                          <strong>
                                                                            <span data-custom-class="heading_1">{{ __('policy.section_12.title') }}</span>
                                                                          </strong>
                                                                        </span>
                                                                      </span>
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                    <span data-custom-class="body_text">
                                                                      <em>
                                                                        {{ __('policy.section_12.content.1') }}
                                                                      </em>
                                                                    </span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                    <strong>
                                                                      <span data-custom-class="heading_2">{{ __('policy.section_12.content.2') }}</span>
                                                                    </strong>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                  <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                    <span data-custom-class="body_text">{{ __('policy.section_12.content.3') }}</span>
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <br>
                                                              </div>
                                                              <table style="width: 100%;">
                                                                <tbody>
                                                                  <tr>
                                                                    <td style="width: 33.8274%; border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black;">
                                                                      <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                        <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                          <span data-custom-class="body_text">
                                                                            <strong>{{ __('policy.section_12.content.table.1-1') }}</strong>
                                                                          </span>
                                                                        </span>
                                                                      </span>
                                                                    </td>
                                                                    <td style="width: 51.4385%; border-top: 1px solid black; border-right: 1px solid black;">
                                                                      <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                        <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                          <span data-custom-class="body_text">
                                                                            <strong>{{ __('policy.section_12.content.table.1-2') }}</strong>
                                                                          </span>
                                                                        </span>
                                                                      </span>
                                                                    </td>
                                                                    <td style="width: 14.9084%; border-right: 1px solid black; border-top: 1px solid black; text-align: center;">
                                                                      <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                        <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                          <span data-custom-class="body_text">
                                                                            <strong>{{ __('policy.section_12.content.table.1-3') }}</strong>
                                                                          </span>
                                                                        </span>
                                                                      </span>
                                                                    </td>
                                                                  </tr>
                                                                  @for ( $i = 2; $i <= 13; $i++ )
                                                                  <tr>
                                                                    <td style="width: 33.8274%; border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black;">
                                                                      <div style="text-align: left; line-height: 1.5;">
                                                                        <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                          <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                            <span data-custom-class="body_text">{{ __('policy.section_12.content.table.'. $i. '-1') }}</span>
                                                                          </span>
                                                                        </span>
                                                                      </div>
                                                                    </td>
                                                                    <td style="width: 51.4385%; border-top: 1px solid black; border-right: 1px solid black;">
                                                                      <div style="text-align: left; line-height: 1.5;">
                                                                        <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                          <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                            <span data-custom-class="body_text">{{ __('policy.section_12.content.table.'. $i. '-2') }}</span>
                                                                          </span>
                                                                        </span>
                                                                      </div>
                                                                    </td>
                                                                    <td style="width: 14.9084%; text-align: center; vertical-align: middle; border-right: 1px solid black; border-top: 1px solid black;">
                                                                      <div style="text-align: left; line-height: 1.5;">
                                                                        <br>
                                                                      </div>
                                                                      <div style="text-align: left; line-height: 1.5;">
                                                                        <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                          <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                            <span data-custom-class="body_text">
                                                                              <bdt class="block-component">
                                                                                <bdt class="block-component"></bdt>
                                                                              </bdt>
                                                                              {{ __('policy.section_12.content.table.'. $i. '-3') }}
                                                                              <bdt class="else-block">
                                                                                <bdt class="block-component"></bdt>
                                                                              </bdt>
                                                                            </span>
                                                                          </span>
                                                                        </span>
                                                                      </div>
                                                                      <div style="text-align: left; line-height: 1.5;">
                                                                        <br>
                                                                      </div>
                                                                    </td>
                                                                  </tr>
                                                                  @endfor
                                                                </tbody>
                                                              </table>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span style="font-size: 15px;">
                                                                  <span data-custom-class="body_text">
                                                                    {{ __('policy.section_12.content.4') }}
                                                                  </span>
                                                                </span>
                                                              </div>
                                                              <ul>
                                                                <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                                                                  <span style="font-size: 15px;">
                                                                    {{ __('policy.section_12.content.5') }}
                                                                  </span>
                                                                </li>
                                                              </ul>
                                                              <ul>
                                                                <li data-custom-class="body_text">
                                                                  <span style="font-size: 15px;">
                                                                    {{ __('policy.section_12.content.6') }}
                                                                  </span>
                                                                </li>
                                                              </ul>
                                                              <ul>
                                                                <li data-custom-class="body_text">
                                                                  <span style="font-size: 15px;">
                                                                    {{ __('policy.section_12.content.7') }}
                                                                  </span>
                                                                  </bdt>
                                                                </li>
                                                              </ul>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <span data-custom-class="body_text">
                                                                  {{ __('policy.section_12.content.8') }}
                                                                </span>
                                                              </div>
                                                              <ul>
                                                                <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                                                                  <span data-custom-class="body_text">
                                                                    {{ __('policy.section_12.content.9') }}
                                                                  </span>
                                                                </li>
                                                              </ul>
                                                              <ul>
                                                                <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                                                                  <span data-custom-class="body_text">
                                                                    {{ __('policy.section_12.content.10') }}
                                                                  </span>
                                                                </li>
                                                              </ul>
                                                              <div style="text-align: left; line-height: 1.5;">
                                                                <div style="text-align: left; line-height: 1.5;">
                                                                  <div style="text-align: left; line-height: 1.5;">
                                                                    <div style="text-align: left; line-height: 1.5;">
                                                                      <div style="text-align: left; line-height: 1.5;">
                                                                        <div style="text-align: left; line-height: 1.5;">
                                                                          <ul>
                                                                            <li data-custom-class="body_text" style="text-align: left; line-height: 1.5;">
                                                                              <span data-custom-class="body_text">
                                                                                {{ __('policy.section_12.content.11') }}
                                                                              </span>
                                                                            </li>
                                                                          </ul>
                                                                          <div style="text-align: left; line-height: 1.5;">
                                                                            <div style="text-align: left; line-height: 1.5;">
                                                                              <div style="text-align: left; line-height: 1.5;">
                                                                                <div style="text-align: left; line-height: 1.5;">
                                                                                  <div id="clausea" style="text-align: left; line-height: 1.5;">
                                                                                    <span style="font-size: 15px;">
                                                                                      <strong>
                                                                                        <span data-custom-class="heading_1">{{ __('policy.section_13.title') }}</span>
                                                                                      </strong>
                                                                                    </span>
                                                                                  </div>
                                                                                  <div style="text-align: left; line-height: 1.5;">
                                                                                    <span style="font-size: 15px;">
                                                                                      <br>
                                                                                    </span>
                                                                                  </div>
                                                                                  <div style="text-align: left; line-height: 1.5;">
                                                                                    <span style="font-size: 15px;">
                                                                                      <bdt class="question">
                                                                                        <span data-custom-class="body_text">{{ __('policy.section_13.content.1') }}</span>
                                                                                      </bdt>
                                                                                    </span>
                                                                                  </div>
                                                                                  <div style="text-align: left; line-height: 1.5;">
                                                                                    <br>
                                                                                  </div>
                                                                                  <div id="policyupdates" style="text-align: left; line-height: 1.5;">
                                                                                    <span style="color: rgb(127, 127, 127);">
                                                                                      <span style="color: rgb(89, 89, 89); font-size: 15px;">
                                                                                        <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                                          <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                                            <span id="control" style="color: rgb(0, 0, 0);">
                                                                                              <strong>
                                                                                                <span data-custom-class="heading_1">{{ __('policy.section_14.title') }}</span>
                                                                                              </strong>
                                                                                            </span>
                                                                                          </span>
                                                                                        </span>
                                                                                      </span>
                                                                                    </span>
                                                                                  </div>
                                                                                  <div style="text-align: left; line-height: 1.5;">
                                                                                    <br>
                                                                                  </div>
                                                                                  <div style="text-align: left; line-height: 1.5;">
                                                                                    <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                                      <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                                        <span data-custom-class="body_text">
                                                                                          <em>
                                                                                            {{ __('policy.section_14.content.1') }}
                                                                                          </em>
                                                                                        </span>
                                                                                      </span>
                                                                                    </span>
                                                                                  </div>
                                                                                  <div style="text-align: left; line-height: 1.5;">
                                                                                    <br>
                                                                                  </div>
                                                                                  <div style="text-align: left; line-height: 1.5;">
                                                                                    <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                                      <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                                        <span data-custom-class="body_text">{{ __('policy.section_14.content.2') }}</span>
                                                                                      </span>
                                                                                    </span>
                                                                                  </div>
                                                                                  <div style="text-align: left; line-height: 1.5;">
                                                                                    <br>
                                                                                  </div>
                                                                                  <div id="contact" style="text-align: left; line-height: 1.5;">
                                                                                    <span style="color: rgb(127, 127, 127);">
                                                                                      <span style="color: rgb(89, 89, 89); font-size: 15px;">
                                                                                        <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                                          <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                                            <span id="control" style="color: rgb(0, 0, 0);">
                                                                                              <strong>
                                                                                                <span data-custom-class="heading_1">{{ __('policy.section_15.title') }}</span>
                                                                                              </strong>
                                                                                            </span>
                                                                                          </span>
                                                                                        </span>
                                                                                      </span>
                                                                                    </span>
                                                                                  </div>
                                                                                  <div style="text-align: left; line-height: 1.5;">
                                                                                    <br>
                                                                                  </div>
                                                                                  <div style="text-align: left; line-height: 1.5;">
                                                                                    <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                                      <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                                        <span data-custom-class="body_text">
                                                                                          {{ __('policy.section_15.content.1') }}
                                                                                        </span>
                                                                                      </span>
                                                                                    </span>
                                                                                  </div>
                                                                                  <div style="text-align: left; line-height: 1.5;">
                                                                                    <br>
                                                                                  </div>
                                                                                  <div style="text-align: left; line-height: 1.5;">
                                                                                    <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                                      <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                                        <span data-custom-class="body_text">
                                                                                          <span style="font-size: 15px;">
                                                                                            <span style="color: rgb(89, 89, 89);">
                                                                                              <span style="color: rgb(89, 89, 89);">
                                                                                                <span data-custom-class="body_text">
                                                                                                  <bdt class="question">{{ __('policy.section_15.content.2') }}</bdt>
                                                                                                </span>
                                                                                              </span>
                                                                                            </span>
                                                                                          </span>
                                                                                        </span>
                                                                                      </span>
                                                                                    </span>
                                                                                  </div>
                                                                                  <div style="text-align: left; line-height: 1.5;">
                                                                                    <span style="font-size: 15px;">
                                                                                      <span data-custom-class="body_text">
                                                                                        {{ __('policy.section_15.content.3') }}
                                                                                      </span>
                                                                                    </span>
                                                                                  </div>
                                                                                  <div style="text-align: left; line-height: 1.5;">
                                                                                    <span style="font-size: 15px;">
                                                                                      <span style="color: rgb(89, 89, 89);">
                                                                                        <span style="color: rgb(89, 89, 89);">
                                                                                          <span data-custom-class="body_text">
                                                                                            <bdt class="question">
                                                                                              {{ __('policy.section_15.content.4') }}
                                                                                            </bdt>
                                                                                          </span>
                                                                                        </span>
                                                                                      </span>
                                                                                    </span>
                                                                                  </div>
                                                                                  <div style="text-align: left; line-height: 1.5;">
                                                                                    <span data-custom-class="body_text">
                                                                                      <span style="font-size: 15px;">
                                                                                        <bdt class="question">{{ __('policy.section_15.content.5') }}</bdt>
                                                                                      </span>
                                                                                    </span>
                                                                                  </div>
                                                                                  <div style="text-align: left; line-height: 1.5;">
                                                                                    <span data-custom-class="body_text">
                                                                                      <bdt class="question">{{ __('policy.section_15.content.6') }}</bdt>
                                                                                    </span>
                                                                                  </div>
                                                                                  <div style="text-align: left; line-height: 1.5;">
                                                                                    <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                                      <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                                        <span data-custom-class="body_text">
                                                                                          <bdt class="question">
                                                                                            {{ __('policy.section_15.content.7') }}
                                                                                          </bdt>
                                                                                        </span>
                                                                                      </span>
                                                                                    </span>
                                                                                  </div>
                                                                                  <div style="text-align: left; line-height: 1.5;">
                                                                                    <br>
                                                                                  </div>
                                                                                  <div id="request" style="text-align: left; line-height: 1.5;">
                                                                                    <span style="color: rgb(127, 127, 127);">
                                                                                      <span style="color: rgb(89, 89, 89); font-size: 15px;">
                                                                                        <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                                          <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                                            <span id="control" style="color: rgb(0, 0, 0);">
                                                                                              <strong>
                                                                                                <span data-custom-class="heading_1">{{ __('policy.section_16.title') }}</span>
                                                                                              </strong>
                                                                                            </span>
                                                                                          </span>
                                                                                        </span>
                                                                                      </span>
                                                                                    </span>
                                                                                  </div>
                                                                                  <div style="text-align: left; line-height: 1.5;">
                                                                                    <br>
                                                                                  </div>
                                                                                  <div style="text-align: left; line-height: 1.5;">
                                                                                    <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                                      <span style="font-size: 15px; color: rgb(89, 89, 89);">
                                                                                        <span data-custom-class="body_text">
                                                                                          {{ __('policy.section_16.content.1') }}
                                                                                        </span>
                                                                                      </span>
                                                                                  </div>
                                                                                  <style>
                                                                                    ul {
                                                                                          list-style-type: square;
                                                                                          }
                                                                                          ul > li > ul {
                                                                                          list-style-type: circle;
                                                                                          }
                                                                                          ul > li > ul > li > ul {
                                                                                          list-style-type: square;
                                                                                          }
                                                                                          ol li {
                                                                                          font-family: Arial ;
                                                                                          }
                                                                                  </style>
                                                                                </div>
                                                                                <script defer src="/gptzerome/static/js/vcd15cbe7772f49c399c6a5babf22c1241717689176015.js" data-cf-beacon='{"rayId":"94a53cb698a0a0e9","version":"2025.5.0","serverTiming":{"name":{"cfExtPri":true,"cfEdge":true,"cfOrigin":true,"cfL4":true,"cfSpeedBrain":true,"cfCacheStatus":true}},"token":"c0bc744898e3448ea9975f46c74bbc60","b":1}'></script>
                                                                                <script data-cfasync="false" src="/gptzerome/static/js/email-decode.min.js"></script>
                                                                              </div>
                                                                            </div>
                                                                          </div>
                                                                        </div>
                                                                      </div>
                                                                    </div>
                                                                  </div>
                                                                </div>
                                                              </div>
                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // 示例：添加其他部分的 js 代码（可根据需要扩展）
        // alert('Hello World!');
    });
</script>
@endsection