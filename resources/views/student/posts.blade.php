@extends('layouts.stu')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">Anonymous Posts</h2>

    <!-- Display success message -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Post Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('student.posts.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <textarea name="content" class="form-control" rows="3" placeholder="Write something anonymously..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Post</button>
            </form>
        </div>
    </div>

    <!-- Display Posts -->
    @foreach($posts as $post)
        <div class="card mb-3">
            <div class="card-body">
                <p class="card-text">{{ $post->content }}</p>
                <small class="text-muted">Posted {{ $post->created_at->diffForHumans() }}</small>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <!-- Like Button -->
                <button class="btn btn-sm btn-outline-primary like-btn" data-post-id="{{ $post->id }}">
                    @if($post->likes->where('student_id', Auth::id())->count())
                        Unlike ({{ $post->likes_count }})
                    @else
                        Like ({{ $post->likes_count }})
                    @endif
                </button>

                <!-- View Comments Button -->
                <button type="button" class="btn btn-sm btn-link" data-toggle="modal" data-target="#commentsModal-{{ $post->id }}">
                    View Comments ({{ $post->comments->count() }})
                </button>
            </div>
        </div>

        <!-- Comments Modal -->
        <div class="modal fade" id="commentsModal-{{ $post->id }}" tabindex="-1" role="dialog" aria-labelledby="commentsModalLabel-{{ $post->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="commentsModalLabel-{{ $post->id }}">Comments</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Display Comments -->
                        @if($post->comments->isNotEmpty())
                            @foreach($post->comments as $comment)
                                <div class="mb-2">
                                    <p class="mb-1"><strong>Anonymous:</strong> {{ $comment->content }}</p>
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                                <hr>
                            @endforeach
                        @else
                            <p class="text-muted">No comments yet.</p>
                        @endif

                        <!-- Comment Form -->
                        <form action="{{ route('student.posts.comment', $post->id) }}" method="POST" class="mt-3">
                            @csrf
                            <div class="form-group">
                                <input type="text" name="comment" class="form-control" placeholder="Add a comment..." required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Comment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Include jQuery if not already included -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script>
    $(document).ready(function() {
        $('.like-btn').click(function() {
            var postId = $(this).data('post-id');
            var button = $(this);

            $.ajax({
                url: '/student/posts/' + postId + '/like',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        if (response.liked) {
                            button.removeClass('btn-outline-primary').addClass('btn-danger');
                            button.text('Unlike (' + response.likes_count + ')');
                        } else {
                            button.removeClass('btn-danger').addClass('btn-outline-primary');
                            button.text('Like (' + response.likes_count + ')');
                        }
                    }
                }
            });
        });
    });
</script>

<style>
    .container {
        max-width: 700px;
    }
</style>
@endsection
