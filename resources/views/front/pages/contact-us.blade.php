@extends('layouts.around.master')

@section('title', __('contact-us.contact_title'))
@section('description', __('contact-us.contact_description'))
@section('keywords', __('contact-us.contact_keywords'))

@section('content')

<!-- Page title -->
<section class="container pt-5 pb-4 pb-lg-0 my-md-2 my-lg-5">
  <div class="row pt-5 pb-4 pb-lg-5 mb-2 mt-1 mt-sm-2 my-xl-3">
    <div class="col-md-7">
      <h1 class="display-3 fw-medium text-uppercase mb-0">
        {{ __('contact-us.title') }}
      </h1>
    </div>
    <div class="col-md-5 col-lg-4 offset-lg-1 pt-3 pt-md-2">
      <p class="mb-0">
        {{ __('contact-us.description') }}
      </p>
    </div>
  </div>
  <hr>
</section>

<!-- Contact content -->
<section class="container pb-5 mb-md-2 mb-lg-3 mb-xl-4 mb-xxl-5">
  <div class="row pt-4 pt-lg-5">

    <!-- Contact form -->
    <div class="col-lg-7">
      <h2 class="h3 mb-4">{{ __('contact-us.form_title') }}</h2>
      @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      <form action="{{ route('save-contact') }}" method="POST">
      {{-- <form action="" method="POST"> --}}
        @csrf

        <div class="row g-4">
          <div class="col-sm-6">
            <label class="form-label">{{ __('contact-us.name') }}</label>
            <input
              type="text"
              name="name"
              class="form-control rounded-pill"
              required
            >
          </div>

          <div class="col-sm-6">
            <label class="form-label">{{ __('contact-us.email') }}</label>
            <input
              type="email"
              name="email"
              class="form-control rounded-pill"
              required
            >
          </div>

          <div class="col-12">
            <label class="form-label">{{ __('contact-us.subject') }}</label>
            <input
              type="text"
              name="subject"
              class="form-control rounded-pill"
            >
          </div>

          <div class="col-12">
            <label class="form-label">{{ __('contact-us.message') }}</label>
            <textarea
              name="message"
              rows="6"
              class="form-control rounded-4"
              required
            ></textarea>
          </div>

          <div class="col-12 pt-2">
            <button
              type="submit"
              class="btn btn-primary rounded-pill px-5"
            >
              {{ __('contact-us.submit') }}
            </button>
          </div>
        </div>
      </form>
    </div>

    <!-- Contact info -->
    <aside class="col-lg-5 mt-5 mt-lg-0 ps-lg-5">
      <h2 class="h4 mb-4">{{ __('contact-us.info_title') }}</h2>

      <div class="mb-4">
        <h3 class="h6 mb-1">{{ __('contact-us.email') }}</h3>
        <p class="mb-0">
          <a href="mailto:{{ config('mail.from.address') }}">
            {{ config('mail.from.address') }}
          </a>
        </p>
      </div>

      <div class="mb-4">
        <h3 class="h6 mb-1">{{ __('contact-us.response_time') }}</h3>
        <p class="mb-0">
          {{ __('contact-us.response_desc') }}
        </p>
      </div>

      <div class="pt-3">
        <p class="text-body-secondary fs-sm">
          {{ __('contact-us.privacy_notice') }}
        </p>
      </div>
    </aside>

  </div>
</section>

@endsection
