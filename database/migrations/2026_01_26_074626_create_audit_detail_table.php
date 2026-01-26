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
            $table->bigInteger('id_audit');
            $table->text('kondisi_usaha');
            $table->text('kondisi_keluarga');
            $table->text('kondisi_lingkungan');
            $table->text('wawancara_anggota');
            $table->text('wawancara_ketua_kel');
            $table->string('foto_wawancara_ketua');
            $table->string('foto_wawancara_anggota');
            $table->string('foto_usaha');
            $table->text('temuan');
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
