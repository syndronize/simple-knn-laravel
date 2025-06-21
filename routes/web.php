<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('authentication.login');
})->name('login');
Route::get('/core', function () {
    return view('core.index');
});
Route::get('/eg', function () {
    return view('eg.index');
});
Route::get('/forgot-password', function () {
    return view('authentication.forgot-password');
})->name('password.request');
Route::get('/dashboard', function () {
    return view('dashboard.index');
})->name('dashboard');
