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
        Schema::create('temuan_lain', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('id_audit_rutin');
            $table->string('id_param_profil');
            $table->string('id_ketentuan');
            $table->string('status_audit');
            $table->text('deskripsi_temuan',255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temuan_lain');
    }
};
