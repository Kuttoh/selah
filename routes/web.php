<?php

use App\Http\Controllers\PublicPrayerProgressController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

Route::get('/', function () {
    return view('cta');
})->name('home');

Route::redirect('/login', '/admin/login', 301);
Route::redirect('/register', '/admin/register', 301);
Route::redirect('/admin', '/admin/prayers');

Route::prefix('admin')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
    });

Route::get('/prayers/create', function () {
    return view('prayers.create');
})->name('prayers.create');

// Public callback request page
Route::get('/callbacks/request', function () {
    return view('callbacks.request');
})->name('callbacks.request');

Route::prefix('admin')->get('/prayers', function () {
    return view('prayers.prayers');
})->middleware(['auth', 'verified'])->name('prayers.index');

Route::prefix('admin')->get('/testimonials', function () {
    return view('testimonials.testimonials');
})->middleware(['auth', 'verified'])->name('admin.testimonials');

Route::prefix('admin')->get('/groups', function () {
    return view('admin.groups');
})->middleware(['auth', 'verified'])->name('admin.groups');

Route::prefix('admin')->get('/services', function () {
    return view('admin.services');
})->middleware(['auth', 'verified'])->name('admin.services');

Route::prefix('admin')->get('/callbacks', function () {
    return view('admin.callbacks');
})->middleware(['auth', 'verified'])->name('admin.callbacks');

Route::prefix('admin')->get('/callbacks/{callback}', function (\App\Models\Callback $callback) {
    return view('admin.callback-detail', ['callback' => $callback]);
})->middleware(['auth', 'verified'])->name('admin.callbacks.show');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

require __DIR__.'/settings.php';

// Public token-based progress endpoint (rate limited)
Route::get('/prayers/{token}', [PublicPrayerProgressController::class, 'show'])
    ->middleware(['throttle:prayer-progress'])
    ->name('prayers.progress');
