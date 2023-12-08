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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
    {{-- <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script> --}}
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
                    <form class="needs-validation" action="{{ route('user.update', $users->id) }}" id="userEditForm"
                        method="post" enctype="multipart/form-data" novalidate>

                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="bs-validation-name">Name</label>
                            <input type="text" name='name' class="form-control" id="bs-validation-name"
                                placeholder="John Doe" value="{{ $users->name }}" required />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="bs-validation-email">Email</label>
                            <input readonly type="email" name='email' id="bs-validation-email"
                                value="{{ $users->email }}" class="form-control" placeholder="john.doe"
                                aria-label="john.doe" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="phone">Phone No</label>
                            <input type="number" name="phone" value="{{ $users->phone }}" id="phone"
                                class="form-control phone-mask" placeholder="6587998941" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="bs-validation-upload-file">Profile pic</label>
                            <input type="file" name='profile' class="form-control" id="bs-validation-upload-file" />
                        </div>

                        @if (isset($users) && $users != null && $users->profile != '')
                            <div class="mb-3 image_box">
                                <input type="hidden" name="hidden_profile" class="hidden_profile"
                                    value="{{ $users->profile }}">
                                <img class="profile"
                                    src="{{ asset('' . str_replace('/profile/', '/profile/thumbnail/', $users->profile) . '') }}"
                                    alt="image" />
                                <button type="button" class="btn btn-danger mt-3 remove">Remove</button>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="d-block form-label">Gender</label>
                            <div class="form-check mb-2">
                                <label class="form-check-label" for="bs-validation-radio-male">Male</label>
                                <input type="radio" name="gender" value="male" id="bs-validation-radio-male"
                                    name="bs-validation-radio" class="form-check-input"
                                    {{ $users->gender == 'male' ? 'checked' : '' }} />
                            </div>
                            <div class="form-check">
                                <label class="form-check-label" for="bs-validation-radio-female">Female</label>
                                <input type="radio" name="gender" value="female" id="bs-validation-radio-female"
                                    name="bs-validation-radio" class="form-check-input"
                                    {{ $users->gender == 'female' ? 'checked' : '' }} required />
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" value="1" class="form-check-input"
                                    id="bs-validation-checkbox" {{ $users->is_active == true ? 'checked' : '' }} />
                                <label class="form-check-label" for="bs-validation-checkbox">Active</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ url()->previous() }}" class="btn btn-label-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('page-script')
    <script>
        $(document).ready(function() {

            $.validator.addMethod('fileSize', function(value, element, param) {
                return this.optional(element) || (element.files[0].size <= param);
            }, 'File size must be less than {0} bytes');

            $("#userEditForm").validate({
                rules: {
                    name: "required",
                    gender: "required",
                    profile: {
                        accept: "image/*",
                        fileSize: {
                            depends: function(element) {
                                return $(element).val() !== ''; // Check if a file is selected
                            },
                            param: 5 * 1024 * 1024, // 5MB in bytes
                            // Add custom method to display message only when a file is uploaded
                            method: function(value, element) {
                                if ($(element).val() !== '' && !/(\.|\/)(png|jpe?g)$/i.test(value)) {
                                    return false;
                                }
                                return true;
                            }
                        }
                    }
                },
                messages: {
                    name: "Please specify your name",
                    gender: "Please specify your gender",
                    profile: {
                        accept: "Please select a valid image file (PNG, JPG, JPEG)",
                        fileSize: "File size must be less than 5MB"
                    }
                }
            });
        });


        $('.remove').click(function() {
            $('.hidden_profile').attr('value', '');
            $('.image_box').remove();
        });
    </script>
@endsection
