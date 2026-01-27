@extends('layouts.around.master')

@section('title', __('lang.about_title'))
@section('description', __('lang.about_description'))
@section('keywords', __('lang.about_keywords'))

@section('content')

<!-- Page title -->
<section class="container pt-5 pb-4 pb-lg-0 my-md-2 my-lg-5">
  <div class="row pt-5 pb-4 pb-lg-5 mb-2 mt-1 mt-sm-2 my-xl-3">
    <div class="col-md-7">
      <h1 class="display-3 fw-medium text-uppercase mb-0">
        {{ __('about-us.title') }}
      </h1>
    </div>
    <div class="col-md-5 col-lg-4 offset-lg-1 pt-3 pt-md-2">
      <p class="mb-0">
        {{ __('about-us.subtitle') }}
      </p>
    </div>
  </div>
  <hr>
</section>

<!-- About content -->
<section class="container pb-5 mb-md-2 mb-lg-3 mb-xl-4 mb-xxl-5">

  <!-- Introduction -->
  <div class="row pt-4 pt-lg-5">
    <div class="col-lg-8">
      <h2 class="h3 mb-3">{{ __('about-us.intro_title') }}</h2>
      <p class="text-body-secondary fs-lg">
        {{ __('about-us.intro_text') }}
      </p>
    </div>
  </div>

  <!-- Mission / Vision -->
  <div class="row pt-5 g-4">
    <div class="col-md-6">
      <div class="card h-100 border-0 shadow-sm rounded-5 p-4">
        <h3 class="h5">{{ __('about-us.mission_title') }}</h3>
        <p class="text-body-secondary mb-0">
          {{ __('about-us.mission_text') }}
        </p>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card h-100 border-0 shadow-sm rounded-5 p-4">
        <h3 class="h5">{{ __('about-us.vision_title') }}</h3>
        <p class="text-body-secondary mb-0">
          {{ __('about-us.vision_text') }}
        </p>
      </div>
    </div>
  </div>

  <!-- Values -->
  <div class="row pt-5">
    <div class="col-lg-12">
      <h2 class="h3 mb-4">{{ __('about-us.values_title') }}</h2>
    </div>

    <div class="col-md-4">
      <div class="card h-100 border-0 rounded-5 p-4">
        <h3 class="h6">{{ __('about-us.value_1_title') }}</h3>
        <p class="text-body-secondary">
          {{ __('about-us.value_1_text') }}
        </p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card h-100 border-0 rounded-5 p-4">
        <h3 class="h6">{{ __('about-us.value_2_title') }}</h3>
        <p class="text-body-secondary">
          {{ __('about-us.value_2_text') }}
        </p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card h-100 border-0 rounded-5 p-4">
        <h3 class="h6">{{ __('about-us.value_3_title') }}</h3>
        <p class="text-body-secondary">
          {{ __('about-us.value_3_text') }}
        </p>
      </div>
    </div>
  </div>

  <!-- Call to action -->
  <div class="row pt-5 mt-lg-4">
    <div class="col-lg-8">
      <h2 class="h3 mb-3">{{ __('about-us.cta_title') }}</h2>
      <p class="text-body-secondary">
        {{ __('about-us.cta_text') }}
      </p>
      <a
        href="{{ route('contact') }}"
        class="btn btn-primary rounded-pill px-4 mt-2"
      >
        {{ __('about-us.cta_button') }}
      </a>
    </div>
  </div>

</section>

@endsection
