@foreach ($posts as $post)
    @if ($post->post_type == 'image')
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $post->name }}</h5>
                    <img data-id="{{ $post->id }}" class="img-fluid d-flex mx-auto my-4 rounded w-100 commentModalBtn"
                        src="{{ asset('' . str_replace('/post/', '/post/thumbnail/', $post->image) . '') }}"
                        alt="Card image cap" />
                    <div class="d-flex align-items-center justify-content-between mt-3">
                        <div class="card-actions">
                            <form action="{{ route('post.destroy', $post->id) }}" class="delete-form" method="POST">
                                @csrf
                                <a href="{{ route('post.edit', $post->id) }}" class=""><i
                                        class="bx bx-edit me-1"></i></a>
                                <a href="javascript:;" class="delete"><i class="bx bx-trash me-1 text-danger"></i></a>
                                <a href="javascript:;" data-id="{{ $post->id }}" class="commentModalBtn"
                                    class="me-1 text-warning"><i class="bx bx-chat me-1"></i></a>
                            </form>
                        </div>
                        <div class="dropdown d-none d-sm-block">
                            <form action="{{ route('post.sharePosts') }}" method="post" class=" sharePostsForm">
                                @csrf
                                <input type="hidden" name="postId" value="{{ $post->id }}">
                                <div class="mb-2">
                                    <select id="selectpickerLiveSearch selectpickerSelectDeselect"
                                        name="sharedUsersIds[]" class="selectpicker w-150 sharedUsersIds"
                                        data-style="btn-default" data-live-search="true" multiple
                                        data-actions-box="false" data-size="5" placeholder="Share To">
                                        @foreach ($users as $user)
                                            @if ($user->profile == '')
                                                <option value="{{ $user->id }}"
                                                    {{ $user->posts->contains($post->id) ? 'selected' : '' }}
                                                    data-content="<img src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=30&background=696cff&color=FFFFFF' class='avatar-initial rounded-circle'>&nbsp;{{ $user->name }}">
                                                    {{ $user->name }}</option>
                                            @else
                                                <option value="{{ $user->id }}"
                                                    {{ $user->posts->contains($post->id) ? 'selected' : '' }}
                                                    data-content="<img src='{{ asset($user->profile) }}' class='rounded-circle' width='30' height='30'>&nbsp;{{ $user->name }}">
                                                    {{ $user->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end align-items-center flex-wrap mt-2 pt-1">
                        <div class="avatar-group d-flex align-items-center assigned-avatar">
                            @foreach ($post->users as $user)
                                <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-placement="top"
                                    aria-label="Helena" data-bs-original-title="{{ $user->name }}">
                                    @if ($user->profile)
                                        <img src="{{ asset($user->profile) }}" alt="Avatar"
                                            class="rounded-circle  pull-up">
                                    @else
                                        <img src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=30&background=696cff&color=FFFFFF'
                                            class='avatar-initial rounded-circle'>
                                    @endif
                                </div>
                            @endforeach
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
                    <div class="d-flex align-items-center justify-content-between mt-3">
                        <div class="card-actions">
                            <form action="{{ route('post.destroy', $post->id) }}" class="delete-form" method="POST">
                                @csrf
                                <a href="{{ route('post.edit', $post->id) }}" class=""><i
                                        class="bx bx-edit me-1"></i></a>
                                <a href="javascript:;" class="delete"><i class="bx bx-trash me-1 text-danger"></i></a>
                                <a href="javascript:;" data-id="{{ $post->id }}" class="commentModalBtn"
                                    class="me-1 text-warning"><i class="bx bx-chat me-1"></i></a>
                            </form>
                        </div>
                        <div class="dropdown d-none d-sm-block">
                            <form action="{{ route('post.sharePosts') }}" method="post" class="sharePostsForm">
                                @csrf
                                <input type="hidden" name="postId" value="{{ $post->id }}">
                                <div class="mb-2">
                                    <select id="selectpickerLiveSearch selectpickerSelectDeselect"
                                        name="sharedUsersIds[]" class="sharedUsersIds selectpicker w-150"
                                        data-style="btn-default" data-live-search="true" multiple
                                        data-actions-box="false" data-size="5" placeholder="Share To">
                                        @foreach ($users as $user)
                                            @if ($user->profile == '')
                                                <option value="{{ $user->id }}"
                                                    {{ $user->posts->contains($post->id) ? 'selected' : '' }}
                                                    data-content="<img src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=30&background=696cff&color=FFFFFF' class='avatar-initial rounded-circle'>&nbsp;{{ $user->name }}">
                                                    {{ $user->name }}</option>
                                            @else
                                                <option value="{{ $user->id }}"
                                                    {{ $user->posts->contains($post->id) ? 'selected' : '' }}
                                                    data-content="<img src='{{ asset($user->profile) }}' class='rounded-circle' width='30' height='30'>&nbsp;{{ $user->name }}">
                                                    {{ $user->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end align-items-center flex-wrap mt-2 pt-1">
                        <div class="avatar-group d-flex align-items-center assigned-avatar">
                            @foreach ($post->users as $user)
                                <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-placement="top"
                                    aria-label="Helena" data-bs-original-title="{{ $user->name }}">
                                    @if ($user->profile)
                                        <img src="{{ asset($user->profile) }}" alt="Avatar"
                                            class="rounded-circle  pull-up">
                                    @else
                                        <img src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=30&background=696cff&color=FFFFFF'
                                            class='avatar-initial rounded-circle'>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
