@extends('layouts/layoutMaster')

@section('title', 'Post Create')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />

    <style>
        .image_box {
            border: 1px solid #e9ecef;
            padding: 8px;
            border-radius: 5px;
            vertical-align: top;
            text-align: center;
            max-width: 50%;
        }

        .image {
            max-width: 100%;
            height: 100%;
            margin-top: 10px;
        }
    </style>
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
@endsection



@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Home /</span> Post Add
    </h4>

    <!-- Form with Tabs -->
    <div class="row">
        <div class="col">
            <div class="nav-align-top mb-3">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <button
                            class="nav-link  {{ $post && $post->post_type == 'image' ? 'active' : '' }} {{ $post == null ? 'active' : '' }}"
                            data-bs-toggle="tab" data-bs-target="#form-tabs-image" role="tab"
                            aria-selected="true">Image</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link {{ $post && $post->post_type == 'text' ? 'active' : '' }} "
                            data-bs-toggle="tab" data-bs-target="#form-tabs-text" role="tab"
                            aria-selected="false">Text</button>
                    </li>
                </ul>
                <div class="tab-content">

                    <!-- Image Info -->
                    <div class="tab-pane fade {{ $post && $post->post_type == 'image' ? 'active show' : '' }} {{ $post == null ? 'active show' : '' }}"
                        id="form-tabs-image" role="tabpanel">
                        @if ($post == null)
                            <form action="{{ route('post.store') }}" method="post" enctype="multipart/form-data">
                            @else
                                <form action="{{ route('post.update', $post->id) }}" method="post"
                                    enctype="multipart/form-data">
                        @endif
                        @csrf

                        <input type="hidden" name="post_type" value="image">
                        <div class="mb-3">
                            <label class="form-label" for="bs-validation-name">Image Name</label>
                            <input type="text" name='name' value="{{ $post ? $post->name : '' }}"
                                class="form-control" id="bs-validation-name" placeholder="Image Name" value=""
                                required />
                            <div class="valid-feedback"> Looks good! </div>
                            <div class="invalid-feedback"> Please enter your name. </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="bs-validation-upload-file">Image</label>
                            <input type="file" name='image' class="form-control" id="bs-validation-upload-file" />
                        </div>

                        @if (isset($post) && $post != null && $post->image != '')
                            <div class="mb-3 image_box">
                                <input type="hidden" name="hidden_image" class="hidden_image" value="{{ $post->image }}">
                                <img class="image" src="{{ asset('' . $post->image . '') }}" alt="image" />
                            </div>
                        @endif

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" value="1" class="form-check-input"
                                    id="bs-validation-checkbox" />
                                <label class="form-check-label" for="bs-validation-checkbox">Active</label>
                                <div class="invalid-feedback"> You must agree before submitting. </div>
                            </div>
                        </div>


                        <div class="row justify-content-start">
                            <div class="col-sm-9">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                                <a href="{{ route('post.list') }}" class="btn btn-label-secondary">Cancel</a>
                            </div>
                        </div>

                        </form>
                    </div>

                    <!-- Text Details -->
                    <div class="tab-pane fade {{ $post && $post->post_type == 'text' ? 'active show' : '' }} "
                        id="form-tabs-text" role="tabpanel">
                        @if ($post == null)
                            <form action="{{ route('post.store') }}" method="post" id="TextForm">
                            @else
                                <form action="{{ route('post.update', $post->id) }}" id="TextForm" method="post">
                        @endif
                        <input type="hidden" name="post_type" value="text">

                        <div class="mb-3">

                            <div id="text-editor">
                                @if ($post && $post->text != '')
                                    {!! $post->text !!}
                                @else
                                    <h6>Quill Rich Text Editor</h6>
                                    <p> Cupcake ipsum dolor sit amet. Halvah cheesecake chocolate bar gummi
                                        bears cupcake. Pie macaroon bear claw. Soufflé I love candy canes I love
                                        cotton candy I love. </p>
                                @endif
                            </div>
                            <div class="valid-feedback"> Looks good! </div>
                            <div class="invalid-feedback"> Please enter your name. </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_active"
                                    {{ $post && $post->is_active == true ? 'checked' : '' }} value="1"
                                    class="form-check-input" name="is_active" id="is_active" />
                                <label class="form-check-label" for="is_active">Active</label>
                                <div class="invalid-feedback"> You must agree before submitting. </div>
                            </div>
                        </div>


                        <div class="row justify-content-start">
                            <div class="col-sm-9">
                                <button type="button"
                                    class="btn btn-primary me-sm-3 me-1 img_form_submit_btn">Submit</button>
                                <a href="{{ route('post.list') }}" class="btn btn-label-secondary">Cancel</a>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            const fullToolbar = [
                [{
                        font: []
                    },
                    {
                        size: []
                    }
                ],
                ['bold', 'italic', 'underline', 'strike'],
                [{
                        color: []
                    },
                    {
                        background: []
                    }
                ],
                [{
                        script: 'super'
                    },
                    {
                        script: 'sub'
                    }
                ],
                [{
                        header: '1'
                    },
                    {
                        header: '2'
                    },
                    'blockquote',
                    'code-block'
                ],
                [{
                        list: 'ordered'
                    },
                    {
                        list: 'bullet'
                    },
                    {
                        indent: '-1'
                    },
                    {
                        indent: '+1'
                    }
                ],
                [{
                    direction: 'rtl'
                }],
                ['link', 'image', 'video', 'formula'],
                ['clean']
            ];
            const fullEditor = new Quill('#text-editor', {
                bounds: '#text-editor',
                placeholder: 'Type Something...',
                modules: {
                    formula: true,
                    toolbar: fullToolbar
                },
                theme: 'snow'
            });

            $(document).on("click", ".img_form_submit_btn", function() {
                let url = $("#TextForm").attr('action');
                let is_active = $("#is_active").prop('checked') ? 1 : 0;
                var editorContent = fullEditor.root.innerHTML; // Get Quill editor content as HTML

                // Send an AJAX request to store the Quill editor content
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        text: editorContent,
                        post_type: 'text',
                        is_active: is_active,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show a toaster message using a library like Toastr
                            toastr.success(response.message);

                            // Redirect to the 'post.list' route after a delay
                            setTimeout(function() {
                                window.location.href = "{{ route('post.list') }}";
                            }, 3000); // Redirect after 3 seconds (adjust the delay as needed)
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error('There was an error:', error);
                    }
                });
            });
        });
    </script>
@endsection
