@php
    $customizerHidden = 'customizer-hide';
    $pageConfigs = ['myLayout' => 'blank'];
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Login')

@section('vendor-style')
    <!-- Vendor -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
@endsection

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/pages-auth.js') }}"></script>
@endsection

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="{{ url('/') }}" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">@include('_partials.macros', [
                                    'width' => 25,
                                    'withbg' => 'var(--bs-primary)',
                                ])</span>
                                <span
                                    class="app-brand-text demo text-body fw-bold">{{ config('variables.templateName') }}</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-2">Welcome to {{ config('variables.templateName') }}! 👋</h4>
                        <p class="mb-4">Please sign-in to your account and start the adventure</p>

                        <div class="d-flex justify-content-center">
                            <a href="{{ route('auth.facebook') }}" class="btn rounded-pill btn-facebook me-3"><i
                                    class="tf-icons bx bxl-facebook me-1"></i>Facebook</a>
                            <a href="{{ route('auth.google') }}" class="btn rounded-pill btn-google-plus">
                                <i class="tf-icons bx bxl-google-plus me-1"></i>Google+
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>
@endsection
