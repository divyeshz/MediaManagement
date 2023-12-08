@extends('layouts/layoutMaster')

@section('title', 'Post List')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/masonry/masonry.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-6">
            <h4 class="py-3 mb-4">
                <span class="text-muted fw-light">Home /</span> Shared Post list
            </h4>
        </div>

    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-2">
                    <select name="postType" id="selectpickerBasic" placeholder="Select Post Type"
                        class="postType selectpicker w-100" data-style="btn-default">
                        <option {{ request()->input('post_type') == 'image' ? 'selected' : 'selected' }} value="image">
                            Image
                        </option>
                        <option {{ request()->input('post_type') == 'text' ? 'selected' : '' }} value="text">Text</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select id="selectpickerLiveSearch selectpickerSelectDeselect " name="sharedUsersIds[]"
                        class="selectpicker w-100 selectUserSearch" data-style="btn-default" data-live-search="false"
                        multiple data-actions-box="false" data-size="5" placeholder="Selcte User">
                        @foreach ($users as $user)
                            @if ($user->profile == '')
                                <option value="{{ $user->id }}"
                                    data-content="<img src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=30&background=696cff&color=FFFFFF' class='avatar-initial rounded-circle'>&nbsp;{{ $user->name }}">
                                    {{ $user->name }}</option>
                            @else
                                <option value="{{ $user->id }}"
                                    data-content="<img src='{{ asset($user->profile) }}' class='rounded-circle' width='30' height='30'>&nbsp;{{ $user->name }}">
                                    {{ $user->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" id="selectpickerBasic" placeholder="Select Status"
                        class="status selectpicker w-100" data-style="btn-default">
                        <option value="" selected>Select Status</option>
                        <option {{ request()->input('status') == '1' ? 'selected' : '' }} value="1">Active</option>
                        <option {{ request()->input('status') == '0' ? 'selected' : '' }} value="0">Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name='search' class="form-control" id="search" placeholder="Search"
                        value="{{ request()->search }}" />
                </div>
                <div class="col-md-2">
                    <a href="{{ route('post.sharePostsList') }}" type="reset" class="btn btn-label-secondary">Cancel</a>
                </div>
                <div class="col-md-2 text-md-end">
                    <a href="{{ route('post.create') }}" class="btn btn-primary">
                        <span class="tf-icons bx bx-plus me-1"></span>Add
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5" id="sharedPostList">
        @include('_partials.shared_post_list')
    </div>

@endsection


@section('page-script')

    @include('components.flash')
    <script src="{{ asset('assets/js/cards-actions.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(document).on('keyup', '#search', function() {
                $.ajax({
                    url: "{{ route('post.sharePostsList') }}",
                    method: 'GET',
                    data: {
                        search: $(this).val(),
                        is_ajax: true
                    },
                    success: function(data) {
                        $('#sharedPostList').html(data)
                    }
                })
            });

            $(document).on('change', '.status', function() {
                $.ajax({
                    url: "{{ route('post.sharePostsList') }}",
                    method: 'GET',
                    data: {
                        status: $(this).val(),
                        is_ajax: true
                    },
                    success: function(data) {
                        $('#sharedPostList').html(data)
                    }
                })
            });
            $(document).on('change', '.selectUserSearch', function() {
                var sharedUserIds = [];

                // Iterate through each select box
                $(this).find('option:selected').each(function() {
                    sharedUserIds.push($(this).val());
                });

                $.ajax({
                    url: "{{ route('post.sharePostsList') }}",
                    method: 'GET',
                    data: {
                        sharedUserIds: sharedUserIds,
                        is_ajax: true
                    },
                    success: function(data) {
                        $('#sharedPostList').html(data)
                    }
                });
            });

            $(document).on('change', '.postType', function() {
                let postType = $(this).val();
                chnagePostType(postType);
            });

            let postType = $(".postType option:selected").val();
            chnagePostType(postType);
        });

        function chnagePostType(post) {
            if (post != "") {
                $.ajax({
                    url: "{{ route('post.sharePostsList') }}",
                    method: 'GET',
                    data: {
                        post_type: post,
                        is_ajax: true
                    },
                    success: function(data) {
                        $('#sharedPostList').html(data);
                    }
                });
            }
        }
    </script>
@endsection
