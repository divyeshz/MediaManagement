@extends('layouts/layoutMaster')

@section('title', 'User Profile - Profile')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}">
@endsection

<!-- Page -->
@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-profile.css') }}" />
@endsection


@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/app-user-view-account.js') }}"></script>
@endsection

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">User Profile /</span> Profile
    </h4>

    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="user-profile-header-banner">
                    <img src="{{ asset('assets/img/pages/profile-banner.png') }}" alt="Banner image" class="rounded-top">
                </div>
                <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                    <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                        @if (Auth::user()->profile)
                            <img src="{{ asset(Auth::user()->profile) }}" alt="user image"
                                class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=41&background=696cff&color=FFFFFF"
                                class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img avatar-initial rounded-circle">
                        @endif
                    </div>
                    <div class="flex-grow-1 mt-3 mt-sm-5">
                        <div
                            class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                            <div class="user-profile-info">
                                <h4>{{ Auth::user()->name }}</h4>
                                <ul
                                    class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                    <li class="list-inline-item fw-medium">
                                        <i class='bx bx-envelope'></i> {{ Auth::user()->email }}
                                    </li>
                                </ul>
                            </div>
                            <a href="{{ route('user.edit', Auth::user()->id) }}"
                                class="btn rounded-pill btn-icon btn-primary">
                                <i class='bx bx-edit'></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Header -->

    <!-- User Profile Content -->
    <div class="row">
        <div class="col-12">
            <!-- About User -->
            <div class="card mb-4">
                <div class="card-body">
                    <small class="text-muted text-uppercase">About</small>
                    <ul class="list-unstyled mb-4 mt-3">
                        <li class="d-flex align-items-center mb-3"><i class="bx bx-user"></i><span
                                class="fw-medium mx-2">Full Name:</span> <span>{{ Auth::user()->name }}</span></li>
                    </ul>
                    <small class="text-muted text-uppercase">Contacts</small>
                    <ul class="list-unstyled mb-4 mt-3">
                        <li class="d-flex align-items-center mb-3"><i class="bx bx-phone"></i><span
                                class="fw-medium mx-2">Contact:</span> <span>{{ Auth::user()->phone ?? '-' }}</span></li>
                        <li class="d-flex align-items-center mb-3"><i class="bx bx-envelope"></i><span
                                class="fw-medium mx-2">Email:</span> <span>{{ Auth::user()->email }}</span></li>
                    </ul>
                </div>
            </div>
            <!--/ About User -->
        </div>
    </div>
    <!--/ User Profile Content -->
@endsection
