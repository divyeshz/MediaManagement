<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\AjaxResponse;
use App\Traits\FileUpload;
use App\Traits\PaginateSortStatusTrait;

class UserController extends Controller
{

  use AjaxResponse, FileUpload, PaginateSortStatusTrait;
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {

    $query = User::query();

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


    $users = $this->PSS($query, $request);

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

      // Remove Old Image
      $url = $user->profile;
      $thumbnailUrl = str_replace('/profile/', '/profile/thumbnail/', $url);
      $this->unlink($url, $thumbnailUrl);

      // Add New Image
      $file = $request->file('profile');
      $filename = $this->createFilename($file);
      $this->createThumbnail($file, $filename, $user->id, 'profile/thumbnail');
      $profile = $this->createImage($file, $filename, $user->id, 'profile');
    } elseif ($user->profile != "" && $request->hidden_profile == "") {

      // Remove Old Image
      $url = $user->profile;
      $thumbnailUrl = str_replace('/profile/', '/profile/thumbnail/', $url);
      $this->unlink($url, $thumbnailUrl);
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
    $user = User::findOrFail($id);
    $url = $user->profile;
    if ($url) {
      $directory = dirname($url);
      $this->deleteDirectory($directory);
    }
    $user->forceDelete();
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
