@extends('layouts.around.master')

@section('title', __('lang.help_title'))
@section('description', __('lang.help_description'))
@section('keywords', __('lang.help_keywords'))

@section('content')

<!-- Page title -->
<section class="container pt-5 pb-4 pb-lg-0 my-md-2 my-lg-5">
  <div class="row pt-5 pb-4 pb-lg-5 mb-2 mt-1 mt-sm-2 my-xl-3">
    <div class="col-md-7">
      <h1 class="display-3 fw-medium text-uppercase mb-0">
        {{ __('help.title') }}
      </h1>
    </div>
    <div class="col-md-5 col-lg-4 offset-lg-1 pt-3 pt-md-2">
      <p class="mb-0">
        {{ __('help.description') }}
      </p>
    </div>
  </div>
  <hr>
</section>

<!-- Help content -->
<section class="container pb-5 mb-md-2 mb-lg-3 mb-xl-4 mb-xxl-5">

  <!-- Quick help blocks -->
  <div class="row pt-4 pt-lg-5 g-4">
    <div class="col-md-4">
      <div class="card h-100 border-0 shadow-sm rounded-5 p-4">
        <h3 class="h5">{{ __('help.account_title') }}</h3>
        <p class="text-body-secondary">
          {{ __('help.account_desc') }}
        </p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card h-100 border-0 shadow-sm rounded-5 p-4">
        <h3 class="h5">{{ __('help.article_title') }}</h3>
        <p class="text-body-secondary">
          {{ __('help.article_desc') }}
        </p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card h-100 border-0 shadow-sm rounded-5 p-4">
        <h3 class="h5">{{ __('help.tech_title') }}</h3>
        <p class="text-body-secondary">
          {{ __('help.tech_desc') }}
        </p>
      </div>
    </div>
  </div>

  <!-- FAQ -->
  <div class="row pt-5 mt-lg-4">
    <div class="col-lg-8">
      <h2 class="h3 mb-4">{{ __('help.faq_title') }}</h2>

      <div class="accordion" id="helpFaq">

        @foreach(__('help.faq') as $index => $faq)
        <div class="accordion-item border-0 mb-3 rounded-4 shadow-sm">
          <h3 class="accordion-header">
            <button
              class="accordion-button collapsed rounded-4"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#faq-{{ $index }}"
            >
              {{ $faq['q'] }}
            </button>
          </h3>
          <div
            id="faq-{{ $index }}"
            class="accordion-collapse collapse"
            data-bs-parent="#helpFaq"
          >
            <div class="accordion-body text-body-secondary">
              {{ $faq['a'] }}
            </div>
          </div>
        </div>
        @endforeach

      </div>
    </div>

    <!-- Sidebar -->
    <aside class="col-lg-4 mt-5 mt-lg-0 ps-lg-5">
      <h2 class="h4 mb-3">{{ __('help.need_more_help') }}</h2>
      <p class="text-body-secondary">
        {{ __('help.need_more_desc') }}
      </p>

      <a
        href="{{ route('contact') }}"
        class="btn btn-primary rounded-pill px-4 mt-2"
      >
        {{ __('help.contact_button') }}
      </a>
    </aside>
  </div>

</section>

@endsection
