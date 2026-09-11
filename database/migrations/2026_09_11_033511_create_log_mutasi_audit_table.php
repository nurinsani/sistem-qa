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
        Schema::create('log_mutasi_audit', function (Blueprint $table) {
            $table->id();
            $table->string('id_ref_sampling', 25);
            $table->enum('jenis_mutasi', ['status', 'petugas', 'unit']);
            $table->string('nilai_lama', 100)->nullable();
            $table->string('nilai_baru', 100);
            $table->bigInteger('diubah_oleh');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_mutasi_audit');
    }
};
