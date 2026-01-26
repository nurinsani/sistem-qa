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
        Schema::create('param_ketentuan', function (Blueprint $table) {
            $table->id();
            $table->string('id_ketentuan');
            $table->string('id_param_profil');
            $table->string('nomer_ketentuan');
            $table->string('deskripsi_ketentuan');
            $table->string('point_ketentuan');
            $table->string('isi_ketentuan');
            $table->string('halaman');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('param_ketentuan');
    }
};
