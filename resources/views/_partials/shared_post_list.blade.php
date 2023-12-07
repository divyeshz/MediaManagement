@foreach ($sharedPosts as $post)
    @if ($post->post_type == 'image')
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $post->name }}</h5>
                    <img class="img-fluid d-flex mx-auto my-4 rounded w-100"
                        src="{{ asset('' . str_replace('/post/', '/post/thumbnail/', $post->image) . '') }}"
                        alt="Card image cap" />
                </div>
            </div>
        </div>
    @elseif ($post->post_type == 'text')
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h3>{!! $post->text !!}</h3>
                </div>
            </div>
        </div>
    @endif
@endforeach
