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
        Schema::create('sampling', function (Blueprint $table) {
            $table->string('cif', 10)->primary();
            $table->string('unit', 6);
            $table->string('kode_profil', 15);
            $table->string('status_sampling', 15);
            $table->string('kode_kel', 15);
            $table->string('kode_ao', 15);
            $table->date('tgl_pull');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sampling');
    }
};
