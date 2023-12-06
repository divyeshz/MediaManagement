@extends('layouts/layoutMaster')

@section('title', 'Post List')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/masonry/masonry.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-6">
            <h4 class="py-3 mb-4">
                <span class="text-muted fw-light">Home /</span> Post list
            </h4>
        </div>
        <div class="col-md-6 text-md-end pt-3 mb-4">
            <a href="{{ route('post.create') }}" class="btn btn-primary">
                <span class="tf-icons bx bx-plus me-1"></span>Add
            </a>
        </div>
    </div>


    <div class="row mb-5" data-masonry='{"percentPosition": true }'>
        @foreach ($posts as $post)
            @if ($post->post_type == 'image')
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $post->name }}</h5>
                            <img class="img-fluid d-flex mx-auto my-4 rounded w-100"
                                src="{{ asset('' . $post->image . '') }}" alt="Card image cap" />
                            <div class="d-flex align-items-center justify-content-between mt-3">
                                <div class="card-actions">
                                    <form action="{{ route('post.destroy', $post->id) }}" class="delete-form"
                                        method="POST">
                                        @csrf
                                        <a href="{{ route('post.edit', $post->id) }}" class=" me-3"><i
                                                class="bx bx-edit me-1"></i></a>
                                        <a href="javascript:;" class="delete"><i
                                                class="bx bx-trash me-1 text-primary"></i></a>
                                    </form>
                                </div>
                                <div class="dropup d-none d-sm-block">
                                    <button class="btn p-0" type="button" id="sharedList" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class='text-primary bx bx-share-alt'></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="sharedList">
                                        <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif ($post->post_type == 'text')
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h3>{!! $post->text !!}</h3>
                            <div class="d-flex align-items-center justify-content-between mt-3">
                                <div class="card-actions">
                                    <form action="{{ route('post.destroy', $post->id) }}" class="delete-form"
                                        method="POST">
                                        @csrf
                                        <a href="{{ route('post.edit', $post->id) }}" class=" me-3"><i
                                                class="bx bx-edit me-1"></i></a>
                                        <a href="javascript:;" class="delete"><i
                                                class="bx bx-trash me-1 text-primary"></i></a>
                                    </form>
                                </div>
                                <div class="dropup d-none d-sm-block">
                                    <button class="btn p-0" type="button" id="sharedList" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class='text-primary bx bx-share-alt'></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="sharedList">
                                        <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
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
        });
    </script>
@endsection
