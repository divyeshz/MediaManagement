<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
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

    $appendable = [];

    // Create appendable array for non-empty query parameters except 'page' and '_token'
    foreach ($request->except(['page', '_token', 'is_ajax']) as $key => $value) {
      if (!empty($value)) {
        $appendable[$key] = $value;
      }
    }

    $posts = $query->orderBy('post_type', 'desc')->paginate(5)->appends($appendable);
    if ($request->is_ajax == true) {
      return view('_partials.post_list', compact('posts'));
    }
    return view("post.list", compact("posts"));
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
    if ($request->get('post_type') == 'image') {

      $post = Post::create([
        'name'  => $request->name,
      ]);

      $image = null;
      if ($request->hasfile('image')) {

        $file = $request->file('image');

        // Create thumbnail of image
        $thumbnail = Image::make($file)->resize(200, 200, function ($constraint) {
          $constraint->aspectRatio();
        });

        $name = $file->getClientOriginalName();
        $filename = Carbon::now()->format('dmY_His') . '_' . trim(pathinfo($name, PATHINFO_FILENAME)) . '.' . Str::lower(pathinfo($name, PATHINFO_EXTENSION));

        // Create the thumbnail directory if it doesn't exist
        $thumbnailDirectory = 'users/' . auth()->id() . '/post/thumbnail/';

        if (!file_exists($thumbnailDirectory)) {
          mkdir($thumbnailDirectory, 0777, true);
        }

        // Move the original file to the post's directory
        $image = $file->move('users/' . auth()->id() . '/post/', $filename);

        // Save the thumbnail to the specified path
        $thumbnail->save('users/' . auth()->id() . '/post/thumbnail/' . $filename);
      }

      // store the data
      $post->update([
        'post_type'     => $request->post_type,
        'image'         => $image,
        'is_active'     => $request->is_active ?? false,
      ]);
    }

    if ($request->get('post_type') == 'text') {

      $post = Post::create([
        'post_type'     => $request->post_type,
        'text'          => $request->text,
        'is_active'     => $request->is_active ?? false,
      ]);

      return response()->json(['success' => true, 'message' => 'Insert Successfully']);
    }

    return redirect()->route('post.list')->with('success', 'Insert SuccessFully!!!');
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
        $file = $request->file('image');

        // Create thumbnail of image
        $thumbnail = Image::make($file)->resize(300, 185, function ($constraint) {
          $constraint->aspectRatio();
        });

        $name = $file->getClientOriginalName();
        $filename = Carbon::now()->format('dmY_His') . '_' . trim(pathinfo($name, PATHINFO_FILENAME)) . '.' . Str::lower(pathinfo($name, PATHINFO_EXTENSION));

        // Create the thumbnail directory if it doesn't exist
        $thumbnailDirectory = 'users/' . auth()->id() . '/post/thumbnail/';

        if (!file_exists($thumbnailDirectory)) {
          mkdir($thumbnailDirectory, 0777, true);
        }
        // Move the original file to the post's directory
        $image = $file->move('users/' . auth()->id() . '/post/', $filename);

        // Save the thumbnail to the specified path
        $thumbnail->save('users/' . auth()->id() . '/post/thumbnail/' . $filename);

        $url = $post->image;
        $thumbnailUrl = str_replace('/post/', '/post/thumbnail/', $url);

        // Delete file if exist
        if (File::exists($url) && $url != "") {
          unlink($thumbnailUrl);
          unlink($url);
        }
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
    $delete = Post::findOrFail($id);
    $delete->forceDelete();
    return redirect()->route('post.list')->with('success', 'Deleted SuccessFully!!!');
  }
}
