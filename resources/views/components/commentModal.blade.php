<!-- Modal -->
@if (isset($post))
    <div class="modal fade" id="commentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentModalTitle">Comments</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="commentForm" method="post">
                        @csrf
                        <h5>{!! $post->post_type == 'image' ? $post->name : $post->text !!}</h5>

                        @if (isset($post) && $post->post_type == 'image' && $post != null && $post->image != '')
                            <div class="mb-3 image_box">
                                <img class="image" src="{{ asset('' . $post->image . '') }}" alt="image" />
                            </div>
                        @endif

                        <input type="hidden" name="post_id" class="" value="{{ $post->id }}">
                        <input type="hidden" name="user_id" class="" value="{{ Auth::user()->id }}">

                        <div class="divider">
                            <div class="divider-text">Add Comment</div>
                        </div>

                        <div class="row">
                            <div class="col mb-3">
                                <textarea class="form-control" placeholder="Write your comment here " name="comment_text"
                                    id="exampleFormControlTextarea1" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="accordion mt-3 accordion-header-primary" id="accordionStyle1">
                            <div class="accordion-item card">
                                <h2 class="accordion-header">
                                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#accordionStyle1-2" aria-expanded="false">
                                        Comments List
                                    </button>
                                </h2>
                                <div id="accordionStyle1-2" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionStyle1">
                                    <div class="accordion-body">
                                        <div class="comments-list">
                                            @foreach ($comments as $comment)
                                                <div class="media mb-4 d-flex align-items-start">
                                                    <div class="avatar avatar-sm me-2 flex-shrink-0 mt-1">
                                                        @if ($comment->user->profile)
                                                            <img src="{{ asset($comment->user->profile) }}"
                                                                alt="Avatar" class="rounded-circle">
                                                        @else
                                                            <img src='https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}&size=30&background=696cff&color=FFFFFF'
                                                                class='avatar-initial rounded-circle'>
                                                        @endif
                                                    </div>
                                                    <div class="media-body">
                                                        <p class="mb-0"> {{ $comment->comment_text }}</p>
                                                        <small class="fw-bolder" class="text-muted">{{ $comment->user->name }} |
                                                            <span class="fw-normal">{{ $comment->created_at->diffForHumans() }}</span></small>
                                                    </div>
                                                    <div class="ms-auto">

                                                        @if ($comment->user_id == Auth::user()->id || $post->created_by == Auth::user()->id)
                                                            <button type="button" data-id="{{ $comment->id }}"
                                                                class="btn btn-icon text-primary editComment">
                                                                <span class="tf-icons bx bx-edit"></span>
                                                            </button>
                                                        @endif
                                                        @if ($post->created_by == Auth::user()->id)
                                                            <button type="button" data-id="{{ $comment->id }}"
                                                                class="btn btn-icon text-danger deleteComment">
                                                                <span class="tf-icons bx bx-trash"></span>
                                                            </button>
                                                        @endif

                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-label-secondary resetCommentForm">Reset</button>
                    <button type="button" class="btn btn-primary commentStoreBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
@endif
