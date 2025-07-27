<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/auth', [AuthController::class, 'authenticate'])->name('auth');

Route::get('/dashboard', function () {
    return view('dashboard.dashboard.index');
})->name('dashboard');
Route::get('/clustering', function () {
    return view('dashboard.clustering.index');
})->name('clustering');
Route::get('/clustering/summary', function () {
    return view('dashboard.clustering.summary');
})->name('clustering.summary');
Route::get('/clustering/detail', function () {
    return view('dashboard.clustering.detail');
})->name('clustering.detail');
Route::get('/screening', function () {
    return view('dashboard.screening.index');
})->name('screening');
