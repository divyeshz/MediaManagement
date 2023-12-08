<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\FileUpload;

class PostController extends Controller
{
  use FileUpload;
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $users = User::where('id', '<>', Auth::user()->id)->get();
    $query = Post::query();

    // Apply search filters for 'name', 'email', and 'gender' columns
    if ($request->has('search')) {
      $searchValue = $request->search;

      // Add search conditions for 'name', 'email', and 'gender' columns
      $query->where(function ($query) use ($searchValue) {
        $query->where('name', 'like', '%' . $searchValue . '%')
          ->orWhere('text', 'like', '%' . $searchValue . '%');
      });
    }

    // Apply filters 'status' columns
    if ($request->has("status") && $request->filled('status')) {
      $query->where('is_active', $request->status);
    }

    if ($request->has("sharedUserIds") && $request->filled('sharedUserIds')) {
      $query->whereIn('created_by', $request->sharedUserIds);
    }

    if ($request->has("post_type") && $request->filled('post_type')) {
      $query->where('post_type', $request->post_type);
    }

    $appendable = [];

    // Create appendable array for non-empty query parameters except 'page' and '_token'
    foreach ($request->except(['page', '_token', 'is_ajax']) as $key => $value) {
      if (!empty($value)) {
        $appendable[$key] = $value;
      }
    }

    $posts = $query->orderBy('post_type', 'desc')->paginate(5)->appends($appendable);
    if ($request->is_ajax == true) {
      return view('_partials.post_list', compact('posts', "users"));
    }
    return view("post.list", compact("posts", "users"));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $post = null;
    return view("post.addEdit", compact("post"));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'post_type'       => 'required|string',
      'name'            => 'required_if:post_type,image|string',
      'image'           => 'required_if:post_type,image|mimes:png,jpg,jpeg|max:5120',
      'text'            => 'required_if:post_type,text|string',
      'is_active'       => 'nullable|boolean',
    ]);

    $post = Post::create([
      'name'          => $request->name,
      'post_type'     => $request->post_type,
      'text'          => $request->text,
      'is_active'     => $request->is_active ?? false,
    ]);

    if ($request->hasfile('image')) {
      $file     = $request->file('image');
      $filename = $this->createFilename($file);
      $this->createThumbnail($file, $filename, auth()->id(), 'post/thumbnail');
      $image    = $this->createImage($file, $filename, auth()->id(), 'post');


      // store the data
      $post->update(['image' => $image]);

      return redirect()->route('post.list')->with('success', 'Insert SuccessFully!!!');
    }

    return response()->json(['success' => true, 'message' => 'Insert Successfully']);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    $post = Post::findOrFail($id);
    return view('post.addEdit', compact('post'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {

    $request->validate([
      'post_type'       => 'required|string',
      'name'            => 'required_if:post_type,image|string',
      'image'           => 'mimes:png,jpg,jpeg|max:5120',
      'hidden_image'    => 'nullable|string',
      'text'            => 'required_if:post_type,text|string',
      'is_active'       => 'nullable|boolean',
    ]);

    $post = Post::findOrFail($id);

    if ($request->get('post_type') == 'image') {

      $image = null;
      if ($request->hasfile('image')) {

        // Remove Old Image
        $url = $post->image;
        $thumbnailUrl = str_replace('/post/', '/post/thumbnail/', $url);
        $this->unlink($url, $thumbnailUrl);

        // Add New Image
        $file = $request->file('image');
        $filename = $this->createFilename($file);
        $this->createThumbnail($file, $filename, auth()->id(), 'post/thumbnail');
        $image = $this->createImage($file, $filename, auth()->id(), 'post');
      } else {
        $image = $request->hidden_image;
      }

      // store the data
      $post->update([
        'post_type'     => $request->post_type,
        'image'         => $image,
        'is_active'     => $request->is_active ?? false,
      ]);
    }

    if ($request->get('post_type') == 'text') {

      $post->update([
        'post_type'     => $request->post_type,
        'text'          => $request->text,
        'is_active'     => $request->is_active ?? false,
      ]);
      return response()->json(['success' => true, 'message' => 'Update Successfully']);
    }

    return redirect()->route('post.list')->with('success', 'Update SuccessFully!!!');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $post = Post::findOrFail($id);
    $url = $post->image;
    if ($url) {
      $directory = dirname($url);
      $this->deleteDirectory($directory);
    }
    $post->forceDelete();
    return redirect()->route('post.list')->with('success', 'Deleted SuccessFully!!!');
  }

  /* function to share video */
  public function sharePosts(Request $request)
  {
    $request->validate([
      'postId'          => 'required|string|exists:posts,id',
      'sharedUsersIds'  => 'array|nullable',
    ]);

    $post = Post::findOrFail($request->postId);
    if ($request->sharedUsersIds) {

      // Delete Old users of requested video from the database
      foreach ($post->users as $key => $userId) {
        $post->users()->detach($post->users[$key]);
      }

      // Add New Users of requested video in the database
      foreach ($request->sharedUsersIds as $key => $userId) {
        $post->users()->attach($request->sharedUsersIds[$key]);
      }
    } else {
      // Delete Old users of requested video from the database
      $post->users()->detach();
    }
    return redirect()->route('post.list')->with('success', 'Posts Shared successfully');
  }

  /* Render shared Posts */
  public function sharePostsList(Request $request)
  {
    $users = User::where('id', '<>', Auth::user()->id)->get();
    $appendable = [];

    // Create appendable array for non-empty query parameters except 'page' and '_token'
    foreach ($request->except(['page', '_token', 'is_ajax']) as $key => $value) {
      if (!empty($value)) {
        $appendable[$key] = $value;
      }
    }

    // Apply search filters for 'name', 'email', and 'gender' columns
    $sharedPosts = auth()->user()->posts()->where(function ($query) use ($request) {
      if ($request->search != null) {
        $query->where('name', 'Like', '%' . $request->search . '%')->orWhere('text', 'Like', '%' . $request->search . '%');
      }
      if ($request->has("status") && $request->filled('status')) {
        $query->where('posts.is_active', $request->status);
      }
      if ($request->has("sharedUserIds") && $request->filled('sharedUserIds')) {
        $query->whereIn('created_by', $request->sharedUserIds);
      }
      if ($request->has("post_type") && $request->filled('post_type')) {
        $query->where('post_type', $request->post_type);
      }
    })->paginate(10)->appends($appendable);

    if ($request->is_ajax == true) {
      return view('_partials.shared_post_list', compact('sharedPosts', 'users'));
    }
    return view("post.sharedPosts", compact("sharedPosts", 'users'));
  }
}
