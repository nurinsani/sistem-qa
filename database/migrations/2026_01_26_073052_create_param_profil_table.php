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
        Schema::create('param_profil', function (Blueprint $table) {
            $table->string('id_param')->primary();
            $table->text('deskripsi',255);
            $table->string('level');
            $table->integer('kategori_param');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('param_profil');
    }
};
