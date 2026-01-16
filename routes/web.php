<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\CreatePrayer;

Route::get('/', function () {
    return view('cta');
})->name('home');

Route::get('/prayers/create', function () {
    return view('prayers.create');
})->name('prayers.create');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';
