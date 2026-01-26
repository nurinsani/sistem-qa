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
        Schema::create('data_sampling', function (Blueprint $table) {
            $table->integer('id_sampling')->primary();
            $table->string('unit',6);
            $table->string('cif',10);
            $table->integer('id_ref_sampling');
            $table->string('nama',100);
            $table->string('kode_kel');
            $table->string('cao',6);
            $table->string('kategori_audit');
            $table->string('user_id');
            $table->timestamps();            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_sampling');
    }
};
