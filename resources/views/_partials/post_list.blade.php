@foreach ($posts as $post)
    @if ($post->post_type == 'image')
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $post->name }}</h5>
                    <img class="img-fluid d-flex mx-auto my-4 rounded w-100"
                        src="{{ asset('' . str_replace('/post/', '/post/thumbnail/', $post->image) . '') }}"
                        alt="Card image cap" />
                    <div class="d-flex align-items-center justify-content-between mt-3">
                        <div class="card-actions">
                            <form action="{{ route('post.destroy', $post->id) }}" class="delete-form" method="POST">
                                @csrf
                                <a href="{{ route('post.edit', $post->id) }}" class=" me-3"><i
                                        class="bx bx-edit me-1"></i></a>
                                <a href="javascript:;" class="delete"><i class="bx bx-trash me-1 text-primary"></i></a>
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
                            <form action="{{ route('post.destroy', $post->id) }}" class="delete-form" method="POST">
                                @csrf
                                <a href="{{ route('post.edit', $post->id) }}" class=" me-3"><i
                                        class="bx bx-edit me-1"></i></a>
                                <a href="javascript:;" class="delete"><i class="bx bx-trash me-1 text-primary"></i></a>
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
