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
        Schema::create('audit_detail', function (Blueprint $table) {
            $table->id();
            $table->string('id_audit_detail');
            $table->string('id_audit');
            $table->string('usaha');
            $table->string('kondisi_keluarga');
            $table->string('kondisi_lingkungan');
            $table->string('wawancara_anggota');
            $table->string('wawancara_ketua_kel');
            $table->string('foto_wawancara_ketua');
            $table->string('foto_wawancara_anggota');
            $table->string('foto_usaha');
            $table->string('temuan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_detail');
    }
};
