<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('cta');
})->name('home');

Route::get('/prayers/create', function () {
    return view('prayers.create');
})->name('prayers.create');

Route::get('/prayers', function () {
    return view('prayers.prayers');
})->middleware(['auth', 'verified'])->name('prayers.index');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

require __DIR__.'/settings.php';
