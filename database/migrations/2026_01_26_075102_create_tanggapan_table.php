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
        Schema::create('tanggapan', function (Blueprint $table) {
            $table->string('id_audit')->primary();
            $table->text('tanggapan_ao',255);
            $table->text('tanggapan_mm',255);
            $table->text('tanggapan_bm',255);
            $table->text('tindak_lanjut',255);
            $table->date('due_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanggapan');
    }
};
