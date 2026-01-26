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
            $table->string('id_audit_detail');
            $table->string('id_audit')->primary();
            $table->text('usaha',255);
            $table->text('kondisi_keluarga',255);
            $table->text('kondisi_lingkungan',255);
            $table->text('wawancara_anggota',255);
            $table->text('wawancara_ketua_kel',255);
            $table->string('foto_wawancara_ketua');
            $table->string('foto_wawancara_anggota');
            $table->string('foto_usaha');
            $table->text('temuan',255);
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
