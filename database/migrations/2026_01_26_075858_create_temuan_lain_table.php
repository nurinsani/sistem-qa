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
            $table->id();
            $table->string('id');
            $table->string('id_audit_rutin');
            $table->string('id_param_profil');
            $table->string('id_ketentuan');
            $table->string('status_audit');
            $table->string('deskripsi_temuan');
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
