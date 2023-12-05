<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  /* Facebook login page */
  public function index()
  {
    return view('content.authentications.auth-login');
  }

  /* User Logout */
  public function logout()
  {
    Auth::logout();
    return redirect()->route('auth-login');
  }

}
