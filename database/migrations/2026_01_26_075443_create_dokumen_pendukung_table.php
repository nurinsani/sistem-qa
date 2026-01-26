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
        Schema::create('dokumen_pendukung', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('id_audit_rutin_detail');
            $table->string('status');
            $table->text('deskripsi',255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_pendukung');
    }
};
