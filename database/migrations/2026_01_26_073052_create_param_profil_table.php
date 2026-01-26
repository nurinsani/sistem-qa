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
            $table->id();
            $table->string('id_param');
            $table->string('deskripsi');
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
