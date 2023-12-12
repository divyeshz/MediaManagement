<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class Googlecontroller extends Controller
{
  /**
   * The function redirects the user to Google for authentication using the Socialite package in PHP.
   */
  public function redirectToGoogle()
  {
    return Socialite::driver('google')->redirect();
  }

  /**
   * The function handles the callback from Google login, checks if the user already exists in the
   * database, and either logs them in or creates a new user and logs them in.
   */
  public function handleGoogleCallback()
  {
    try {

      $user = Socialite::driver('google')->user();

      $finduser = User::where('social_id', $user->id)->first();
      if ($finduser) {

        Auth::login($finduser);

        return redirect()->route('home');
      } else {
        $newUser = User::updateOrCreate(['email' => $user->email], [
          'name' => $user->name,
          'social_id' => $user->id,
          'social_type' => 'google',
          'profile' => $user->avatar,
        ]);

        Auth::login($newUser);

        return redirect()->route('home');
      }
    } catch (Exception $e) {
      return redirect()->back()->with('status', $e->getMessage());
    }
  }
}
