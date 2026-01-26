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
            $table->string('id_ketentuan')->primary();
            $table->string('id_param_profil');
            $table->string('nomer_ketentuan');
            $table->text('deskripsi_ketentuan');
            $table->text('point_ketentuan');
            $table->text('isi_ketentuan');
            $table->text('halaman');
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
