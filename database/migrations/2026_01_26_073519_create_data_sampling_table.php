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
            $table->id();
            $table->string('unit',6);
            $table->string('cif',10);
            $table->string('id_ref_sampling',25);
            $table->string('nama',100);
            $table->string('kode_kel',10);
            $table->string('cao',6);
            $table->enum('jenis_audit',['audit_rutin','audit_khusus']);
            $table->bigInteger('user_id');
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
