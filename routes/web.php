<?php

use App\Http\Controllers\pages\Page2;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\FacebookController;


// Facebook Routes Group
Route::controller(FacebookController::class)->group(function () {
  Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
  Route::get('auth/facebook/callback', 'handleFacebookCallback');
});

// Authentication Routes Group
Route::controller(AuthController::class)->group(function () {

  Route::get('/', 'index')->name('auth-login');

  Route::group(['middleware' => 'auth'], function () {
    Route::post('logout', 'logout')->name('logout');
  });

});

Route::group(['middleware' => ['auth']], function () {

  // Main Page Route
  Route::get('/home', [HomePage::class, 'index'])->name('home');

  Route::get('/page-2', [FacebookController::class, 'index'])->name('pages-page-2');

  Route::get('/page-2', [Page2::class, 'index'])->name('pages-page-2');

  Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
});
