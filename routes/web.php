<?php

use App\Http\Controllers\pages\Page2;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;


// Facebook Routes Group
Route::controller(FacebookController::class)->group(function () {
  Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
  Route::get('auth/facebook/callback', 'handleFacebookCallback');
});

// Authentication Routes Group
Route::controller(AuthController::class)->group(function () {
  Route::get('/', 'index')->name('auth-login')->middleware('guest');;
});

Route::group(['middleware' => ['auth']], function () {

  Route::controller(AuthController::class)->group(function () {
    Route::post('logout', 'logout')->name('logout');
  });

  Route::controller(UserController::class)->group(function () {
    Route::prefix('user')->group(function () {
      Route::get('profile', 'profile')->name('profile');
      Route::get('list', 'index')->name('user.list');
      Route::get('edit/{id}', 'edit')->name('user.edit');
      Route::post('update/{id}', 'update')->name('user.update');
      Route::get('destroy/{id}', 'destroy')->name('user.destroy');
      Route::post('status', 'status')->name('user.status');
    });
  });

  Route::controller(PostController::class)->group(function () {
    Route::prefix('post')->group(function () {
      Route::get('list', 'index')->name('post.list');
      Route::get('create', 'createEdit')->name('post.create');
      Route::post('store', 'store')->name('post.store');
      Route::get('edit/{id}', 'createEdit')->name('post.edit');
      Route::post('update/{id}', 'update')->name('post.update');
      Route::post('destroy/{id}', 'destroy')->name('post.destroy');
      Route::post('status', 'status')->name('post.status');
      Route::post('sharePosts', 'sharePosts')->name('post.sharePosts');
      Route::get('sharePostsList', 'sharePostsList')->name('post.sharePostsList');
    });
  });

  // Main Page Route
  Route::get('/home', [HomePage::class, 'index'])->name('home');
});
