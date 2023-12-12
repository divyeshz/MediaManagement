@extends('layouts/layoutMaster')

@section('title', 'Post List')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
    <style>
        .image_box {
            border: 1px solid #e9ecef;
            padding: 8px;
            border-radius: 5px;
            vertical-align: top;
            text-align: center;
            max-width: 100%;
            margin-top: 10px;
        }

        .image {
            max-width: 100%;
            height: 100%;
        }
    </style>
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
                                    data-content="<span class='avatar-initial text-white p-1 rounded-circle bg-primary'>{{ implode('', array_map(fn($word) => strtoupper($word[0]), explode(' ', $user->name))) }}</span>&nbsp;{{ $user->name }}">
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

    <div id="commentModalDiv">
    </div>

@endsection

@includeIf('components.commentModal')

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

            $(document).on('click', '.commentModalBtn', function() {
                let id = $(this).attr('data-id');
                // Send AJAX request
                $.ajax({
                    url: "{{ route('comment.comments') }}",
                    type: 'POST',
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        // Handle success response here if needed
                        // console.log(response);
                        $('#commentModalDiv').html(response);
                        $('#commentModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        // Handle error response here if needed
                        console.error(xhr.responseText);
                    }
                });

            });

            $(document).on('click', '.commentStoreBtn', function(e) {
                e.preventDefault();
                // Send AJAX request
                $.ajax({
                    url: "{{ route('comment.commentStore') }}",
                    type: 'POST',
                    data: $("#commentForm").serialize(),
                    success: function(response) {
                        // Append or update the comments section within the modal
                        $('#commentModal .modal-body').find('.comments-list').html($(response)
                            .find('.comments-list').html());

                        // Clear the comment form
                        $('#commentForm textarea[name="comment_text"]').val('');
                        $('#commentForm input[name="edited_comment_id"]')
                            .remove(); // Remove the hidden input
                    },
                    error: function(xhr, status, error) {
                        // Handle error response here if needed
                        console.error(xhr.responseText);
                    }
                });

            });

            $(document).on('click', '.editComment', function(e) {
                e.preventDefault();
                // Get the comment text
                let commentText = $(this).closest('.media').find('.media-body p').text();
                // Set the comment text to the textarea
                $('#commentForm textarea[name="comment_text"]').val(commentText);

                // Get the comment ID from the data attribute
                let commentID = $(this).data('id');

                // Create or update the hidden input field for comment ID
                let hiddenInput = $('#commentForm input[name="edited_comment_id"]');
                if (hiddenInput.length === 0) {
                    // If the hidden input doesn't exist, create it
                    hiddenInput = $('<input>').attr({
                        type: 'hidden',
                        name: 'edited_comment_id',
                        value: commentID
                    });
                    $('#commentForm').append(hiddenInput);
                } else {
                    // If the hidden input exists, update its value
                    hiddenInput.val(commentID);
                }
            });

            $(document).on('click', '.resetCommentForm', function(e) {
                e.preventDefault();
                // Clear the comment form
                $('#commentForm').trigger('reset'); // Reset the form

                $('#commentForm textarea[name="comment_text"]').val('');
                $('#commentForm input[name="edited_comment_id"]').remove(); // Remove the hidden input
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
