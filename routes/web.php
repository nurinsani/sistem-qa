<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PengurusController;
use App\Http\Controllers\QaController;
use App\Http\Controllers\QalController;
use App\Http\Controllers\QamController;
use App\Http\Controllers\RencanaAuditController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['role:1'])->group(function () {
    Route::get('/qa/dashboard', [QaController::class, 'index']);

    // Rencana Audit Routes
    Route::get('/qa/rencana-audit', [RencanaAuditController::class, 'index']);
    Route::get('/qa/rencana-audit/data', [RencanaAuditController::class, 'data'])->name('rencana.audit.data');
    Route::get('/qa/rencana-audit/{ref_sampling}', [RencanaAuditController::class, 'show'])->name('rencana.audit.show');
    Route::post('/qa/rencana-audit-rutin', [RencanaAuditController::class, 'auditRutinStore'])->name('audit.rutin.store');
    Route::post('/qa/rencana-audit-khusus', [RencanaAuditController::class, 'auditKhususStore'])->name('audit.khusus.store');
    Route::get('/qa/rencana-audit/{ref_sampling}/{cif}', [RencanaAuditController::class, 'detail_sampling'])->name('rencana.audit.detail_sampling');
    Route::post('/qa/rencana-audit/{id}/start', [RencanaAuditController::class, 'start'])->name('rencana.audit.start');
    Route::get('/kelompok/search', [RencanaAuditController::class, 'search'])->name('kelompok.search');
    Route::get('/kelompok/get-cif', [RencanaAuditController::class, 'getCif'])->name('kelompok.get-cif');
    // End Rencana Audit Routes

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