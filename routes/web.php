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
    Route::get('/qa', [QaController::class, 'index']);
});

Route::middleware(['role:2'])->group(function () {
    Route::get('/qal', [QalController::class, 'index']);
});

Route::middleware(['role:3'])->group(function () {
    Route::get('/qam', [QamController::class, 'index']);
});

Route::middleware(['role:4'])->group(function () {
    Route::get('/pengurus', [PengurusController::class, 'index']);
});