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
        Schema::create('audit_rutin', function (Blueprint $table) {
            $table->string('id_audit')->primary();
            $table->string('id_sampling');
            $table->string('cif',10);
            $table->date('tanggal');
            $table->string('jenis_audit');
            $table->string('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_rutin');
    }
};
