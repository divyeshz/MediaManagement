<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Traits\AjaxResponse;

class UserController extends Controller
{

  use AjaxResponse;
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {

    $query = User::query();

    $sortableColumns = ['name', 'email', 'gender']; // Define columns that can be sorted

    // Sorting logic
    if ($request->has('sort_by') && in_array($request->sort_by, $sortableColumns)) {
      $sortDirection = $request->has('sort_dir') && $request->sort_dir === 'desc' ? 'desc' : 'asc';
      $query->orderBy($request->sort_by, $sortDirection);
    }

    // Apply search filters for 'name', 'email', and 'gender' columns
    if ($request->has('search')) {
      $searchValue = $request->search;

      // Add search conditions for 'name', 'email', and 'gender' columns
      $query->where(function ($query) use ($searchValue) {
        $query->where('name', 'like', '%' . $searchValue . '%')
          ->orWhere('email', 'like', '%' . $searchValue . '%')
          ->orWhere('gender', 'like', '%' . $searchValue . '%');
      });
    }

    // Apply filters 'gender' columns
    if ($request->has("gender") && $request->filled('gender')) {
      $query->where('gender', $request->gender);
    }

    // Apply filters 'status' columns
    if ($request->has("status") && $request->filled('status')) {
      $query->where('is_active', $request->status);
    }

    // Set default per page value to 10 if 'per_page' parameter is not present or invalid
    $perPage = $request->filled('per_page') ? intval($request->per_page) : 10;

    $appendable = [];

    // Create appendable array for non-empty query parameters except 'page' and '_token'
    foreach ($request->except(['page', '_token', 'is_ajax']) as $key => $value) {
      if (!empty($value)) {
        $appendable[$key] = $value;
      }
    }

    $users = $query->paginate($perPage)->appends($appendable);
    if ($request->is_ajax == true) {
      return view('_partials.user_list', compact('users'));
    }
    return view('user.list', compact('users'));
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
      'name'            => 'required|string',
      'phone'           => 'nullable|min:10|max:10',
      'email'           => 'required|email',
      'profile'         => 'mimes:png,jpg,jpeg|max:5120',
      'hidden_profile'  => 'string',
      'gender'          => 'required|string',
      'is_active'       => 'boolean',
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
      'name'          => $request->name,
      'email'         => $request->email,
      'phone'         => $request->phone,
      'profile'       => $profile,
      'gender'        => $request->gender,
      'is_active'     => $request->is_active ?? false,
    ]);

    return redirect()->route('user.list')->with('success', 'Updated SuccessFully!!!');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $delete = User::findOrFail($id);
    $delete->forceDelete();
    return redirect()->route('user.list')->with('success', 'Deleted SuccessFully!!!');
  }

  /* Chnage active status */
  public function status(Request $request)
  {
    $request->validate([
      'id'        => 'required',
      'is_active' => 'numeric'
    ]);

    $status = User::where('id', $request->id)->update([
      'is_active'     => $request->is_active,
    ]);
    if ($status) {
      return $this->success(200, 'Status Updated SuccessFully!!!');
    } else {
      return $this->error(400, 'Status Updated Failed!!!');
    }
  }

  /* Profile page */
  public function profile()
  {
    return view('profile.profile');
  }
}
