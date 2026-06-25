<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — HackBridge
|--------------------------------------------------------------------------
*/

// Landing page (before login)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// After login → redirect to dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Auth routes (login, register, logout)
// These are provided automatically if you use Laravel Breeze:
// php artisan breeze:install blade
// php artisan migrate