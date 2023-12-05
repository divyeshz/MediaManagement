@extends('layouts/layoutMaster')

@section('title', 'User Edit')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
    <style>
        .image_box {
            border: 1px solid #e9ecef;
            padding: 8px;
            border-radius: 5px;
            vertical-align: top;
            text-align: center;
        }
        .profile {
            max-width: 100%;
            height: 100%;
            margin-top: 10px;
        }
    </style>
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/form-validation.js') }}"></script>
    <script>
        $('.remove').click(function() {
            $('.hidden_profile').attr('value', '');
            $('.image_box').remove();
        });
    </script>
@endsection

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Home /</span> User Edit
    </h4>

    <div class="row">
        <div class="col-xxl">
            <div class="card">
                <h5 class="card-header">Bootstrap Validation</h5>
                <div class="card-body">
                    <form class="needs-validation" action="{{ route('user.update', $users->id) }}" method="post"
                        enctype="multipart/form-data" novalidate>

                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="bs-validation-name">Name</label>
                            <input type="text" name='name' class="form-control" id="bs-validation-name"
                                placeholder="John Doe" value="{{ $users->name }}" required />
                            <div class="valid-feedback"> Looks good! </div>
                            <div class="invalid-feedback"> Please enter your name. </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="bs-validation-email">Email</label>
                            <input readonly type="email" name='email' id="bs-validation-email" value="{{ $users->email }}"
                                class="form-control" placeholder="john.doe" aria-label="john.doe" required />
                            <div class="valid-feedback"> Looks good! </div>
                            <div class="invalid-feedback"> Please enter a valid email </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="phone">Phone No</label>
                            <input type="number" name="phone" value="{{ $users->phone }}" id="phone" class="form-control phone-mask"
                                placeholder="6587998941" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="bs-validation-upload-file">Profile pic</label>
                            <input type="file" name='profile' class="form-control" id="bs-validation-upload-file"
                                {{ $users->profile == '' ? 'required' : '' }} />
                        </div>

                        @if (isset($users) && $users != null && $users->profile != '')
                            <div class="mb-3 image_box">
                                <input type="hidden" name="hidden_profile" class="hidden_profile"
                                    value="{{ $users->profile }}">
                                <img class="profile" src="{{ $users->profile }}" alt="image" />
                                <button type="button" class="btn btn-danger mt-3 remove">Remove</button>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="d-block form-label">Gender</label>
                            <div class="form-check mb-2">
                                <input type="radio" name="gender" value="male" id="bs-validation-radio-male"
                                    name="bs-validation-radio" class="form-check-input" required
                                    {{ $users->gender == 'male' ? 'checked' : '' }} />
                                <label class="form-check-label" for="bs-validation-radio-male">Male</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="gender" value="female" id="bs-validation-radio-female"
                                    name="bs-validation-radio" class="form-check-input" required
                                    {{ $users->gender == 'female' ? 'checked' : '' }} />
                                <label class="form-check-label" for="bs-validation-radio-female">Female</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" value="1" class="form-check-input"
                                    id="bs-validation-checkbox" required
                                    {{ $users->is_active == true ? 'checked' : '' }} />
                                <label class="form-check-label" for="bs-validation-checkbox">Active</label>
                                <div class="invalid-feedback"> You must agree before submitting. </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
