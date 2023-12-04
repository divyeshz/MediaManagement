<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{

  /**
   * The function redirects the user to Facebook for authentication using the Socialite package in PHP.
   */
  public function redirectToFacebook()
  {
    return Socialite::driver('facebook')->redirect();
  }

  /**
   * The function handles the callback from Facebook login, checks if the user already exists in the
   * database, and either logs them in or creates a new user and logs them in.
   */
  public function handleFacebookCallback()
  {
    try {

      $user = Socialite::driver('facebook')->user();

      $finduser = User::where('social_id', $user->id)->first();

      if ($finduser) {

        Auth::login($finduser);

        return redirect()->route('home');

      } else {
        $newUser = User::updateOrCreate(['email' => $user->email], [
          'name' => $user->name,
          'social_id' => $user->id,
          'social_type' => 'facebook',
          'profile' => $user->avatar,
          'password' => encrypt('123456')
        ]);

        Auth::login($newUser);

        return redirect()->route('home');
      }

    } catch (Exception $e) {
      return redirect()->back()->with('status', $e->getMessage());
    }
  }
}
