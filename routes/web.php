<?php

use App\Http\Controllers\PublicPrayerProgressController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('cta');
})->name('home');

Route::get('/prayers/create', function () {
    return view('prayers.create');
})->name('prayers.create');

Route::get('/prayers/{token}', [PublicPrayerProgressController::class, 'show'])
    ->middleware(['throttle:prayer-progress'])
    ->name('prayers.progress');

Route::get('/callbacks/request', function () {
    return view('callbacks.request');
})->name('callbacks.request');

Route::get('/share-testimony', function () {
    return view('testimonials.share');
})->name('testimonials.share');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::redirect('/login', '/admin/login', 301);
Route::redirect('/register', '/admin/register', 301);
Route::redirect('/admin', '/admin/prayers');

Route::prefix('admin')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
    });

Route::prefix('admin')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/prayers', fn () => view('prayers.prayers'))->name('prayers.index');
        Route::get('/testimonials', fn () => view('testimonials.testimonials'))->name('admin.testimonials');
        Route::get('/groups', fn () => view('admin.groups'))->name('admin.groups');
        Route::get('/services', fn () => view('admin.services'))->name('admin.services');
        Route::get('/callbacks', fn () => view('admin.callbacks'))->name('admin.callbacks');
        Route::get('/callbacks/{callback}', fn (\App\Models\Callback $callback) => view('admin.callback-detail', ['callback' => $callback]))->name('admin.callbacks.show');
    });

require __DIR__.'/settings.php';
