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
                        <div class="dropdown d-none d-sm-block">
                            <button class="btn p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                type="button" data-bs-auto-close="outside" id="sharedList">
                                <i class='text-primary bx bx-share-alt'></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end w-px-300">
                                <form action="{{ route('post.sharePosts') }}" method="post" class="p-4 sharePostsForm">
                                    @csrf
                                    <input type="hidden" name="postId" value="{{ $post->id }}">
                                    <div class="mb-2">
                                        <select id="selectpickerLiveSearch selectpickerSelectDeselect"
                                            name="sharedUsersIds[]" class="selectpicker w-100" data-style="btn-default"
                                            data-live-search="true" multiple data-actions-box="false" data-size="5">
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
                                    <div class="d-grid gap-2 col-lg-6 mx-auto">
                                        <button type="button"
                                            class="btn rounded-pill btn-outline-primary shareButton">Share</button>
                                    </div>
                                </form>
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
                        <div class="dropdown d-none d-sm-block">
                            <button class="btn p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                type="button" data-bs-auto-close="outside" id="sharedList">
                                <i class='text-primary bx bx-share-alt'></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end w-px-300">
                                <form action="{{ route('post.sharePosts') }}" method="post"
                                    class="p-4 sharePostsForm">
                                    @csrf
                                    <input type="hidden" name="postId" value="{{ $post->id }}">
                                    <div class="mb-2">
                                        <select id="selectpickerLiveSearch selectpickerSelectDeselect"
                                            name="sharedUsersIds[]" class="selectpicker w-100" data-style="btn-default"
                                            data-live-search="true" multiple data-actions-box="false" data-size="5">
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
                                    <div class="d-grid gap-2 col-lg-6 mx-auto">
                                        <button type="button"
                                            class="btn rounded-pill btn-outline-primary shareButton">Share</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
