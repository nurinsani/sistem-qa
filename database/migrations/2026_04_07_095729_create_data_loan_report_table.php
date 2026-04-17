<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_loan_report', function (Blueprint $table) {
            $table->dateTime('tanggal')->nullable();
            $table->string('code_kel', 10)->nullable();
            $table->string('nama_kel', 255)->nullable();
            $table->string('norek', 50)->nullable();
            $table->string('cif', 50)->nullable();
            $table->string('nama', 255)->nullable();
            $table->string('Deal_type', 50)->nullable();
            $table->string('suffix', 50)->nullable();
            $table->decimal('baghas', 18, 2)->nullable();
            $table->integer('tenor')->nullable();
            $table->decimal('Plafond', 18, 2)->nullable();
            $table->decimal('Os', 18, 2)->nullable();
            $table->decimal('saldo_margin', 18, 2)->nullable();
            $table->decimal('angsuran', 18, 2)->nullable();
            $table->decimal('pokok', 18, 2)->nullable();
            $table->decimal('ijaroh', 18, 2)->nullable();
            $table->decimal('nyata_setor', 18, 2)->nullable();
            $table->integer('run_tenor')->nullable();
            $table->string('unit', 50)->nullable();
            $table->date('tgl_akad')->nullable();
            $table->date('tgl_wakalah')->nullable();
            $table->date('tgl_murab')->nullable();
            $table->date('next_schedule')->nullable();
            $table->date('jatpo')->nullable();
            $table->date('last_payment')->nullable();
            $table->string('hari', 11)->nullable();
            $table->string('cao', 10)->nullable();
            $table->string('Userid', 10)->nullable();
            $table->string('STATUS', 50)->nullable();
            $table->string('status_usia', 50)->nullable();
            $table->string('status_app', 50)->nullable();
            $table->string('collect', 50)->nullable();
            $table->string('deal_produk', 50)->nullable();
            $table->string('nama_usaha', 50)->nullable();
            $table->string('deskripsi_usaha', 50)->nullable();
            $table->string('area', 50)->nullable();
            $table->string('cabang', 50)->nullable();
            $table->string('AP', 50)->nullable();
            $table->decimal('twm', 18, 2)->nullable();
            $table->decimal('sim_pokok', 18, 0)->nullable();
            $table->bigInteger('sim_wajib')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_loan_report');
    }
};
