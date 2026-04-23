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
        Schema::create('masterqa', function (Blueprint $table) {
            $table->string('code_qa', 10)->primary();
            $table->string('nama_qa', 100);
            $table->string('no_tlp', 15);
            $table->string('kode_unit', 10);
            $table->string('atasan', 16);
            $table->string('nik_qa', 20);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_qa');
    }
};
