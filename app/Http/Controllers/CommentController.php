<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Traits\AjaxResponse;

class CommentController extends Controller
{
  use AjaxResponse;

  /**
   * The comments function is validates a request, retrieves a post and its comments, and returns a
   * view with the post and comments.
   */
  public function comments(Request $request)
  {
    $request->validate([
      'id'   => 'required|string|exists:posts',
    ]);

    $post = Post::findOrFail($request->id);

    $comments = $post->comments;

    return view('components.commentModal', compact('post', 'comments'));
  }

  /**
   * The commentStore function is validates and stores a comment, either creating a new comment or
   * updating an existing one, and then returns a view with the post and its comments.
   */
  public function commentStore(Request $request)
  {

    $request->validate([
      'post_id'           => 'required|string|exists:posts,id',
      'user_id'           => 'required|string|exists:users,id',
      'comment_text'      => 'required|string',
      'edited_comment_id' => 'nullable|string|exists:comments,id',
    ]);

    $commentData = [
      'post_id'       => $request->post_id,
      'user_id'       => $request->user_id,
      'comment_text'  => $request->comment_text,
      'is_active'     => $request->is_active ?? false,
    ];

    // Check if an edited_comment_id is present
    if ($request->has('edited_comment_id') && isset($request->edited_comment_id)) {
      // Update the existing comment
      $comment = Comment::find($request->edited_comment_id);
      if ($comment) {
        $comment->update($commentData);
      }
    } else {
      // Create a new comment
      Comment::create($commentData);
    }

    $post = Post::findOrFail($request->post_id);

    $comments = $post->comments;

    return view('components.commentModal', compact('post', 'comments'));
  }

  /**
   * The commentDestroy function in PHP is used to permanently delete a comment based on its ID.
   */
  public function commentDestroy(Request $request)
  {
    $request->validate([
      'comment_id'        => 'required|string|exists:comments,id',
    ]);

    $comment = Comment::findOrFail($request->comment_id);
    $comment->forceDelete();
  }
}
