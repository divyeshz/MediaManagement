<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\Page2;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\FacebookController;



// Main Page Route
// Route::get('/', [HomePage::class, 'index'])->name('pages-home');

Route::redirect('/', '/auth/login');

// authentication
Route::get('/auth/login', [LoginBasic::class, 'index'])->name('auth-login');
Route::get('/page-2', [FacebookController::class, 'index'])->name('pages-page-2');
// Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');


Route::get('/page-2', [Page2::class, 'index'])->name('pages-page-2');

// pages
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
