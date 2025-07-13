<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('dashboard.dashboard.index');
})->name('dashboard');
Route::get('/clustering', function () {
    return view('dashboard.clustering.index');
})->name('clustering');
Route::get('/screening', function () {
    return view('dashboard.screening.index');
})->name('screening');
