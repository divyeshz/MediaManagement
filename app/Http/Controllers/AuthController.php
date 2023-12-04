<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  /* Facebook login page */
  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-login', ['pageConfigs' => $pageConfigs]);
  }

  /* User Logout */
  public function logout(Request $request)
  {
    Auth::logout();
    return redirect()->route('auth-login');
  }

}
