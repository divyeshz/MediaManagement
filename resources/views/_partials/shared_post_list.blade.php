@foreach ($sharedPosts as $post)
    @if ($post->post_type == 'image')
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $post->name }}</h5>
                    <img data-id="{{ $post->id }}" class="img-fluid d-flex mx-auto my-4 rounded w-100 commentModalBtn"
                        src="{{ asset('' . str_replace('/post/', '/post/thumbnail/', $post->image) . '') }}"
                        alt="Card image cap" />
                    <div class="d-flex align-items-center justify-content-between flex-wrap mt-2 pt-1">
                        <div class="card-actions">
                            <a href="javascript:;" data-id="{{ $post->id }}" class="commentModalBtn"
                                class="me-1 text-warning"><i class="bx bx-chat me-1"></i></a>
                        </div>
                        <div class="avatar-group d-flex align-items-center assigned-avatar">
                            @if ($post->owner->profile)
                                <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-placement="top"
                                    aria-label="{{ $post->owner->name }}"
                                    data-bs-original-title="{{ $post->owner->name }}">
                                    <img src="{{ asset($post->owner->profile) }}" alt="Avatar"
                                        class="rounded-circle  pull-up">
                                </div>
                            @else
                                <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-placement="top"
                                    aria-label="{{ $post->owner->name }}"
                                    data-bs-original-title="{{ $post->owner->name }}">
                                    <span class="avatar-initial rounded-circle bg-primary">{{ implode('', array_map(fn($word) => strtoupper($word[0]), explode(' ', $post->owner->name))) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($post->post_type == 'text')
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 data-id="{{ $post->id }}" class="commentModalBtn">{!! $post->text !!}</h5>
                    <div class="d-flex justify-content-between align-items-center flex-wrap mt-2 pt-1">
                        <div class="card-actions">
                            <a href="javascript:;" data-id="{{ $post->id }}" class="commentModalBtn"
                                class="me-1 text-warning"><i class="bx bx-chat me-1"></i></a>
                        </div>
                        <div class="avatar-group d-flex align-items-center assigned-avatar">
                            @if ($post->owner->profile)
                                <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-placement="top"
                                    aria-label="{{ $post->owner->name }}"
                                    data-bs-original-title="{{ $post->owner->name }}">
                                    <img src="{{ asset($post->owner->profile) }}" alt="Avatar"
                                        class="rounded-circle  pull-up">
                                </div>
                            @else
                                <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-placement="top"
                                    aria-label="{{ $post->owner->name }}"
                                    data-bs-original-title="{{ $post->owner->name }}">
                                    <span class="avatar-initial rounded-circle bg-primary">{{ implode('', array_map(fn($word) => strtoupper($word[0]), explode(' ', $post->owner->name))) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
