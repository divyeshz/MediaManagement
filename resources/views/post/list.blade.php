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
                <span class="text-muted fw-light">Home /</span> Post list
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
                    <a href="{{ route('post.list') }}" type="reset" class="btn btn-label-secondary">Cancel</a>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('post.create') }}" class="btn btn-primary">
                        <span class="tf-icons bx bx-plus me-1"></span>Add
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5" id="postList">
        @include('_partials.post_list')
    </div>

    @if ($posts->links() != '')
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-center">
                    {!! $posts->links() !!}
                </div>
            </div>
        </div>
    @endif
@endsection


@section('page-script')

    @include('components.flash')
    <script src="{{ asset('assets/js/cards-actions.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(document).on("click", ".delete", function() {
                const form = $(this).closest('.delete-form');

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger me-2'
                    },
                    buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonClass: 'me-2',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        form.submit();
                    } else if (
                        // Read more about handling dismissals
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons.fire(
                            'Cancelled',
                            'Your imaginary file is safe :)',
                            'error'
                        )
                    }
                });
            });

            $(document).on('keyup', '#search', function() {
                $.ajax({
                    url: "{{ route('post.list') }}",
                    method: 'GET',
                    data: {
                        search: $(this).val(),
                        is_ajax: true
                    },
                    success: function(data) {
                        $('#postList').html(data)
                    }
                })
            });

            $(document).on('change', '.status', function(event) {
                event.stopPropagation();
                $.ajax({
                    url: "{{ route('post.list') }}",
                    method: 'GET',
                    data: {
                        status: $(".status option:selected").val(),
                        is_ajax: true
                    },
                    success: function(data) {
                        $('#postList').html(data)
                    }
                })
            });

            $(document).on("change", ".sharedUsersIds", function(event) {
                event.stopPropagation();
                var formData = $(this).closest('.sharePostsForm').serialize();

                // Send AJAX request
                $.ajax({
                    url: "{{ route('post.sharePosts') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Handle success response here if needed
                        console.log(response);
                    },
                    error: function(xhr, status, error) {
                        // Handle error response here if needed
                        console.error(xhr.responseText);
                    }
                });
            });

            $(document).on('change', '.selectUserSearch', function() {
                var sharedUserIds = [];

                // Iterate through each select box
                $(this).find('option:selected').each(function() {
                    sharedUserIds.push($(this).val());
                });

                $.ajax({
                    url: "{{ route('post.list') }}",
                    method: 'GET',
                    data: {
                        sharedUserIds: sharedUserIds,
                        is_ajax: true
                    },
                    success: function(data) {
                        $('#postList').html(data)
                    }
                });
            });

            $(document).on('click', '.shareButton', function() {
                const form = $(this).closest('.sharePostsForm');

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger me-2'
                    },
                    buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                    title: 'Are you sure?',
                    text: "You Want to share this post!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonClass: 'me-2',
                    confirmButtonText: 'Yes, share it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        form.submit();
                    } else if (
                        // Read more about handling dismissals
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons.fire(
                            'Cancelled',
                            'Your file is not shared safe :)',
                            'error'
                        )
                    }
                });
            });

            $(document).on('change', '.postType', function() {
                let postType = $(this).val();
                // chnagePostType(postType);
            });

            // let postType = $(".postType option:selected").val();
            // chnagePostType(postType);

            // function chnagePostType(post) {
            //     if (post != "") {
            //         $.ajax({
            //             url: "{{ route('post.list') }}",
            //             method: 'GET',
            //             data: {
            //                 post_type: post,
            //                 is_ajax: true
            //             },
            //             success: function(data) {
            //                 $('#postList').html(data)
            //             }
            //         });
            //     }
            // }
        });
    </script>
@endsection
