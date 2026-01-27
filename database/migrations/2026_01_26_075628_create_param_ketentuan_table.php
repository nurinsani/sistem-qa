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
        Schema::create('param_ketentuan', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_param_profil');
            $table->string('nomor_ketentuan',30);
            $table->text('heading');
            $table->text('sub_heading');
            $table->text('sub_sub_heading');
            $table->text('sub_sub_sub_heading');
            $table->text('sub_sub_sub_sub_heading');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('param_ketentuan');
    }
};
