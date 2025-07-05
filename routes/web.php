<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\Backend\LeadController;
use App\Http\Controllers\Backend\FollowUpController;
use App\Http\Controllers\Backend\IndustriesController;
use App\Http\Controllers\Backend\CustomersController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\PenagihanController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\AuthenticationController;
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

Route::middleware('cantlogin')->group(function () {
    Route::get('/', [AuthenticationController::class, 'showLogin'])->name('login');
    Route::post('/loginsession', [AuthenticationController::class, 'login'])->name('loginsession');
});
Route::middleware('canlogin')->group(function () {
    Route::get('/logout', [AuthenticationController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::post('/users', [UsersController::class, 'create'])->name('users.create');
    Route::get('/users/{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UsersController::class, 'destroy'])->name('users.destroy');

    Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
    Route::post('/leads', [LeadController::class, 'create'])->name('leads.create');
    Route::get('/leads/{id}/edit', [LeadController::class, 'edit'])->name('leads.edit');
    Route::put('/leads/{id}', [LeadController::class, 'update'])->name('leads.update');
    Route::delete('/leads/{id}', [LeadController::class, 'destroy'])->name('leads.destroy');

    Route::get('/follow-ups', [FollowUpController::class, 'index'])->name('follow-ups.index');
    Route::post('/follow-ups', [FollowUpController::class, 'create'])->name('follow-ups.create');
    Route::get('/follow-ups/{id}/edit', [FollowUpController::class, 'edit'])->name('follow-ups.edit');
    Route::get('/follow-ups/{id}/knn', [FollowUpController::class, 'knn'])->name('follow-ups.knn');
    Route::put('/follow-ups/{id}', [FollowUpController::class, 'update'])->name('follow-ups.update');
    Route::get('/follow-ups/{id}/detail', [FollowUpController::class, 'detail'])->name('follow-ups.detail');

    Route::get('/industries', [IndustriesController::class, 'index'])->name('industries.index');
    Route::post('/industries', [IndustriesController::class, 'create'])->name('industries.create');
    Route::get('/industries/{id}/edit', [IndustriesController::class, 'edit'])->name('industries.edit');
    Route::put('/industries/{id}', [IndustriesController::class, 'update'])->name('industries.update');

    Route::get('/customers', [CustomersController::class, 'index'])->name('customers.index');
    Route::post('/customers', [CustomersController::class, 'create'])->name('customers.create');
    Route::get('/customers/{id}/detail', [CustomersController::class, 'detail'])->name('customers.detail');
    Route::put('/customers/{id}', [CustomersController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{id}/dokumen/{index}', [CustomersController::class, 'deleteDokumen']);

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class, 'create'])->name('products.create');
    Route::get('/products/{id}/detail', [ProductController::class, 'detail'])->name('products.detail');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');

    Route::get('/penagihan', [PenagihanController::class, 'index'])->name('penagihan.index');
    Route::post('/penagihan', [PenagihanController::class, 'create'])->name('penagihan.create');
    Route::get('/penagihan/{id}/detail', [PenagihanController::class, 'detail'])->name('penagihan.detail');
    Route::put('/penagihan/{id}', [PenagihanController::class, 'update'])->name('penagihan.update');

    Route::get('/export-penagihan', [ReportController::class, 'exportpenagihan'])->name('export.penagihan');
    Route::get('/export-leads', [ReportController::class, 'exportleads'])->name('export.leads');
});
