<?php

use App\Http\Controllers\pages\Page2;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\UserController;


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

  Route::controller(UserController::class)->group(function () {
    Route::prefix('user')->group(function () {
      Route::get('list', 'index')->name('user.list');
      Route::get('create', 'create')->name('user.create');
      Route::post('store', 'store')->name('user.store');
      Route::get('edit/{id}', 'edit')->name('user.edit');
      Route::post('update/{id}', 'update')->name('user.update');
      Route::get('destroy/{id}', 'destroy')->name('user.destroy');
      Route::post('status', 'status')->name('user.status');
    });
  });

  // Main Page Route
  Route::get('/home', [HomePage::class, 'index'])->name('home');

  Route::get('/page-2', [FacebookController::class, 'index'])->name('pages-page-2');

  Route::get('/page-2', [Page2::class, 'index'])->name('pages-page-2');

  Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
});
