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
            $table->bigInteger('id_ref_sampling'); //TODO: pastikan tipe data ini sesuai dengan tipe data id_ref_sampling di tabel data_sampling
            $table->bigInteger('id_param_profil');
            $table->bigInteger('id_ketentuan');
            $table->string('cif', 10);
            $table->string('status_audit',10);
            $table->text('deskripsi_temuan');
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
