<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $users = User::all();
    return view('user.list', compact('users'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    $users = User::findOrFail($id);
    return view('user.edit', compact('users'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {

    $request->validate([
      'name' => 'required|string',
      'phone' => 'nullable|min:10|max:10',
      'email' => 'required|email',
      'profile' => 'mimes:png,jpg,jpeg|max:5120',
      'hidden_profile' => 'string',
      'gender' => 'required|string',
      'is_active' => 'boolean',
    ]);

    $user = User::findOrFail($id);

    $profile = "";
    if ($request->hasfile('profile')) {

      $url = $user->profile;
      $filePath = public_path(parse_url($url, PHP_URL_PATH));
      $thumbnailFile = basename(public_path(parse_url($url, PHP_URL_PATH)));

      // Delete file if exist
      if (File::exists($filePath) && $url != "") {
        unlink('users/' . $user->id . '/profile/thumbnail/' . $thumbnailFile);
        unlink($filePath);
      }

      $file = $request->file('profile');

      // Create thumbnail of image
      $thumbnail = Image::make($file)->resize(200, 200, function ($constraint) {
        $constraint->aspectRatio();
      });

      $name = $file->getClientOriginalName();
      $filename = Carbon::now()->format('dmY_His') . '_' . trim(pathinfo($name, PATHINFO_FILENAME)) . '.' . Str::lower(pathinfo($name, PATHINFO_EXTENSION));

      // Move the original file to the user's directory
      $profile .= asset($file->move('users/' . $user->id . '/profile/', $filename));

      // Create the thumbnail directory if it doesn't exist
      $thumbnailDirectory = 'users/' . $user->id . '/profile/thumbnail/';

      if (!file_exists($thumbnailDirectory)) {
        mkdir($thumbnailDirectory, 0777, true);
      }
      // Save the thumbnail to the specified path
      $thumbnail->save('users/' . $user->id . '/profile/thumbnail/' . $filename);
    } elseif ($user->profile != "" && $request->hidden_profile == "") {
      $url = $user->profile;
      $filePath = public_path(parse_url($url, PHP_URL_PATH));
      $thumbnailFile = basename(public_path(parse_url($url, PHP_URL_PATH)));

      // Delete file if exist
      if (File::exists($filePath)) {
        unlink('users/' . $user->id . '/profile/thumbnail/' . $thumbnailFile);
        unlink($filePath);
      }
    } else {
      $profile .= $request->hidden_profile;
    }

    // Update user details
    $user->update([
      'name' => $request->name,
      'email' => $request->email,
      'phone' => $request->phone,
      'profile' => $profile,
      'gender' => $request->gender,
      'is_active' => $request->is_active ?? false,
    ]);

    return redirect()->route('user.list')->with('success', 'Updated SuccessFully!!!');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $delete = User::findOrFail($id);
    if ($delete) {
      $delete->forceDelete();
      return redirect()->route('user.list')->with('success', 'Deleted SuccessFully!!!');
    } else {
      return redirect()->route('user.list')->with('error', 'Deleted failed!!!');
    }
  }
}
