<?php

use App\Http\Controllers\AuditKhususController;
use App\Http\Controllers\AuditRutinController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FraudAlertController;
use App\Http\Controllers\InformasiAnggotaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengurusController;
use App\Http\Controllers\QaController;
use App\Http\Controllers\QalController;
use App\Http\Controllers\QamController;
use App\Http\Controllers\RencanaAuditController;
use App\Http\Controllers\TanggapanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporanPengurusController;


Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['role:1'])->group(function () {
    Route::get('/qa/dashboard', [QaController::class, 'index']);

    // informasi anggota
    Route::get('/informasi_anggota', [InformasiAnggotaController::class, 'index'])->name('informasi_anggota');
    Route::get('/informasi_anggota_detail/{cif}', [InformasiAnggotaController::class, 'informasi_anggota'])->name('informasi_anggota_detail');
    Route::get('/mutasi_anggota/{cif}', [InformasiAnggotaController::class, 'mutasi_anggota'])->name('mutasi_anggota');
    Route::get('/search_anggota', [InformasiAnggotaController::class, 'search'])
    ->name('search_anggota');
    Route::get('/mutasi_anggota/print/{cif}', [InformasiAnggotaController::class, 'printMutasi'])
        ->name('mutasi_anggota_print');
    //end informasi anggota

    // Rencana Audit Routes
    Route::get('/qa/rencana-audit', [RencanaAuditController::class, 'index']);
    Route::get('/qa/rencana-audit/data', [RencanaAuditController::class, 'data'])->name('rencana.audit.data');
    Route::get('/qa/rencana-audit/{ref_sampling}', [RencanaAuditController::class, 'show'])->name('rencana.audit.show');
    Route::post('/qa/rencana-audit-rutin', [RencanaAuditController::class, 'auditRutinStore'])->name('audit.rutin.store');
    Route::post('/qa/rencana-audit-khusus', [RencanaAuditController::class, 'auditKhususStore'])->name('audit.khusus.store');
    Route::get('/qa/rencana-audit/{ref_sampling}/{cif}', [RencanaAuditController::class, 'detail_sampling'])
        ->name('rencana.audit.detail_sampling');
    Route::post('/qa/rencana-audit/{id}/start', [RencanaAuditController::class, 'start'])->name('rencana.audit.start');
    Route::get('/kelompok/search', [RencanaAuditController::class, 'search'])->name('kelompok.search');
    Route::get('/kelompok/get-cif', [RencanaAuditController::class, 'getCif'])->name('kelompok.get-cif');
    // End Rencana Audit Routes

    // Audit Rutin Routes
    Route::get('/qa/audit-rutin', [AuditRutinController::class, 'index'])->name('audit.rutin.index');
    Route::get('/qa/audit-rutin/data', [AuditRutinController::class, 'getData'])->name('audit.rutin.data');
    Route::get('/qa/audit-rutin/detail/{id}/{cif}', [AuditRutinController::class, 'detail'])->name('audit.rutin.detail');
    Route::post('/qa/audit-rutin/store/{id}', [AuditRutinController::class, 'store'])->name('audit.rutin.tambah');
    Route::post('/qa/audit-rutin/ketentuan/{id_ref_sampling}/{cif}', [AuditRutinController::class, 'storeKetentuan'])
        ->name('audit.rutin.ketentuan.store');
    Route::post('/qa/audit-rutin/temuan/store/{id_ref_sampling}/{cif}', [AuditRutinController::class, 'storeTemuanLain'])
        ->name('audit.rutin.temuan-lain.store');
    Route::get('/param-ketentuan/{id}', [AuditRutinController::class, 'getByParam'])
        ->name('param.ketentuan.get');

    // Route::get('/audit-rutin/history/{id_ref_sampling}/{cif}', [AuditRutinController::class, 'history'])
    //     ->name('audit.rutin.history');
    Route::get('/qa/audit-rutin/history/{id}/{cif}', [AuditRutinController::class, 'detail_history'])->name('audit.rutin.history');
    
    // End Audit Rutin Routes

    // Audit Khusus Routes
    Route::get('/qa/audit-khusus', [AuditKhususController::class, 'index'])->name('audit.khusus.index');
    Route::get('/qa/audit-khusus/data', [AuditKhususController::class, 'getData'])->name('audit.khusus.data');
    Route::get('/qa/audit-khusus/detail/{id}/{cif}', [AuditKhususController::class, 'detail'])->name('audit.khusus.detail');
    Route::post('/qa/audit-khusus/store/{id}', [AuditKhususController::class, 'store'])->name('audit.khusus.tambah');
    Route::post('/qa/audit-khusus/ketentuan/{id_ref_sampling}/{cif}', [AuditKhususController::class, 'storeKetentuan'])
        ->name('audit.khusus.ketentuan.store');
    Route::post('/qa/audit-khusus/temuan/store/{id_ref_sampling}/{cif}', [AuditKhususController::class, 'storeTemuanLain'])
        ->name('audit.khusus.temuan-lain.store');
    Route::get('/param-ketentuan/{id}', [AuditRutinController::class, 'getByParam'])
        ->name('param.ketentuan.get');
    // End Audit Khusus Routes

    // Tanggapan Routes
    Route::get('/qa/tanggapan', [TanggapanController::class, 'index'])->name('tanggapan.index');
    Route::get('/qa/tanggapan/data', [TanggapanController::class, 'getData'])->name('tanggapan.data');
    Route::get('/qa/tanggapan/detail/{id}/{cif}', [TanggapanController::class, 'detail'])->name('tanggapan.detail');
    Route::get('/qa/tanggapan/edit/{id}/{cif}', [TanggapanController::class, 'edit'])->name('tanggapan.edit');
    Route::post('/qa/tanggapan/{id}', [TanggapanController::class, 'store'])->name('tanggapan.store');
    Route::put('/qa/tanggapan/{id}', [TanggapanController::class, 'update'])->name('tanggapan.update');
    // End Tanggapan Routes

    // Laporan Routes
    Route::get('/qa/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/qa/laporan/pdf/{id}', [LaporanController::class, 'pdf'])->name('laporan.pdf');
    Route::get('/qa/laporan/export-excel', [LaporanController::class, 'export_excel']);
    // End Laporan Routes

    // Fraud Alert Routes
    Route::get('/qa/fraud-alert', [FraudAlertController::class, 'index'])->name('fraud.alerts');
    Route::get('/fraud-alerts/export', [FraudAlertController::class, 'export'])
        ->name('fraud.alerts.export');
});

Route::middleware(['role:2'])->group(function () {
    Route::get('/qal/dashboard', [QalController::class, 'index']);
});

Route::middleware(['role:3'])->group(function () {
    Route::get('/qam/dashboard', [QamController::class, 'index']);
});

Route::middleware(['role:4'])->group(function () {
    Route::get('/pengurus/dashboard', [PengurusController::class, 'index']);

    // Laporan Routes
    Route::get('/pengurus/laporan', [LaporanPengurusController::class, 'index'])->name('pengurus.laporan.index');
    Route::get('/pengurus/laporan/pdf/{id}', [LaporanPengurusController::class, 'pdf'])->name('pengurus.laporan.pdf');
    Route::get('/pengurus/laporan/export-excel', [LaporanPengurusController::class, 'export_excel'])->name('pengurus.laporan.export_excel');
    // End Laporan Routes
});
