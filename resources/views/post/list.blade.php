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
                <div class="col-md-3">
                    <input type="text" name='search' class="form-control" id="search" placeholder="Search"
                        value="{{ request()->search }}" />
                </div>
                <div class="col-md-9 text-md-end">
                    <a href="{{ route('post.create') }}" class="btn btn-primary">
                        <span class="tf-icons bx bx-plus me-1"></span>Add
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5" id="postList">
        @include('_partials.post_list')
        {{-- Pagination --}}
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

        });
    </script>
@endsection
