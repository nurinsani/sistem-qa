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
        Schema::create('audit', function (Blueprint $table) {
            $table->id();
            $table->string('id_ref_sampling', 50);
            $table->string('cif',10);
            $table->date('tanggal');
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
        Schema::dropIfExists('audit');
    }
};
