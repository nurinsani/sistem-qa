<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PengurusController;
use App\Http\Controllers\QaController;
use App\Http\Controllers\QalController;
use App\Http\Controllers\QamController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['role:1'])->group(function () {
    Route::get('/qa/dashboard', [QaController::class, 'index']);
});

Route::middleware(['role:2'])->group(function () {
    Route::get('/qal/dashboard', [QalController::class, 'index']);
});

Route::middleware(['role:3'])->group(function () {
    Route::get('/qam/dashboard', [QamController::class, 'index']);
});

Route::middleware(['role:4'])->group(function () {
    Route::get('/pengurus/dashboard', [PengurusController::class, 'index']);
});